<?php

namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;

/**
 * Class Collectable
 *
 * @package Lodestone\Entities\Character
 */
class Collectable extends AbstractEntity
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
     * @param int
     * @return $this
     */
    public function setId($id)
    {
        return $this;
    }

    /**
     * @param string
     * @return $this
     */
    public function setName(string $name)
    {
        $this->validator
            ->check($name, 'Name')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}