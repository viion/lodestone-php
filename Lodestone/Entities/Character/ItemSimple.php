<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
};

/**
 * Class ItemSimple
 *
 * @package Lodestone\Entities\Character\Profile
 */
class ItemSimple extends AbstractEntity
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
     * @var int
     * @index Value
     */
    protected $value;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        Validator::getInstance()
            ->check($id, 'Item Lodestone ID')
            ->isString();
    
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
     * @return $this
     */
    public function setName(string $name)
    {
        Validator::getInstance()
            ->check($name, 'Item Name')
            ->isNotEmpty()
            ->isString();

        $this->name = $name;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
    
    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value)
    {
        // can be empty
        Validator::getInstance()
            ->check($value, 'Item value')
            ->isString();
        
        $this->value = $value;
        return $this;
    }
}
