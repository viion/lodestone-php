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
     * @index PvPTeams
     */
    protected $pvpTeams = [];
    
    /**
     * @return array
     */
    public function getPvPTeams(): array
    {
        return $this->pvpTeams;
    }
    
    /**
     * @param array $pvpTeams
     * @return $this
     */
    public function setPvPTeams(array $pvpTeams)
    {
        PvPTeamValidator::getInstance()
            ->check($pvpTeams, 'PvPTeams')
            ->isInitialized()
            ->isArray()
            ->validate();
        
        $this->pvpTeams = $pvpTeams;
        
        return $this;
    }
    
    /**
     * @param PvPTeamSimple $character
     * @return $this
     */
    public function addPvPTeam(PvPTeamSimple $pvpTeams)
    {
        PvPTeamValidator::getInstance()
            ->check($pvpTeams, 'PvPTeam')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->pvpTeams[] = $pvpTeams;
        
        return $this;
    }
}
