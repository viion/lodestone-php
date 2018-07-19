<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Modules\Validator
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
     * @index Characters
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
        Validator::getInstance()
            ->check($characters, 'Characters')
            ->isArray();
        
        $this->characters = $characters;
        
        return $this;
    }
    
    /**
     * @param CharacterSimple $character
     * @return $this
     */
    public function addCharacter(CharacterSimple $character)
    {
        Validator::getInstance()
            ->check($character, 'Character')
            ->isObject();
        
        $this->characters[] = $character;
        
        return $this;
    }
}
