<?php

namespace Lodestone\Entities\FreeCompany;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Character\CharacterSimple,
    Validator\CharacterValidator
};

/**
 * Class FreeCompany
 *
 * @package Lodestone\Entities\Character
 */
class FreeCompanyMembers extends AbstractEntity
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
     * @return FreeCompanyMembers
     */
    public function setPageCurrent(int $pageCurrent)
    {
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
     * @return FreeCompanyMembers
     */
    public function setPageTotal(int $pageTotal)
    {
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
     * @return FreeCompanyMembers
     */
    public function setTotal(int $total)
    {
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
     * @return FreeCompanyMembers
     */
    public function setCharacters(array $characters)
    {
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
            ->check($character, 'Character')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->characters[] = $character;
        
        return $this;
    }
}