<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\PvPTeam\PvPTeamSimple,
    Modules\Validator
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
        Validator::getInstance()
            ->check($pvpTeams, 'PvPTeams')
            ->isArray();
        
        $this->pvpTeams = $pvpTeams;
        
        return $this;
    }
    
    /**
     * @param PvPTeamSimple $character
     * @return $this
     */
    public function addPvPTeam(PvPTeamSimple $pvpTeams)
    {
        Validator::getInstance()
            ->check($pvpTeams, 'PvPTeam')
            ->isObject();
        
        $this->pvpTeams[] = $pvpTeams;
        
        return $this;
    }
}
