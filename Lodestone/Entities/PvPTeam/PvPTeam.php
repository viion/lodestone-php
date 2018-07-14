<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\Traits\CharacterListTrait,
    Entities\AbstractEntity,
    Entities\Traits\ListTrait,
    Validator\CharacterValidator
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
     * @index DataCenter
     */
    protected $dataCenter;
    
    
    
    /**
     * PvPTeam constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        CharacterValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString();
        
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
    public function getDataCenter(): string
    {
        return $this->dataCenter;
    }
    
    /**
     * @param string $dataCenter
     * @return PvPTeam
     */
    public function setDataCenter(string $dataCenter)
    {
        $this->dataCenter = $dataCenter;
        
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
