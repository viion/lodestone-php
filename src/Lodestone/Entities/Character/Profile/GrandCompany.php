<?php
namespace Lodestone\Entities\Character\Profile;

use Lodestone\Entities\AbstractEntity;

/**
 * Class GrandCompany
 * @package Lodestone\Entities\Character\Profile
 */
class GrandCompany extends AbstractEntity {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $rank;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id) {
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
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name) {
        $this->validator
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
    public function getIcon(): string {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon) {
        $this->validator
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
    public function getRank(): string {
        return $this->rank;
    }

    /**
     * @param string $rank
     * @return $this
     */
    public function setRank(string $rank) {
        $this->validator
            ->check($rank, 'Rank')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->rank = $rank;

        return $this;
    }



}