<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
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
     * @index Name
     */
    protected $name;

    /**
     * @var int
     * @index Value
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
        Validator::getInstance()
            ->check($name, 'Attribute Name')
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
     * @param int $value
     * @return $this
     */
    public function setValue(int $value)
    {
        Validator::getInstance()
            ->check($value, 'Attribute Value')
            ->isNumeric();

        $this->value = $value;
        return $this;
    }
}
