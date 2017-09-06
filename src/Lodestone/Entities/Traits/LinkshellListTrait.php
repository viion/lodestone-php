<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\Linkshell\LinkshellSimple,
    Validator\LinkshellValidator
};

/**
 * Class LinkshellListTrait
 *
 * @package Lodestone\Entities\Traits
 */
trait LinkshellListTrait
{
    /**
     * @var array
     */
    protected $linkshells = [];
    
    /**
     * @return array
     */
    public function getLinkshells(): array
    {
        return $this->linkshells;
    }
    
    /**
     * @param array $linkshells
     * @return $this
     */
    public function setLinkshells(array $linkshells)
    {
        LinkshellValidator::getInstance()
            ->check($linkshells, 'Linkshells')
            ->isInitialized()
            ->isArray()
            ->validate();
        
        $this->linkshells = $linkshells;
        
        return $this;
    }
    
    /**
     * @param LinkshellSimple $character
     * @return $this
     */
    public function addLinkshell(LinkshellSimple $character)
    {
        LinkshellValidator::getInstance()
            ->check($character, 'Linkshell')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->linkshells[] = $character;
        
        return $this;
    }
}