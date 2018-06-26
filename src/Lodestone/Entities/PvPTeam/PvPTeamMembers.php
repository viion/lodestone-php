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
     * @var int
     * @index ID
     */
    protected $id;
    
    /**
     * @var string
     * @index Name
     */
    protected $name;
    
    /**
     * @var string
     * @index Server
     */
    protected $server;
    
    /**
     * @var string
     * @index Avatar
     */
    protected $avatar;
    
    /**
     * @var string
     * @index Rank
     */
    protected $rank;
    
    /**
     * @var string
     * @index RankIcon
     */
    protected $rankicon;
    
    /**
     * @var string
     * @index Feasts
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
