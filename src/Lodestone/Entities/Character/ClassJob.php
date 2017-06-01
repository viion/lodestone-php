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
    public $currentExp;

    /**
     * @var int
     */
    public $maxExp;

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
    public function getCurrentExp(): int
    {
        return $this->currentExp;
    }

    /**
     * @param int $currentExp
     */
    public function setCurrentExp(int $currentExp)
    {
        $this->validator
            ->check($currentExp, 'Current EXP')
            ->isInitialized()
            ->isNumeric()
            ->validate();

        $this->currentExp = $currentExp;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxExp(): int
    {
        return $this->maxExp;
    }

    /**
     * @param int $maxExp
     */
    public function setMaxExp(int $maxExp)
    {
        $this->validator
            ->check($maxExp, 'Max EXP')
            ->isInitialized()
            ->isNumeric()
            ->validate();

        $this->maxExp = $maxExp;
        return $this;
    }
}