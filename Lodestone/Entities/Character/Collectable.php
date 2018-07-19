<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
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
     * @index Name
     */
    protected $name;
    
    /**
     * @var string
     * @index Icon
     */
    protected $icon;

    /**
     * @param string
     * @return $this
     */
    public function setName(string $name)
    {
        Validator::getInstance()
            ->check($name, 'Name')
            ->isNotEmpty()
            ->isString();

        $this->name = $name;

        return $this;
    }
    
    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon)
    {
        Validator::getInstance()
            ->check($icon, 'Icon URL')
            ->isNotEmpty()
            ->isString();

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
