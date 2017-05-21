<?php
namespace Lodestone\Entities\Character;

use Lodestone\Entities\AbstractEntity;
use Lodestone\Entities\Character\Profile\City;
use Lodestone\Entities\Character\Profile\GrandCompany;
use Lodestone\Entities\Character\Profile\Guardian;
use Lodestone\Validator\CharacterValidator;

/**
 * Class Profile
 * @package Lodestone\Entities\Character
 */
class Profile extends AbstractEntity {

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $server;

    /**
     * @var string|null
     */
    private $title = null;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var string
     */
    private $portrait;

    /**
     * @var string
     */
    private $biography = '';

    /**
     * @var string
     */
    private $race;

    /**
     * @var string
     */
    private $clan;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $nameday;

    /**
     * @var Guardian
     */
    private $guardian;

    /**
     * @var City
     */
    private $city;

    /**
     * @var GrandCompany|null
     */
    private $grandcompany = null;

    /**
     * @var string|null
     */
    private $freecompany = null;

    protected function initializeValidator() {
        $this->validator = new CharacterValidator();
    }
    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id) {
        $this->validator
            ->check($id, 'ID')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

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
            ->isString()
            ->isValidCharacterName()
            ->validate();
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getServer(): string {
        return $this->server;
    }

    /**
     * @param string $server
     * @return $this
     */
    public function setServer(string $server) {
        $this->validator
            ->check($server, 'Server')
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
    public function setTitle($title) {
        $this->validator
            ->check($title, 'Title')
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
    public function getAvatar(): string {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return $this
     */
    public function setAvatar(string $avatar) {
        $this->validator
            ->check($avatar, 'Avatar URL')
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
    public function getPortrait(): string {
        return $this->portrait;
    }

    /**
     * @param string $portrait
     * @return $this
     */
    public function setPortrait(string $portrait) {
        $this->validator
            ->check($portrait, 'Portrait URL')
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
    public function getBiography(): string {
        return $this->biography;
    }

    /**
     * @param string $biography
     * @return $this
     */
    public function setBiography(string $biography) {
        $this->validator
            ->check($biography, 'Biography')
            ->isInitialized()
            ->isStringOrEmpty()
            ->validate();

        $this->biography = $biography;

        return $this;
    }

    /**
     * @return string
     */
    public function getRace(): string {
        return $this->race;
    }

    /**
     * @param string $race
     * @return $this
     */
    public function setRace(string $race) {
        $this->validator
            ->check($race, 'Race')
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
    public function getClan(): string {
        return $this->clan;
    }

    /**
     * @param string $clan
     * @return $this
     */
    public function setClan(string $clan) {
        $this->validator
            ->check($clan, 'Clan')
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
    public function getGender(): string {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender(string $gender) {
        $this->validator
            ->check($gender, 'Gender')
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
    public function getNameday(): string {
        return $this->nameday;
    }

    /**
     * @param string $nameday
     * @return $this
     */
    public function setNameday(string $nameday) {
        $this->validator
            ->check($nameday, 'Nameday')
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
    public function getGuardian(): Guardian {
        return $this->guardian;
    }

    /**
     * @param Guardian $guardian
     * @return $this
     */
    public function setGuardian(Guardian $guardian) {
        $this->validator
            ->check($guardian, 'Guardian')
            ->isInitialized()
            ->validate();

        $this->guardian = $guardian;

        return $this;
    }

    /**
     * @return City
     */
    public function getCity(): City {
        return $this->city;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function setCity(City $city) {
        $this->validator
            ->check($city, 'City')
            ->isInitialized()
            ->validate();

        $this->city = $city;

        return $this;
    }

    /**
     * @return GrandCompany|null
     */
    public function getGrandcompany() {
        return $this->grandcompany;
    }

    /**
     * @param GrandCompany $grandcompany
     * @return $this
     */
    public function setGrandcompany($grandcompany) {
        $this->validator
            ->check($grandcompany, 'Grand Company')
            ->isInitialized()
            ->validate();

        $this->grandcompany = $grandcompany;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFreecompany() {
        return $this->freecompany;
    }

    /**
     * @param string $freecompany
     * @return $this
     */
    public function setFreecompany($freecompany) {
        $this->validator
            ->check($freecompany, 'Free Company')
            ->isInitialized()
            ->isNotEmpty()
            ->isString()
            ->validate();

        $this->freecompany = $freecompany;

        return $this;
    }
    
    
}