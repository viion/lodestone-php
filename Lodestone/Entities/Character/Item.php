<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
};

/**
 * Class Item
 *
 * @package Lodestone\Entities\Character\Profile
 */
class Item extends AbstractEntity
{
    /**
     * @var string
     * @index ID
     */
    protected $id;

    /**
     * @var string
     * @index Name
     */
    protected $name;

    /**
     * @var string
     * @index Slot
     */
    protected $slot;

    /**
     * @var string
     * @index Category
     */
    protected $category;

    /**
     * @var ItemSimple
     * @index Mirage
     */
    protected $mirage;

    /**
     * @var int
     * @index Creator
     */
    protected $creator;

    /**
     * @var ItemSimple
     * @index Dye
     */
    protected $dye;

    /**
     * @var array
     * @index Materia
     */
    protected $materia = [];

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
        Validator::getInstance()
            ->check($id, 'Item Lodestone ID')
            ->isString();
    
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
        Validator::getInstance()
            ->check($name, 'Item Name')
            ->isNotEmpty()
            ->isString();

        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlot(): string
    {
        return $this->slot;
    }

    /**
     * @param string $slot
     * @return $this
     */
    public function setSlot(string $slot)
    {
        Validator::getInstance()
            ->check($slot, 'Item Slot')
            ->isNotEmpty()
            ->isString();

        $this->slot = $slot;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory(string $category)
    {
        Validator::getInstance()
            ->check($category, 'Item Category')
            ->isStringOrEmpty();

        $this->category = $category;
        return $this;
    }

    /**
     * @return int
     */
    public function getMirage(): int
    {
        return $this->mirage;
    }

    /**
     * @param string $mirage
     * @return $this
     */
    public function setMirage(array $mirage)
    {
        // can be empty
        Validator::getInstance()
            ->check($mirage, 'Item mirage')
            ->isObject();

        $this->mirage = $mirage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreator(): int
    {
        return $this->creator;
    }

    /**
     * @param int $creator
     * @return $this
     */
    public function setCreator(int $creator)
    {
        // can be empty
        Validator::getInstance()
            ->check($creator, 'Item Creator')
            ->isNumeric();

        $this->creator = $creator;
        return $this;
    }

    /**
     * @return string
     */
    public function getDye(): string
    {
        return $this->dye;
    }

    /**
     * @param string $dye
     * @return $this
     */
    public function setDye(ItemSimple $dye)
    {
        Validator::getInstance()
            ->check($dye, 'Item Dye')
            ->isObject();

        $this->dye = $dye;
        return $this;
    }

    /**
     * @return array
     */
    public function getMateria(): array
    {
        return $this->materia;
    }

    /**
     * @param array $materia
     * @return $this
     */
    public function setMateria(array $materia)
    {
        Validator::getInstance()
            ->check($materia, 'Materia Array (replace)')
            ->isArray();

        $this->materia = $materia;
        return $this;
    }

    /**
     * @param array $materia
     * @return $this
     */
    public function addMateria(ItemSimple $materia)
    {
        Validator::getInstance()
            ->check($materia, 'Materia Array (add)')
            ->isObject();

        $this->materia[] = $materia;
        return $this;
    }
}
