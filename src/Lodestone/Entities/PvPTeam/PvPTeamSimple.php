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
        return $this->server;
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
        
        $this->server = $server;
        
        return $this;
    }
}
