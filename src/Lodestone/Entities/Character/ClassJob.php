<?php

namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;

/**
 * Class ClassJob
 *
 * @package Lodestone\Entities\Character
 */
class ClassJob extends AbstractEntity
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
    public $level;

    /**
     * @var int
     */
    public $expLevel;

    /**
     * @var int
     */
    public $expLevelTogo;

    /**
     * @var int
     */
    public $expLevelMax;

    /**
     * @var int
     */
    public $expTotal;

    /**
     * @var int
     */
    public $expTotalMax;

    /**
     * @var int
     */
    public $expTotalTogo;

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
            ->check($id, 'ID')
            ->isInitialized()
            ->isInteger();

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
            ->check($name, 'Name')
            ->isInitialized()
            ->isNotEmpty()
            ->isString();

        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level)
    {
        $this->validator
            ->check($level, 'Level')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();

        $this->level = $level;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpLevel(): int
    {
        return $this->expLevel;
    }

    /**
     * @param int $expLevel
     */
    public function setExpLevel(int $expLevel)
    {
        $this->expLevel = $expLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpLevelTogo(): int
    {
        return $this->expLevelTogo;
    }

    /**
     * @param int $expLevelTogo
     */
    public function setExpLevelTogo(int $expLevelTogo)
    {
        $this->expLevelTogo = $expLevelTogo;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpLevelMax(): int
    {
        return $this->expLevelMax;
    }

    /**
     * @param int $expLevelMax
     */
    public function setExpLevelMax(int $expLevelMax)
    {
        $this->expLevelMax = $expLevelMax;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpTotal(): int
    {
        return $this->expTotal;
    }

    /**
     * @param int $expTotal
     */
    public function setExpTotal(int $expTotal)
    {
        $this->expTotal = $expTotal;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpTotalMax(): int
    {
        return $this->expTotalMax;
    }

    /**
     * @param int $expTotalMax
     */
    public function setExpTotalMax(int $expTotalMax)
    {
        $this->expTotalMax = $expTotalMax;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpTotalTogo(): int
    {
        return $this->expTotalTogo;
    }

    /**
     * @param int $expTotalTogo
     */
    public function setExpTotalTogo(int $expTotalTogo)
    {
        $this->expTotalTogo = $expTotalTogo;
        return $this;
    }
}