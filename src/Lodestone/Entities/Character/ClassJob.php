<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
};

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
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var int
     */
    protected $expLevel;

    /**
     * @var int
     */
    protected $expLevelTogo;

    /**
     * @var int
     */
    protected $expLevelMax;

    /**
     * @var int
     */
    protected $expTotal;

    /**
     * @var int
     */
    protected $expTotalMax;

    /**
     * @var int
     */
    protected $expTotalTogo;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
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
     * @return $this
     */
    public function setName(string $name)
    {
        BaseValidator::getInstance()
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
     * @return $this
     */
    public function setLevel(int $level)
    {
        BaseValidator::getInstance()
            ->check($level, 'Level')
            ->isInitialized()
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setExpTotalTogo(int $expTotalTogo)
    {
        $this->expTotalTogo = $expTotalTogo;
        return $this;
    }
}