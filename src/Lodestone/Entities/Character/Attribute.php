<?php

namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;

/**
 * Class Attribute
 *
 * @package Lodestone\Entities\Character
 */
class Attribute extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $value;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->validator
            ->check($id, 'Attribute ID')
            ->isInitialized()
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
     */
    public function setName(string $name)
    {
        $this->validator
            ->check($name, 'Attribute Name')
            ->isInitialized()
            ->isString()
            ->validate();

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
     * @param int $value
     */
    public function setValue(int $value)
    {
        $this->validator
            ->check($value, 'Attribute Value')
            ->isInitialized()
            ->isNumeric()
            ->validate();

        $this->value = $value;
        return $this;
    }
}