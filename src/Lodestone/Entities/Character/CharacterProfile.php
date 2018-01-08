<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Validator\CharacterValidator
};

/**
 * Class Profile
 *
 * @package Lodestone\Entities\Character
 */
class CharacterProfile extends AbstractEntity
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
     * @var string
     */
    protected $server;

    /**
     * @var string
     */
    protected $title = null;

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var string
     */
    protected $portrait;

    /**
     * @var string
     */
    protected $biography = '';

    /**
     * @var string
     */
    protected $race;

    /**
     * @var string
     */
    protected $clan;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var string
     */
    protected $nameday;

    /**
     * @var Guardian
     */
    protected $guardian;

    /**
     * @var City
     */
    protected $city;

    /**
     * @var GrandCompany|null
     */
    protected $grandcompany = null;

    /**
     * @var string|null
     */
    protected $freecompany = null;

    /**
     * @var array
     */
    protected $classjobs = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var Collectables
     */
    protected $collectables = null;

    /**
     * @var array
     */
    protected $gear = [];

    /**
     * @var ClassJob
     */
    protected $activeClassJob = null;

    /**
     * Profile constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        CharacterValidator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();

        $this->id = $id;

        // profile classes
        $this->collectables = new Collectables();
    }

    public function getHash()
    {
        $data = $this->toArray();

        // remove hash, obvs (its blank anyway)
        unset($data['hash']);

        // remove images, urls can change
        unset($data['avatar']);
        unset($data['portrait']);
        unset($data['guardian']['icon']);
        unset($data['city']['icon']);
        unset($data['grandcompany']['icon']);

        // remove free company id, being kicked
        // should not generate a new hash
        unset($data['freecompany']);

        // remove biography as this is too "open"
        // and could become malformed easily.
        unset($data['biography']);

        // remove stats, SE can change the formula
        unset($data['stats']);

        return sha1(serialize($data));
    }

    /**
     * @return string
     */
    public function getId(): string
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
        CharacterValidator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->isValidCharacterName()
            ->validate();

        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * @param string $server
     * @return $this
     */
    public function setServer(string $server)
    {
        CharacterValidator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->server = $server;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        CharacterValidator::getInstance()
            ->check($title, 'Title', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return $this
     */
    public function setAvatar(string $avatar)
    {
        CharacterValidator::getInstance()
            ->check($avatar, 'Avatar URL', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getPortrait(): string
    {
        return $this->portrait;
    }

    /**
     * @param string $portrait
     * @return $this
     */
    public function setPortrait(string $portrait)
    {
        CharacterValidator::getInstance()
            ->check($portrait, 'Portrait URL', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->portrait = $portrait;

        return $this;
    }

    /**
     * @return string
     */
    public function getBiography(): string
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     * @return $this
     */
    public function setBiography(string $biography)
    {
        CharacterValidator::getInstance()
            ->check($biography, 'Biography', $this->id)
            ->isInitialized()
            ->isStringOrEmpty()
            ->validate();

        $this->biography = $biography;

        return $this;
    }

    /**
     * @return string
     */
    public function getRace(): string
    {
        return $this->race;
    }

    /**
     * @param string $race
     * @return $this
     */
    public function setRace(string $race)
    {
        CharacterValidator::getInstance()
            ->check($race, 'Race', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->race = $race;

        return $this;
    }

    /**
     * @return string
     */
    public function getClan(): string
    {
        return $this->clan;
    }

    /**
     * @param string $clan
     * @return $this
     */
    public function setClan(string $clan)
    {
        CharacterValidator::getInstance()
            ->check($clan, 'Clan', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->clan = $clan;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender(string $gender)
    {
        CharacterValidator::getInstance()
            ->check($gender, 'Gender', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameday(): string
    {
        return $this->nameday;
    }

    /**
     * @param string $nameday
     * @return $this
     */
    public function setNameday(string $nameday)
    {
        CharacterValidator::getInstance()
            ->check($nameday, 'Nameday', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->nameday = $nameday;

        return $this;
    }

    /**
     * @return Guardian
     */
    public function getGuardian(): Guardian
    {
        return $this->guardian;
    }

    /**
     * @param Guardian $guardian
     * @return $this
     */
    public function setGuardian(Guardian $guardian)
    {
        CharacterValidator::getInstance()
            ->check($guardian, 'Guardian', $this->id)
            ->isInitialized()
            ->validate();

        $this->guardian = $guardian;

        return $this;
    }

    /**
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function setCity(City $city)
    {
        CharacterValidator::getInstance()
            ->check($city, 'City', $this->id)
            ->isInitialized()
            ->validate();

        $this->city = $city;

        return $this;
    }

    /**
     * @return GrandCompany|null
     */
    public function getGrandcompany()
    {
        return $this->grandcompany;
    }

    /**
     * @param GrandCompany $grandcompany
     * @return $this
     */
    public function setGrandcompany($grandcompany)
    {
        CharacterValidator::getInstance()
            ->check($grandcompany, 'Grand Company', $this->id)
            ->isInitialized()
            ->validate();

        $this->grandcompany = $grandcompany;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFreecompany()
    {
        return $this->freecompany;
    }

    /**
     * @param string $freecompany
     * @return $this
     */
    public function setFreecompany($freecompany)
    {
        CharacterValidator::getInstance()
            ->check($freecompany, 'Free Company', $this->id)
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->freecompany = $freecompany;

        return $this;
    }

    /**
     * @return ClassJob
     */
    public function getActiveClassJob(): ClassJob
    {
        return $this->activeClassJob;
    }

    /**
     * @param ClassJob $activeClassJob
     * @return CharacterProfile
     */
    public function setActiveClassJob($activeClassJob): CharacterProfile
    {
        CharacterValidator::getInstance()
            ->check($activeClassJob, 'Active ClassJob', $this->id)
            ->isNotEmpty()
            ->isObject()
            ->validate();
        
        $this->activeClassJob = $activeClassJob;
        return $this;
    }

    /**
     * @return Collectables
     */
    public function getCollectables(): Collectables
    {
        return $this->collectables;
    }

    /**
     * @param string $slot
     * @param Item $item
     * @return CharacterProfile $this
     */
    public function addGear(string $slot, Item $item)
    {
        $this->gear[$slot] = $item;
        return $this;
    }

    /**
     * @param string $slot
     * @return bool|Item $item
     */
    public function getGear(string $slot)
    {
        return $this->gear[$slot] ?? false;
    }

    /**
     * @param string $key
     * @param ClassJob $role
     * @return CharacterProfile $this
     */
    public function addClassJob(string $key, ClassJob $role)
    {
        $this->classjobs[$key] = $role;
        return $this;
    }

    /**
     * @param string $id
     * @return bool|ClassJob $job
     */
    public function getClassJob($id)
    {
        return $this->classjobs[$id] ?? false;
    }

    /**
     * @return array
     */
    public function getClassJobs()
    {
        return $this->classjobs;
    }

    /**
     * @param Attribute $attribute
     * @return CharacterProfile $this
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;
        return $this;
    }


}