<?php

namespace Lodestone\Parser;

use Lodestone\{
    Entities\Character\Achievements as Achs,
    Entities\Character\Achievement,
    Modules\Http\Routes,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser\Achievements
 */
class Achievements extends ParserHelper
{
    /** @var Achievements */
    public $results;
    
    /**
     * Parser constructor.
     *
     * @param int $category
     */
    function __construct(string $html, bool $includeUnobtained, $detailsAchievementId = false)
    {
        $this->results = new Achs();
        $this->html = $html;
        $this->initialize();
        
        // parse achievements
        if ($detailsAchievementId === false) {
            $this->parseAchievements($includeUnobtained);
        } else {
            $this->results = $this->parseAchievementDetails($detailsAchievementId);
        }
    }
    
    /**
     * Parse a characters achievements
     */
    private function parseAchievements(bool $includeUnobtained)
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
                
                // timestamp
                $this->achievementTime($achievement, $node->find('.entry__activity__time', 0));
    
                // add achievement
                $this->results->addAchievement($achievement);
            }
        }
    }
    
    /**
     * Parse achievement details
     */
    private function parseAchievementDetails($detailsAchievementId)
    {
        $achievement = new Achievement();
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
        return $achievement;
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
                $this->results->incPointsObtained($achievement->getPoints());
                $this->results->incPointsTotal($achievement->getPoints());
                $achievement->setTimestamp($timestamp);
            } else {
                $this->results->incPointsTotal($achievement->getPoints());
            }
        } else {
            $this->results->incPointsTotal($achievement->getPoints());
        }
    }
}
