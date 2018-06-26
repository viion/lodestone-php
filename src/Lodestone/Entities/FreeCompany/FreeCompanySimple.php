<?php

namespace Lodestone\Entities\FreeCompany;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\FreeCompanyValidator
};

/**
 * Class FreeCompanySimple
 *
 * @package Lodestone\Entities\FreeCompany
 */
class FreeCompanySimple extends AbstractEntity
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
     * @var array
     * @index Avatar
     */
    protected $avatar;
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     * @return FreeCompanySimple
     */
    public function setId(string $id)
    {
        FreeCompanyValidator::getInstance()
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
     * @return FreeCompanySimple
     */
    public function setName(string $name)
    {
        FreeCompanyValidator::getInstance()
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
     * @return FreeCompanySimple
     */
    public function setServer(string $server)
    {
        FreeCompanyValidator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->server = $server;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getAvatar(): array
    {
        return $this->avatar;
    }
    
    /**
     * @param array $avatar
     * @return FreeCompanySimple
     */
    public function setAvatar(array $avatar)
    {
        FreeCompanyValidator::getInstance()
            ->check($avatar, 'Avatar Array', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isArray()
            ->validate();
        
        $this->avatar = $avatar;
        
        return $this;
    }
}
