<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
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
     * @var string
     * @index Feasts
     */
    public $feasts = 0;
    
    /**
     * @return null|string
     */
    
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
        Validator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isNotEmpty()
            ->isNumeric();
    
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
        Validator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isNotEmpty()
            ->isString()
            ->isValidCharacterName();
        
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
        Validator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isNotEmpty()
            ->isString();
        
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
        Validator::getInstance()
            ->check($avatar, 'Avatar URL', $this->id)
            ->isNotEmpty()
            ->isString();
        
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
    
    /**
     * @return null|string
     */
    public function getFeasts()
    {
        return $this->feasts;
    }

    /**
     * @param string $feasts
     * @return $this
     */
    public function setFeasts($feasts)
    {
        Validator::getInstance()
            ->check($feasts, 'Feast Games', $this->id)
            ->isString();
        
        if (empty($feasts)) {$feasts = '0';}
        
        $this->feasts = $feasts;

        return $this;
    }
}
