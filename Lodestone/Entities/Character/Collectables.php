<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
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
     * @index Minions
     */
    protected $minions = [];

    /**
     * @var array
     * @index Mounts
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
        Validator::getInstance()
            ->check($minions, 'Minions array')
            ->isArray();

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
        Validator::getInstance()
            ->check($mounts, 'Mounts array')
            ->isArray();

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
