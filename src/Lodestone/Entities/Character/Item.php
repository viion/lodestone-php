<?php

namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;

/**
 * Class Item
 *
 * @package Lodestone\Entities\Character\Profile
 */
class Item extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $lodestoneId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slot;

    /**
     * @var string
     */
    public $category;

    /**
     * @var string
     */
    public $mirageId;

    /**
     * @var int
     */
    public $creatorId;

    /**
     * @var string
     */
    public $dyeId;

    /**
     * @var array
     */
    public $materia = [];

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
            ->check($id, 'Item ID')
            ->isInitialized()
            ->isNumeric()
            ->validate();

        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLodestoneId(): string
    {
        return $this->lodestoneId;
    }

    /**
     * @param string $lodestoneId
     */
    public function setLodestoneId(string $lodestoneId)
    {
        $this->validator
            ->check($lodestoneId, 'Item Lodestone ID')
            ->isInitialized()
            ->isString()
            ->validate();

        $this->lodestoneId = $lodestoneId;
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
     */
    public function setSlot(string $slot)
    {
        $this->validator
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
     */
    public function setCategory(string $category)
    {
        $this->validator
            ->check($category, 'Item Category')
            ->isInitialized()
            ->isStringOrEmpty()
            ->validate();

        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getMirageId(): int
    {
        return $this->mirageId;
    }

    /**
     * @param string $mirageId
     */
    public function setMirageId(string $mirageId)
    {
        // can be empty
        $this->validator
            ->check($mirageId, 'Item mirage')
            ->isInitialized()
            ->isStringOrEmpty()
            ->validate();

        $this->mirageId = $mirageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    /**
     * @param int $creatorId
     */
    public function setCreatorId(int $creatorId)
    {
        // can be empty
        $this->validator
            ->check($creatorId, 'Item Creator')
            ->isInitialized()
            ->isNumeric()
            ->validate();

        $this->creatorId = $creatorId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDyeId(): string
    {
        return $this->dyeId;
    }

    /**
     * @param string $dyeId
     */
    public function setDyeId(string $dyeId)
    {
        $this->validator
            ->check($dyeId, 'Item Dye')
            ->isInitialized()
            ->isStringOrEmpty()
            ->validate();

        $this->dyeId = $dyeId;
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
     */
    public function setMateria(array $materia)
    {
        $this->validator
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
        $this->validator
            ->check($materia, 'Materia Array (add)')
            ->isInitialized()
            ->isArray()
            ->validate();

        $this->materia[] = $materia;
        return $this;
    }
}