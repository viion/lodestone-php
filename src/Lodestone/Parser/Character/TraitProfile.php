<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark;
use Lodestone\Entities\Character\{
    City,
    GrandCompany,
    Guardian
};

/**
 * Class TraitProfile
 *
 * Handles parsing character profile information
 *
 * @package Lodestone\Parser
 */
trait TraitProfile
{
    /**
     * Parse main profile bits
     */
    protected function parseProfile()
    {
        Benchmark::start(__METHOD__,__LINE__);

        // parse main profile info
        $this->parseProfileBasic();
        $this->parseProfileBiography();
        $this->parseProfileRaceClanGender();
        $this->parseProfileNameDay();
        $this->parseProfileGuardian();
        $this->parseProfileCity();

        // grand company + free company
        $this->parseProfileGrandCompany();
        $this->parseProfileFreeCompany();

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Name, Server, Title, Picture
     */
    protected function parseProfileBasic()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('frame__chara', 'parts__connect--state');

        //name
        $name = $this->getArrayFromRange('frame__chara__name', 0, $html);
        $this->profile->setName(trim(strip_tags($name[0])));
        
        //server
        $server = $this->getArrayFromRange('frame__chara__world', 0, $html);
        $this->profile->setServer(trim(strip_tags($server[0])));

        // title
        $title = $this->getArrayFromRange('frame__chara__title', 0, $html);
        if ($title) {
            $this->profile->setTitle(trim(strip_tags($title[0])));
        }

        // picture
        $avatar = $this->getArrayFromRange('frame__chara__face', 2, $html);
        $avatar = $this->getImageSource($avatar[1]);
        $this->profile
            ->setAvatar($avatar)
            ->setPortrait(str_ireplace('c0_96x96', 'l0_640x873', $avatar));

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Biography
     */
    protected function parseProfileBiography()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $bio = $this->getArrayFromRange('character__selfintroduction', 'btn__comment');
        $bio = trim($bio[1]);

        if (strip_tags($bio)) {
            $this->profile->setBiography($bio);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Race, Clan and Gender
     */
    protected function parseProfileRaceClanGender()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Race/Clan/Gender', 2, $html);

        // refactor it
        $html = trim($html[1]);
        $html = str_ireplace(['<br />','<br>','<br/>'], ' / ', $html);

        list($race, $clan, $gender) = explode('/', strip_tags($html));

        $this->profile
            ->setRace(strip_tags(trim($race)))
            ->setClan(strip_tags(trim($clan)))
            ->setGender(strip_tags(trim($gender)) == '♀' ? 'female' : 'male');

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Nameday
     */
    protected function parseProfileNameDay()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Nameday', 1, $html);

        $this->profile->setNameday(strip_tags($html[1]));

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Guardian
     */
    protected function parseProfileGuardian()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Guardian', 1, $html);

        $name = strip_tags($html[1]);
        $id = $this->xivdb->getGuardianId($name);

        $guardian = new Guardian();
        $guardian
            ->setName($name)
            ->setId($id);

        $this->profile->setGuardian($guardian);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: City
     */
    protected function parseProfileCity()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $name = $this->getArrayFromRange('City-state', 2);
        $name = trim(strip_tags($name[1]));

        // needed to convert Ul&#39;dah -> Ul'dah
        if ($name == 'Ul&#39;dah') {
            $name = html_entity_decode($name, ENT_QUOTES, "UTF-8");
        }

        $id = $this->xivdb->getTownId($name);
        // todo - get icon from XIVDB and attach it

        $city = new City();
        $city
            ->setName($name)
            ->setId($id);

        $this->profile->setCity($city);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Grand Company
     */
    protected function parseProfileGrandCompany()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Grand Company', 1, $html);

        // not all characters have a grand company
        if (isset($html[1])) {
            list($name, $rank) = explode('/', strip_tags($html[1]));

            $name = trim($name);
            $rank = trim($rank);

            $id = $this->xivdb->getGcId($name);
            // todo - get icon from XIVDB and attach it (including rank)

            $grandcompany = new GrandCompany();
            $grandcompany
                ->setId($id)
                ->setName($name)
                ->setRank($rank);

            $this->profile->setGrandCompany($grandcompany);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Free Company
     */
    protected function parseProfileFreeCompany()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Free Company', 1, $html);

        // not all characters have a free company
        if (isset($html[1])) {
            $url = trim(explode('/', $html[1])[3]);
            $this->profile->setFreecompany($url);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }
}
