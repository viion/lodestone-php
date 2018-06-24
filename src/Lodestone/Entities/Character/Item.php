<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\BaseValidator
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
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slot;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $mirage;

    /**
     * @var int
     */
    protected $creator;

    /**
     * @var string
     */
    protected $dye;

    /**
     * @var array
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
        BaseValidator::getInstance()
            ->check($id, 'Item Lodestone ID')
            ->isInitialized()
            ->isString()
            ->validate();
    
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
            ->check($name, 'Item Name')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

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
        BaseValidator::getInstance()
            ->check($slot, 'Item Slot')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

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
        BaseValidator::getInstance()
            ->check($category, 'Item Category')
            ->isInitialized()
            ->isStringOrEmpty()
            ->validate();

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
        BaseValidator::getInstance()
            ->check($mirage, 'Item mirage')
            ->isInitialized()
            ->isArray()
            ->validate();

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
        BaseValidator::getInstance()
            ->check($creator, 'Item Creator')
            ->isInitialized()
            ->isNumeric()
            ->validate();

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
    public function setDye(array $dye)
    {
        BaseValidator::getInstance()
            ->check($dye, 'Item Dye')
            ->isInitialized()
            ->isArray()
            ->validate();

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
        BaseValidator::getInstance()
            ->check($materia, 'Materia Array (replace)')
            ->isInitialized()
            ->isArray()
            ->validate();

        $this->materia = $materia;
        return $this;
    }

    /**
     * @param array $materia
     * @return $this
     */
    public function addMateria(array $materia)
    {
        BaseValidator::getInstance()
            ->check($materia, 'Materia Array (add)')
            ->isInitialized()
            ->isArray()
            ->validate();

        $this->materia[] = $materia;
        return $this;
    }
}
