<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
};

/**
 * Class ClassJob
 *
 * @package Lodestone\Entities\Character
 */
class ClassJob extends AbstractEntity
{/**
     * @var int
     * @index Level
     */
    protected $level;

    /**
     * @var int
     * @index ExpLevel
     */
    protected $expLevel;

    /**
     * @var int
     * @index ExpLevelTogo
     */
    protected $expLevelTogo;

    /**
     * @var int
     * @index ExpLevelMax
     */
    protected $expLevelMax;
    
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
        Validator::getInstance()
            ->check($level, 'Level')
            ->isNumeric();

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
}
