<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\PvPTeamValidator
};

/**
 * Class PvPTeamSimple
 *
 * @package Lodestone\Entities\PvPTeam
 */
class PvPTeamSimple extends AbstractEntity
{
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $datacenter;
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     * @return PvPTeamSimple
     */
    public function setId(string $id)
    {
        PvPTeamValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->validate();
        
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return PvPTeamSimple
     */
    public function setName(string $name)
    {
        PvPTeamValidator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->datacenter;
    }
    
    /**
     * @param string $server
     * @return PvPTeamSimple
     */
    public function setServer(string $server)
    {
        PvPTeamValidator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->datacenter = $server;
        
        return $this;
    }
}