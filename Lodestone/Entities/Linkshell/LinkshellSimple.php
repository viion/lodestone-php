<?php

namespace Lodestone\Entities\Linkshell;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
};

/**
 * Class LinkshellSimple
 *
 * @package Lodestone\Entities\Linkshell
 */
class LinkshellSimple extends AbstractEntity
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
     * @return LinkshellSimple
     */
    public function setId(string $id)
    {
        Validator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isNotEmpty();
        
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
     * @return LinkshellSimple
     */
    public function setName(string $name)
    {
        Validator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isNotEmpty()
            ->isString();
        
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
     * @return LinkshellSimple
     */
    public function setServer(string $server)
    {
        Validator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isNotEmpty()
            ->isString();
        
        $this->server = $server;
        
        return $this;
    }
}
