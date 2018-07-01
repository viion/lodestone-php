<?php

namespace Lodestone\Parser\Achievements;

use Lodestone\{
    Entities\Character\Achievements,
    Entities\Character\Achievement,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
    Modules\Http\Routes,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser\Achievements
 */
class Parser extends ParserHelper
{
    /** @var Achievements */
    protected $achievements;
    
    /**
     * Parser constructor.
     *
     * @param int $category
     */
    function __construct(int $category, $id)
    {
        $this->achievements = new Achievements();
        $this->achievements->setCharacter($id);
        $this->achievements->setCategory($category);
    }
    
    /**
     * @return Achievements
     */
    public function parse(bool $includeUnobtained, bool $details, $detailsAchievementId = false, string $useragent, string $language)
    {
        if ($detailsAchievementId === false) {
            $this->initialize();
        }
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        
        // parse achievements
        if ($detailsAchievementId === false) {
            $this->parseAchievements($includeUnobtained, $details, $useragent, $language);
        } else {
            $achievement = new Achievement();
            $this->parseAchievementDetails($achievement, $detailsAchievementId, $useragent, $language);
            $this->achievements->addAchievement($achievement);
        }
     
        // fin
        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
        
        return $this->achievements;
    }
    
    /**
     * Parse a characters achievements
     */
    private function parseAchievements(bool $includeUnobtained, bool $details, string $useragent, string $language)
    {
        $box = $this->getSpecial__Achievements();
        $rows = $box->find('li');
        
        foreach($rows as $node) {
            
            $achievement = new Achievement();

            // Get achievements data
            if (!empty($achnode = $node->find(($includeUnobtained ? '.entry__achievement' : '.entry__achievement--complete'), 0))) {
                $detailsAchievementId = explode('/', $achnode->getAttribute('href'))[6];
                $achievement
                    ->setId($detailsAchievementId)
                    ->setName($node->find('.entry__activity__txt', 0)->plaintext)
                    ->setIcon(explode('?', $node->find('.entry__achievement__frame', 0)->find('img', 0)->getAttribute("src"))[0])
                    ->setPoints( intval($node->find('.entry__achievement__number', 0)->plaintext) );
                
                if ($details) {
                    $this->parseAchievementDetails($achievement, $detailsAchievementId, $useragent, $language);
                }
                
                // timestamp
                $this->achievementTime($achievement, $node->find('.entry__activity__time', 0));
    
                // add achievement
                $this->achievements->addAchievement($achievement);
            }
        }
    }
    
    /**
     * Parse achievement details
     */
    private function parseAchievementDetails($achievement, $detailsAchievementId, string $useragent, string $language)
    {
        $url = sprintf((new Routes($language))::$LODESTONE_ACHIEVEMENTS_DET_URL, $this->achievements->getCharacter(), $detailsAchievementId);
        $this->url($url, $useragent)->initialize();
        $box = $this->getSpecial__AchievementDetails();
        $achievement
            ->setId($detailsAchievementId)
            ->setName($box->find('.entry__achievement--list>p')->plaintext)
            ->setIcon(explode('?', $box->find('.entry__achievement__frame', 0)->find('img', 0)->getAttribute("src"))[0])
            ->setPoints(intval($box->find('.entry__achievement__number', 0)->plaintext));
        
        $this->achievementTime($achievement, $box->find('.entry__activity__time', 0));
        
        //How to description and title reward
        $howto = $box->find('.achievement__base--text');
        if (!empty($howto[1])) {
            $achievement->setTitle($howto[1]->plaintext);
        }
        $achievement->setHowto($howto[0]->plaintext);
        
        //Category
        $rows = $this->getSpecial__AchievementCategories();
        foreach ($rows->find('dt') as $row) {
            if ($row->find('.close')->count()) {
                $achievement->setCategory($row->plaintext);
                break;
            }
        }
        
        //Subcategory
        $achievement->setSubcategory($rows->find('dd .active')->plaintext);
        
        //Item reward
        $item = $box->find('.item-list__name');
        if ($item->count()) {
            $achievement->setItem(
                explode('/',  $box->find('.item-list__name a', 0)->getAttribute('href'))[5],
                $box->find('.item-list__name')->plaintext,
                explode('?', $box->find('.db-list__item__icon__item_image', 0)->find('img', 0)->getAttribute("src"))[0]
            );
        }
    }
    
    private function achievementTime($achievement, $timestamp): void
    {
        if ($timestamp) {
            $timestamp = $timestamp->plaintext;
            $timestamp = trim(explode('(', $timestamp)[2]);
            $timestamp = trim(explode(',', $timestamp)[0]);
            $timestamp = $timestamp ? new \DateTime('@' . $timestamp) : null;
            if ($timestamp) {
                // if obtained, increment obtained points
                $this->achievements->incPointsObtained($achievement->getPoints());
                $this->achievements->incPointsTotal($achievement->getPoints());
                $achievement->setTimestamp($timestamp);
            } else {
                $this->achievements->incPointsTotal($achievement->getPoints());
            }
        } else {
            $this->achievements->incPointsTotal($achievement->getPoints());
        }
    }
}
