<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
};

/**
 * Class GrandCompany
 *
 * @package Lodestone\Entities\Character
 */
class GrandCompany extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $rank;
    
    /**
     * @var string
     */
    protected $icon;

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
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon)
    {
        BaseValidator::getInstance()
            ->check($icon, 'Icon URL')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * @param string $rank
     * @return $this
     */
    public function setRank(string $rank)
    {
        BaseValidator::getInstance()
            ->check($rank, 'Rank')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->rank = $rank;

        return $this;
    }
}
