<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
};

/**
 * Class Attribute
 *
 * @package Lodestone\Entities\Character
 */
class Attribute extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $value;

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
     * @return $this
     */
    public function setValue(int $value)
    {
        BaseValidator::getInstance()
            ->check($value, 'Attribute Value')
            ->isInitialized()
            ->isNumeric()
            ->validate();

        $this->value = $value;
        return $this;
    }
}