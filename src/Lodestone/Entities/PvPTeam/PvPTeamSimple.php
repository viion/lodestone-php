<?php

namespace Lodestone\Entities\PvPTeam;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\PvPTeamValidator
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
        PvPTeamValidator::getInstance()
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
     * @return PvPTeamSimple
     */
    public function setName(string $name)
    {
        PvPTeamValidator::getInstance()
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
        PvPTeamValidator::getInstance()
            ->check($dataCenter, 'Data Center', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();
        
        $this->dataCenter = $dataCenter;
        
        return $this;
    }
}
