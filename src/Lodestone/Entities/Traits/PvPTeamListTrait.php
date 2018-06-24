<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\PvPTeam\PvPTeamSimple,
    Validator\PvPTeamValidator
};

/**
 * Class PvPTeamListTrait
 *
 * @package Lodestone\Entities\Traits
 */
trait PvPTeamListTrait
{
    /**
     * @var array
     */
    protected $PvPTeams = [];
    
    /**
     * @return array
     */
    public function getPvPTeams(): array
    {
        return $this->PvPTeams;
    }
    
    /**
     * @param array $PvPTeams
     * @return $this
     */
    public function setPvPTeams(array $PvPTeams)
    {
        PvPTeamValidator::getInstance()
            ->check($PvPTeams, 'PvPTeams')
            ->isInitialized()
            ->isArray()
            ->validate();
        
        $this->PvPTeams = $PvPTeams;
        
        return $this;
    }
    
    /**
     * @param PvPTeamSimple $character
     * @return $this
     */
    public function addPvPTeam(PvPTeamSimple $character)
    {
        PvPTeamValidator::getInstance()
            ->check($character, 'PvPTeam')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->PvPTeams[] = $character;
        
        return $this;
    }
}