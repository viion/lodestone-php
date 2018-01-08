<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Validator\CharacterValidator
};

/**
 * Class CharacterListTrait
 *
 * @package Lodestone\Entities\Traits
 */
trait CharacterListTrait
{
    /**
     * @var array
     */
    protected $characters = [];
    
    /**
     * @return array
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
    
    /**
     * @param array $characters
     * @return $this
     */
    public function setCharacters(array $characters)
    {
        CharacterValidator::getInstance()
            ->check($characters, 'Characters')
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
            ->check($character, 'Character')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->characters[] = $character;
        
        return $this;
    }
}