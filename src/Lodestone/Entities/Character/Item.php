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
    protected $mirageId;

    /**
     * @var int
     */
    protected $creatorId;

    /**
     * @var string
     */
    protected $dyeId;

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
    public function getMirageId(): int
    {
        return $this->mirageId;
    }

    /**
     * @param string $mirageId
     * @return $this
     */
    public function setMirageId(string $mirageId)
    {
        // can be empty
        BaseValidator::getInstance()
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
     * @return $this
     */
    public function setCreatorId(int $creatorId)
    {
        // can be empty
        BaseValidator::getInstance()
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
     * @return $this
     */
    public function setDyeId(string $dyeId)
    {
        BaseValidator::getInstance()
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