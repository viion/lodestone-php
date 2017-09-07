<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\CharacterValidator
};

/**
 * Class Achievements
 *
 * @package Lodestone\Entities\Character
 */
class Achievements extends AbstractEntity
{
    /**
     * @var int
     */
    protected $pointsObtained = 0;
    
    /**
     * @var int
     */
    protected $pointsTotal = 0;
    
    /**
     * @var int
     */
    protected $category;
    
    /**
     * @var array
     */
    protected $achievements = [];
    
    /**
     * @return int
     */
    public function getPointsObtained(): int
    {
        return $this->pointsObtained;
    }
    
    /**
     * @param int $pointsObtained
     * @return Achievements
     */
    public function setPointsObtained(int $pointsObtained)
    {
        CharacterValidator::getInstance()
            ->check($pointsObtained, 'pointsObtained')
            ->isInitialized()
            ->isNumeric()
            ->validate();
        
        $this->pointsObtained = $pointsObtained;
        
        return $this;
    }
    
    /**
     * @param int $pointsTotal
     * @return $this
     */
    public function incPointsTotal(int $pointsTotal)
    {
        CharacterValidator::getInstance()
            ->check($pointsTotal, 'pointsTotal')
            ->isInitialized()
            ->isNumeric()
            ->validate();
        
        $this->pointsTotal += $pointsTotal;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPointsTotal(): int
    {
        return $this->pointsTotal;
    }
    
    /**
     * @param int $pointsTotal
     * @return Achievements
     */
    public function setPointsTotal(int $pointsTotal)
    {
        CharacterValidator::getInstance()
            ->check($pointsTotal, 'pointsTotal')
            ->isInitialized()
            ->isNumeric()
            ->validate();
        
        $this->pointsTotal = $pointsTotal;
        
        return $this;
    }
    
    /**
     * @param int $pointsObtained
     * @return $this
     */
    public function incPointsObtained(int $pointsObtained)
    {
        CharacterValidator::getInstance()
            ->check($pointsObtained, 'pointsObtained')
            ->isInitialized()
            ->isNumeric()
            ->validate();
        
        $this->pointsObtained += $pointsObtained;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
    
    /**
     * @param int $category
     * @return Achievements
     */
    public function setCategory(int $category)
    {
        CharacterValidator::getInstance()
            ->check($category, 'category')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->category = $category;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getAchievements(): array
    {
        return $this->achievements;
    }
    
    /**
     * @param array $achievements
     * @return Achievements
     */
    public function setAchievements(array $achievements)
    {
        CharacterValidator::getInstance()
            ->check($achievements, 'achievements')
            ->isInitialized()
            ->isArray()
            ->validate();
        
        $this->achievements = $achievements;
        
        return $this;
    }
    
    /**
     * @param Achievement $achievement
     * @return Achievements
     */
    public function addAchievement(Achievement $achievement)
    {
        $this->achievements[] = $achievement;
        
        return $this;
    }
}