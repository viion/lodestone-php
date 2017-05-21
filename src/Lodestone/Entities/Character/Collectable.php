<?php
namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;

/**
 * Class Collectable
 * @package Lodestone\Entities\Character
 */
class Collectable extends AbstractEntity {

    /**
     * @var int id
     */
    private $id;

    /**
     * @var string name
     */
    private $name;

    /**
     * @var string icon
     */
    private $icon;

    /**
     * @param int
     * @return $this
     */
    public function setId(int $id) {
        $this->validator
            ->check($id, 'ID')
            ->isInitialized()
            ->isInteger()
            ->validate();

        $this->id = $id;

        return $this;
    }

    /**
     * @param string
     * @return $this
     */
    public function setName(string $name) {
        $this->validator
            ->check($name, 'Name')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->name = $name;

        return $this;
    }

    /**
     * @param string
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
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIcon(): string {
        return $this->icon;
    }


}