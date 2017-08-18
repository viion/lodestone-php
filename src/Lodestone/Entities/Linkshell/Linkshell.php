<?php

namespace Lodestone\Entities\Linkshell;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Character\CharacterSimple,
    Validator\CharacterValidator,
    Validator\LinkshellValidator
};

/**
 * Class Linkshell
 *
 * @package Lodestone\Entities\Linkshell
 */
class Linkshell extends AbstractEntity
{
    /** @var string */
    protected $id;
    
    /** @var string */
    protected $name;
    
    /** @var string */
    protected $server;
    
    /** @var int */
    protected $pageCurrent;
    
    /** @var int */
    protected $pageTotal;
    
    /** @var int */
    protected $total;
    
    /** @var array */
    protected $characters = [];
    
    /**
     * Linkshell constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        LinkshellValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
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
     * @return Linkshell
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
     * @return Linkshell
     */
    public function setName(string $name)
    {
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
     * @return Linkshell
     */
    public function setServer(string $server)
    {
        $this->server = $server;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPageCurrent(): int
    {
        return $this->pageCurrent;
    }
    
    /**
     * @param int $pageCurrent
     * @return Linkshell
     */
    public function setPageCurrent(int $pageCurrent)
    {
        $this->pageCurrent = $pageCurrent;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPageTotal(): int
    {
        return $this->pageTotal;
    }
    
    /**
     * @param int $pageTotal
     * @return Linkshell
     */
    public function setPageTotal(int $pageTotal)
    {
        $this->pageTotal = $pageTotal;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
    
    /**
     * @param int $total
     * @return Linkshell
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
    
    /**
     * @param array $characters
     * @return Linkshell
     */
    public function setCharacters(array $characters)
    {
        $this->characters = $characters;
        
        return $this;
    }
    
    /**
     * @param CharacterSimple $character
     * @return $this
     */
    public function addCharacter(CharacterSimple $character)
    {
        CharacterValidator::getInstance()
            ->check($character, 'Character')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->characters[] = $character;
        
        return $this;
    }
}