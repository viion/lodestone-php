<?php

namespace Lodestone\Entities\FreeCompany;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
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
     * @return FreeCompanySimple
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
     * @return FreeCompanySimple
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
        Validator::getInstance()
            ->check($avatar, 'Avatar Array', $this->id)
            ->isNotEmpty()
            ->isArray();
        
        $this->avatar = $avatar;
        
        return $this;
    }
}
