<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\Linkshell\LinkshellSimple,
    Modules\Validator
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
     * @index Linkshells
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
        Validator::getInstance()
            ->check($linkshells, 'Linkshells')
            ->isArray();
        
        $this->linkshells = $linkshells;
        
        return $this;
    }
    
    /**
     * @param LinkshellSimple $character
     * @return $this
     */
    public function addLinkshell(LinkshellSimple $character)
    {
        Validator::getInstance()
            ->check($character, 'Linkshell')
            ->isObject();
        
        $this->linkshells[] = $character;
        
        return $this;
    }
}
