<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
};

/**
 * Class Collectable
 *
 * @package Lodestone\Entities\Character
 */
class Collectable extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $icon;

    /**
     * @param string
     * @return $this
     */
    public function setName(string $name)
    {
        BaseValidator::getInstance()
            ->check($name, 'Name')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->name = $name;

        return $this;
    }
    
    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon)
    {
        BaseValidator::getInstance()
            ->check($icon, 'Icon URL')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->icon = $icon;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
