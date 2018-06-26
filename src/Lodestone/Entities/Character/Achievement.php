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
     * @index ID
     */
    protected $id;
    
    /**
     * @var string
     * @index Name
     */
    protected $name;
    
    /**
     * @var string
     * @index Category
     */
    protected $category;
    
    /**
     * @var string
     * @index SubCategory
     */
    protected $subcategory;
    
    /**
     * @var string
     * @index HowTo
     */
    protected $howto;
    
    /**
     * @var string
     * @index Icon
     */
    protected $icon;
    
    /**
     * @var int
     * @index Points
     */
    protected $points = 0;
    
    /**
     * @var string
     * @index Title
     */
    protected $title;
    
    /**
     * @var array
     * @index Item
     */
    protected $item = [];
    
    /**
     * @var \DateTime
     * @index Timestamp
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
    
    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        CharacterValidator::getInstance()
            ->check($name, 'Achievement Name')
            ->isInitialized()
            ->isString()
            ->validate();

        $this->name = $name;
        return $this;
    }
    
    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon)
    {
        CharacterValidator::getInstance()
            ->check($icon, 'Icon URL')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->icon = $icon;

        return $this;
    }
    
    /**
     * @param string $howto
     * @return $this
     */
    public function setHowto(string $howto)
    {
        CharacterValidator::getInstance()
            ->check($howto, 'How to')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->howto = $howto;

        return $this;
    }
    
    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        CharacterValidator::getInstance()
            ->check($title, 'Title')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->title = $title;

        return $this;
    }
    
    /**
     * @param string $category
     * @return $this
     */
    public function setCategory(string $category)
    {
        CharacterValidator::getInstance()
            ->check($category, 'Category')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->category = $category;

        return $this;
    }
    
    /**
     * @param string $title
     * @return $this
     */
    public function setSubcategory(string $subcategory)
    {
        CharacterValidator::getInstance()
            ->check($subcategory, 'Subcategory')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->subcategory = $subcategory;

        return $this;
    }
    
    /**
     * @param string $item
     * @return $this
     */
    public function setItem(string $id, string $name, string $icon)
    {
        CharacterValidator::getInstance()
            ->check($id, 'Item ID')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
            
        CharacterValidator::getInstance()
            ->check($name, 'Item name')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
            
        CharacterValidator::getInstance()
            ->check($icon, 'Item icon')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        $this->item = ['id'=>$id,'name'=>$name,'icon'=>$icon];

        return $this;
    }
}
