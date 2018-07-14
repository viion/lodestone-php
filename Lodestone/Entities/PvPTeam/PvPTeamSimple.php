<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\CharacterValidator
};

/**
 * Class PvPTeamSimple
 *
 * @package Lodestone\Entities\PvPTeam
 */
class PvPTeamSimple extends AbstractEntity
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
     * @index DataCenter
     */
    protected $dataCenter;
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     * @return PvPTeamSimple
     */
    public function setId(string $id)
    {
        CharacterValidator::getInstance()
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
     * @return PvPTeamSimple
     */
    public function setName(string $name)
    {
        CharacterValidator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isNotEmpty()
            ->isString();
        
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
     * @return PvPTeamSimple
     */
    public function setDataCenter(string $dataCenter)
    {
        CharacterValidator::getInstance()
            ->check($dataCenter, 'Data Center', $this->id)
            ->isNotEmpty()
            ->isString();
        
        $this->dataCenter = $dataCenter;
        
        return $this;
    }
}
