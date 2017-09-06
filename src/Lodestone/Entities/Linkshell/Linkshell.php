<?php

namespace Lodestone\Entities\Linkshell;

use Lodestone\{
    Entities\Traits\CharacterListTrait,
    Entities\AbstractEntity,
    Entities\Traits\ListTrait,
    Validator\LinkshellValidator
};

/**
 * Class Linkshell
 *
 * @package Lodestone\Entities\Linkshell
 */
class Linkshell extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
    
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
    protected $server;
    
    /**
     * Linkshell constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        LinkshellValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
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
     * @return Linkshell
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
     * @return Linkshell
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
     * @return Linkshell
     */
    public function setServer(string $server)
    {
        $this->server = $server;
        
        return $this;
    }
}