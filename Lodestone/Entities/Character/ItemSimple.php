<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
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
        BaseValidator::getInstance()
            ->check($id, 'Item Lodestone ID')
            ->isInitialized()
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
        BaseValidator::getInstance()
            ->check($name, 'Item Name')
            ->isInitialized()
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
        BaseValidator::getInstance()
            ->check($value, 'Item value')
            ->isInitialized()
            ->isString();
        
        $this->value = $value;
        return $this;
    }
}
