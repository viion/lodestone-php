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
     * @var string
     * @index Avatar
     */
    protected $avatar;
    
    /**
     * @var string
     * @index Rank
     */
    protected $rank;
    
    /**
     * @var string
     * @index RankIcon
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
