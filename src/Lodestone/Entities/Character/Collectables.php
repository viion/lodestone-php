<?php

namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;

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
    public $minions = [];

    /**
     * @var array
     */
    public $mounts = [];

    /**
     * @return array
     */
    public function getMinions(): array
    {
        return $this->minions;
    }

    /**
     * @param array $minions
     */
    public function setMinions(array $minions)
    {
        $this->validator
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
     */
    public function setMounts(array $mounts)
    {
        $this->validator
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