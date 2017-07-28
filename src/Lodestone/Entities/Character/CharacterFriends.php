<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\CharacterValidator
};

/**
 * Class Profile
 *
 * @package Lodestone\Entities\Character
 */
class CharacterFriends extends AbstractEntity
{
    /**
     * @var int
     */
    protected $pageCurrent;
    
    /**
     * @var int
     */
    protected $pageTotal;
    
    /**
     * @var int
     */
    protected $total;
    
    /**
     * @var array
     */
    protected $characters = [];
    
    /**
     * @return int
     */
    public function getPageCurrent(): int
    {
        return $this->pageCurrent;
    }
    
    /**
     * @param int $pageCurrent
     * @return CharacterFriends
     */
    public function setPageCurrent(int $pageCurrent)
    {
        CharacterValidator::getInstance()
            ->check($pageCurrent, 'Current Page')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->pageCurrent = $pageCurrent;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPageTotal(): int
    {
        return $this->pageTotal;
    }
    
    /**
     * @param int $pageTotal
     * @return CharacterFriends
     */
    public function setPageTotal(int $pageTotal)
    {
        CharacterValidator::getInstance()
            ->check($pageTotal, 'Page Total')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->pageTotal = $pageTotal;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
    
    /**
     * @param int $total
     * @return CharacterFriends
     */
    public function setTotal(int $total)
    {
        CharacterValidator::getInstance()
            ->check($total, 'Total')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->total = $total;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
    
    /**
     * @param array $characters
     * @return CharacterFriends
     */
    public function setCharacters(array $characters)
    {
        CharacterValidator::getInstance()
            ->check($characters, 'Total')
            ->isInitialized()
            ->isArray()
            ->validate();
        
        $this->characters = $characters;
        
        return $this;
    }
    
    /**
     * @param CharacterSimple $character
     * @return $this
     */
    public function addCharacter(CharacterSimple $character)
    {
        CharacterValidator::getInstance()
            ->check($character, 'Total')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->characters[] = $character;
        
        return $this;
    }
}