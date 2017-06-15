<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
};

/**
 * Class Collectables
 *
 * @package Lodestone\Entities\Character
 */
class Collectables extends AbstractEntity
{
    /**
     * @var array
     */
    protected $minions = [];

    /**
     * @var array
     */
    protected $mounts = [];

    /**
     * @return array
     */
    public function getMinions(): array
    {
        return $this->minions;
    }

    /**
     * @param array $minions
     * @return $this
     */
    public function setMinions(array $minions)
    {
        BaseValidator::getInstance()
            ->check($minions, 'Minions array')
            ->isInitialized()
            ->isArray()
            ->validate();

        $this->minions = $minions;
        return $this;
    }

    /**
     * @param Collectable $collectable
     * @return $this
     */
    public function addMinion(Collectable $collectable)
    {
        $this->minions[] = $collectable;
        return $this;
    }

    /**
     * @return array
     */
    public function getMounts(): array
    {
        return $this->mounts;
    }

    /**
     * @param array $mounts
     * @return $this
     */
    public function setMounts(array $mounts)
    {
        BaseValidator::getInstance()
            ->check($mounts, 'Mounts array')
            ->isInitialized()
            ->isArray()
            ->validate();

        $this->mounts = $mounts;
        return $this;
    }

    /**
     * @param Collectable $collectable
     * @return $this
     */
    public function addMount(Collectable $collectable)
    {
        $this->mounts[] = $collectable;
        return $this;
    }
}