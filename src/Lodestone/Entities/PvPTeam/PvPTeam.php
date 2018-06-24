<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\Traits\CharacterListTrait,
    Entities\AbstractEntity,
    //Entities\Traits\ListTrait,
    Validator\PvPTeamValidator
};

/**
 * Class PvPTeam
 *
 * @package Lodestone\Entities\PvPTeam
 */
class PvPTeam extends AbstractEntity
{
    //use ListTrait;
    use CharacterListTrait;
    
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var array
     */
    protected $crest = [];
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $datacenter;
    
    
    
    /**
     * PvPTeam constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        PvPTeamValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->id = $id;
    }
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     * @return PvPTeam
     */
    public function setId(string $id)
    {
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
     * @return PvPTeam
     */
    public function setName(string $name)
    {
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
     * @return PvPTeam
     */
    public function setServer(string $server)
    {
        $this->datacenter = $server;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getCrest()
    {
        return $this->crest;
    }
    
    /**
     * @param mixed $crest
     * @return PvPTeam
     */
    public function setCrest($crest)
    {
        $this->crest = $crest;
        
        return $this;
    }
}