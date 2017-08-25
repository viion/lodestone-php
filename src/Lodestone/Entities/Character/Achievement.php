<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\CharacterValidator
};

/**
 * Class Achievement
 *
 * @package Lodestone\Entities\Character
 */
class Achievement extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var int
     */
    protected $points = 0;
    
    /**
     * @var \DateTime
     */
    protected $timestamp;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     * @return Achievement
     */
    public function setId(int $id)
    {
        CharacterValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
    
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }
    
    /**
     * @param int $points
     * @return Achievement
     */
    public function setPoints(int $points)
    {
        CharacterValidator::getInstance()
            ->check($points, 'Points', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
    
        $this->points = $points;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }
    
    /**
     * @param \DateTime $timestamp
     * @return Achievement
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        CharacterValidator::getInstance()
            ->check($timestamp, 'DateTime', $this->id)
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->timestamp = $timestamp;
        
        return $this;
    }
}