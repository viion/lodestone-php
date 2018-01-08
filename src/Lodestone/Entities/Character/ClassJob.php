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
    protected $classId;
    
    /**
     * @var int
     */
    protected $jobId;
    
    /**
     * @var string
     */
    protected $className;
    
    /**
     * @var string
     */
    protected $jobName;

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
     * @return int
     */
    public function getClassId(): int
    {
        return $this->classId;
    }
    
    /**
     * @param int $classId
     * @return $this
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobId(): int
    {
        return $this->jobId;
    }

    /**
     * @param int $jobId
     * @return $this
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName(string $className)
    {
        BaseValidator::getInstance()
            ->check($className, 'ClassName')
            ->isInitialized()
            ->isNotEmpty()
            ->isString();

        $this->className = $className;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getJobName(): string
    {
        return $this->jobName;
    }
    
    /**
     * @param string $jobName
     * @return $this
     */
    public function setJobName(string $jobName)
    {
        BaseValidator::getInstance()
            ->check($jobName, 'JobName')
            ->isInitialized()
            ->isNotEmpty()
            ->isString();
        
        $this->jobName = $jobName;
        return $this;
    }
    
    /**
     * Legacy name containing both class+job name
     *
     * @return string
     */
    public function getName()
    {
        return sprintf('%s/%s', $this->getClassName(), $this->getJobName());
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
}