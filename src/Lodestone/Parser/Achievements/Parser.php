<?php

namespace Lodestone\Parser\Achievements;

use Lodestone\{
    Entities\Character\Achievements,
    Entities\Character\Achievement,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
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
    function __construct(int $category)
    {
        $this->achievements = new Achievements();
        $this->achievements->setCategory($category);
    }
    
    /**
     * @return Achievements
     */
    public function parse(bool $includeUnobtained)
    {
        $this->initialize();
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        
        // parse achievements
        $this->parseAchievements($includeUnobtained);
     
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
    private function parseAchievements(bool $includeUnobtained)
    {
        $box = $this->getSpecial__Achievements();
        $rows = $box->find('li');
        
        foreach($rows as $node) {
            
            $achievement = new Achievement();

            // Get achievements data
            if (!empty($achnode = $node->find(($includeUnobtained ? '.entry__achievement' : '.entry__achievement--complete'), 0))) {
                $achievement
                    ->setId( explode('/', $achnode->getAttribute('href'))[6] )
                    ->setName($node->find('.entry__activity__txt', 0)->plaintext)
                    ->setIcon(explode('?', $node->find('.entry__achievement__frame', 0)->find('img', 0)->getAttribute("src"))[0])
                    ->setPoints( intval($node->find('.entry__achievement__number', 0)->plaintext) );
    
                // timestamp
                $timestamp = $node->find('.entry__activity__time', 0);
                if ($timestamp) {
                    $timestamp = $timestamp->plaintext;
                    $timestamp = trim(explode('(', $timestamp)[2]);
                    $timestamp = trim(explode(',', $timestamp)[0]);
                    $timestamp = $timestamp ? new \DateTime('@' . $timestamp) : null;
        
                    // if obtained, increment obtained points
                    if ($timestamp) {
                        $achievement->setTimestamp($timestamp);
                        $this->achievements->incPointsObtained($achievement->getPoints());
                    }
                }
                
                // increment total points
                $this->achievements->incPointsTotal($achievement->getPoints());
    
                // add achievement
                $this->achievements->addAchievement($achievement);
            }
        }
    }
}
