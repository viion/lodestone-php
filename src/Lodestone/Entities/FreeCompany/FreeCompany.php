<?php

namespace Lodestone\Entities\FreeCompany;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\FreeCompanyValidator
};

/**
 * Class FreeCompany
 *
 * @package Lodestone\Entities\Character
 */
class FreeCompany extends AbstractEntity
{
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
     * @index GrandCompany
     */
    protected $grandCompany;
    
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
     * @index Tag
     */
    protected $tag;
    
    /**
     * @var string
     * @index Formed
     */
    protected $formed;
    
    /**
     * @var int
     * @index ActiveMemberCount
     */
    protected $activeMemberCount;
    
    /**
     * @var string
     * @index Rank
     */
    protected $rank;
    
    /**
     * @var object
     * @index Ranking
     */
    protected $ranking;
    
    /**
     * @var string
     * @index Slogan
     */
    protected $slogan;
    
    /**
     * @var object
     * @index Estate
     */
    protected $estate;
    
    /**
     * @var object
     * @index Reputation
     */
    protected $reputation = [];
    
    /**
     * @var string
     * @index Active
     */
    protected $active;
    
    /**
     * @var string
     * @index Recruitment
     */
    protected $recruitment;
    
    /**
     * @var object
     * @index Focus
     */
    protected $focus = [];
    
    /**
     * @var object
     * @index Seeking
     */
    protected $seeking = [];
    
    /**
     * FreeCompany constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        FreeCompanyValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
    
        $this->id = $id;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param mixed $id
     * @return FreeCompany
     */
    public function setId($id)
    {
        $this->id = $id;
        
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
     * @return FreeCompany
     */
    public function setCrest($crest)
    {
        $this->crest = $crest;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getGrandCompany()
    {
        return $this->grandCompany;
    }
    
    /**
     * @param mixed $grandCompany
     * @return FreeCompany
     */
    public function setGrandCompany($grandCompany)
    {
        $this->grandCompany = $grandCompany;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param mixed $name
     * @return FreeCompany
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }
    
    /**
     * @param mixed $server
     * @return FreeCompany
     */
    public function setServer($server)
    {
        $this->server = $server;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }
    
    /**
     * @param mixed $tag
     * @return FreeCompany
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getFormed()
    {
        return $this->formed;
    }
    
    /**
     * @param mixed $formed
     * @return FreeCompany
     */
    public function setFormed($formed)
    {
        $this->formed = $formed;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getActiveMemberCount()
    {
        return $this->activeMemberCount;
    }
    
    /**
     * @param mixed $activeMemberCount
     * @return FreeCompany
     */
    public function setActiveMemberCount($activeMemberCount)
    {
        $this->activeMemberCount = $activeMemberCount;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }
    
    /**
     * @param mixed $rank
     * @return FreeCompany
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getRanking()
    {
        return $this->ranking;
    }
    
    /**
     * @param mixed $ranking
     * @return FreeCompany
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getSlogan()
    {
        return $this->slogan;
    }
    
    /**
     * @param mixed $slogan
     * @return FreeCompany
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getEstate()
    {
        return $this->estate;
    }
    
    /**
     * @param mixed $estate
     * @return FreeCompany
     */
    public function setEstate($estate)
    {
        $this->estate = $estate;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getReputation(): array
    {
        return $this->reputation;
    }
    
    /**
     * @param array $reputation
     * @return FreeCompany
     */
    public function setReputation(array $reputation)
    {
        $this->reputation = $reputation;
        
        return $this;
    }
    
    /**
     * @param $object
     */
    public function addReputation($object)
    {
        $this->reputation[] = $object;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * @param mixed $active
     * @return FreeCompany
     */
    public function setActive($active)
    {
        $this->active = $active;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getRecruitment()
    {
        return $this->recruitment;
    }
    
    /**
     * @param mixed $recruitment
     * @return FreeCompany
     */
    public function setRecruitment($recruitment)
    {
        $this->recruitment = $recruitment;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getFocus(): array
    {
        return $this->focus;
    }
    
    /**
     * @param array $focus
     * @return FreeCompany
     */
    public function setFocus(array $focus)
    {
        $this->focus = $focus;
        
        return $this;
    }
    
    /**
     * @param $object
     * @return $this
     */
    public function addFocus($object)
    {
        $this->focus[] = $object;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getSeeking(): array
    {
        return $this->seeking;
    }
    
    /**
     * @param array $seeking
     * @return FreeCompany
     */
    public function setSeeking(array $seeking)
    {
        $this->seeking = $seeking;
        
        return $this;
    }
    
    /**
     * @param $object
     * @return $this
     */
    public function addSeeking($object)
    {
        $this->seeking[] = $object;
        
        return $this;
    }
}
