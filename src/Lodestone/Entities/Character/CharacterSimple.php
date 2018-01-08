<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\CharacterValidator
};

/**
 * Class CharacterSimple
 *
 * Simple character, used for things such as:
 * - Search
 * - Friends
 * - Followers
 *
 * @package Lodestone\Entities\Character
 */
class CharacterSimple extends AbstractEntity
{
    /**
     * @var int
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
     * @var string
     */
    protected $avatar;
    
    /**
     * @var string
     */
    protected $rank;
    
    /**
     * @var string
     */
    protected $rankicon;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     * @return CharacterSimple
     */
    public function setId(int $id)
    {
        CharacterValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
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
     * @return CharacterSimple
     */
    public function setName(string $name)
    {
        CharacterValidator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->isValidCharacterName()
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
     * @return CharacterSimple
     */
    public function setServer(string $server)
    {
        CharacterValidator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->server = $server;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }
    
    /**
     * @param string $avatar
     * @return CharacterSimple
     */
    public function setAvatar(string $avatar)
    {
        CharacterValidator::getInstance()
            ->check($avatar, 'Avatar URL', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->avatar = $avatar;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }
    
    /**
     * @param string $rank
     * @return CharacterSimple
     */
    public function setRank(string $rank)
    {
        $this->rank = $rank;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getRankicon(): string
    {
        return $this->rankicon;
    }
    
    /**
     * @param string $rankicon
     * @return CharacterSimple
     */
    public function setRankicon(string $rankicon)
    {
        $this->rankicon = $rankicon;
        
        return $this;
    }
}