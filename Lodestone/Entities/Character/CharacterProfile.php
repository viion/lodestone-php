<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Modules\Validator
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
     * @index Server
     */
    protected $server;

    /**
     * @var string
     * @index Title
     */
    protected $title = null;

    /**
     * @var string
     * @index Avatar
     */
    protected $avatar;

    /**
     * @var string
     * @index Portrait
     */
    protected $portrait;

    /**
     * @var string
     * @index Bio
     */
    protected $bio = '';

    /**
     * @var string
     * @index Race
     */
    protected $race;

    /**
     * @var string
     * @index Tribe
     */
    protected $tribe;

    /**
     * @var string
     * @index Gender
     */
    protected $gender;

    /**
     * @var string
     * @index NamesDay
     */
    protected $nameday;

    /**
     * @var Guardian
     * @index GuardianDeity
     */
    protected $guardian;

    /**
     * @var Town
     * @index Town
     */
    protected $town;

    /**
     * @var GrandCompany|null
     * @index GrandCompany
     */
    protected $grandCompany = null;

    /**
     * @var string|null
     * @index FreeCompany
     */
    protected $freecompany = null;
    
    /**
     * @var string|null
     * @index PvPTeam
     */
    protected $pvpteam = null;

    /**
     * @var array
     * @index ClassJobs
     */
    protected $classjobs = [];

    /**
     * @var array
     * @index Attributes
     */
    protected $attributes = [];

    /**
     * @var Collectables
     * @index Collectables
     */
    protected $collectables = null;

    /**
     * @var array
     * @index Gear
     */
    protected $gear = [];

    /**
     * @var ClassJob
     * @index ActiveClassJob
     */
    protected $activeClassJob = null;

    /**
     * Profile constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        Validator::getInstance()
            ->check($id, 'ID', $this->id)
            ->isNotEmpty()
            ->isNumeric();

        $this->id = $id;

        // profile classes
        $this->collectables = new Collectables();
    }

    public function getHash()
    {
        $data = $this->toArray(1);

        // remove hash, obvs (its blank anyway)
        unset($data['Hash']);

        // remove images, urls can change
        unset($data['Avatar']);
        unset($data['Portrait']);
        unset($data['GuardianDeity']['Icon']);
        unset($data['Town']['Icon']);
        unset($data['GrandCompany']['Icon']);

        // remove free company id, being kicked
        // should not generate a new hash
        unset($data['FreeCompany']);
        
        // remove pvp team id, being kicked
        // should not generate a new hash
        unset($data['PvPTeam']);

        // remove bio as this is too "open"
        // and could become malformed easily.
        unset($data['Bio']);

        // remove stats, SE can change the formula
        unset($data['Stats']);

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
        Validator::getInstance()
            ->check($name, 'Name', $this->id)
            ->isNotEmpty()
            ->isString()
            ->isValidCharacterName();

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
        Validator::getInstance()
            ->check($server, 'Server', $this->id)
            ->isNotEmpty()
            ->isString();

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
        Validator::getInstance()
            ->check($title, 'Title', $this->id)
            ->isNotEmpty()
            ->isString();

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
        Validator::getInstance()
            ->check($avatar, 'Avatar URL', $this->id)
            ->isNotEmpty()
            ->isString();

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
        Validator::getInstance()
            ->check($portrait, 'Portrait URL', $this->id)
            ->isNotEmpty()
            ->isString();

        $this->portrait = $portrait;

        return $this;
    }

    /**
     * @return string
     */
    public function getBio(): string
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     * @return $this
     */
    public function setBio(string $bio)
    {
        Validator::getInstance()
            ->check($bio, 'Bio', $this->id)
            ->isStringOrEmpty();

        $this->bio = $bio;

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
        Validator::getInstance()
            ->check($race, 'Race', $this->id)
            ->isNotEmpty()
            ->isString();

        $this->race = $race;

        return $this;
    }

    /**
     * @return string
     */
    public function getTribe(): string
    {
        return $this->tribe;
    }

    /**
     * @param string $tribe
     * @return $this
     */
    public function setTribe(string $tribe)
    {
        Validator::getInstance()
            ->check($tribe, 'Tribe', $this->id)
            ->isNotEmpty()
            ->isString();

        $this->tribe = $tribe;

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
        Validator::getInstance()
            ->check($gender, 'Gender', $this->id)
            ->isNotEmpty()
            ->isString();

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
        Validator::getInstance()
            ->check($nameday, 'Nameday', $this->id)
            ->isNotEmpty()
            ->isString();

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
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @return Town
     */
    public function getTown(): Town
    {
        return $this->town;
    }

    /**
     * @param Town $town
     * @return $this
     */
    public function setTown(Town $town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return GrandCompany|null
     */
    public function getGrandCompany()
    {
        return $this->grandCompany;
    }

    /**
     * @param GrandCompany $grandCompany
     * @return $this
     */
    public function setGrandCompany($grandCompany)
    {
        $this->grandCompany = $grandCompany;
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
        Validator::getInstance()
            ->check($freecompany, 'Free Company', $this->id)
            ->isNotEmpty()
            ->isString();

        $this->freecompany = $freecompany;

        return $this;
    }
    
    /**
     * @param string $pvpteam
     * @return $this
     */
    public function setPvPTeam($pvpteam)
    {
        Validator::getInstance()
            ->check($pvpteam, 'PvP Team', $this->id)
            ->isNotEmpty()
            ->isString();

        $this->pvpteam = $pvpteam;

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
        Validator::getInstance()
            ->check($activeClassJob, 'Active ClassJob', $this->id)
            ->isNotEmpty();
        
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
