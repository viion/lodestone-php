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
     * @var array
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