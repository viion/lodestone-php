<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\Traits\CharacterListTrait,
    Entities\AbstractEntity,
    Entities\Traits\ListTrait,
    Validator\PvPTeamValidator
};

/**
 * Class PvPTeam
 *
 * @package Lodestone\Entities\PvPTeam
 */
class PvPTeam extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
    
    /**
     * @var string
     * @index ID
     */
    protected $id;
    
    /**
     * @var array
     * @index Crest
     */
    protected $crest = [];
    
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
        return $this->server;
    }
    
    /**
     * @param string $server
     * @return PvPTeam
     */
    public function setServer(string $server)
    {
        $this->server = $server;
        
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
