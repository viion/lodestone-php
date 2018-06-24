<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\Character,
    Entities\Character\CharacterSimple,
    Entities\AbstractEntity,
    Validator\PvPTeamValidator
};

/**
 * Class PvPTeam
 *
 * @package Lodestone\Entities\PvPTeam
 */
class PvPTeamMembers extends CharacterSimple
{
    /**
     * @var string
     */
    protected $feasts = 0;
    
    /**
     * @return null|string
     */
    public function getFeasts()
    {
        return $this->feasts;
    }

    /**
     * @param string $feasts
     * @return $this
     */
    public function setFeasts($feasts)
    {
        PvPTeamValidator::getInstance()
            ->check($feasts, 'Feast Games', $this->id)
            ->isInitialized()
            ->isString()
            ->validate();
        
        if (empty($feasts)) {$feasts = '0';}
        
        $this->feasts = $feasts;

        return $this;
    }
}