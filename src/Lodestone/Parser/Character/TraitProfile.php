<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark;
use Lodestone\Entities\Character\{
    City,
    GrandCompany,
    Guardian
};
use Lodestone\Dom\{
    Document,
    Element
};
use Lodestone\Modules\Logger;

/**
 * Class CharacterProfileTrait
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
        $started = Benchmark::milliseconds();

        // parse basic info
        $this->parseProfileBasic();
        $this->parseProfileBiography();
        $this->parseProfileRaceClanGender();

        $this->parseProfileNameDay();
        $this->parseProfileGuardian();
        $this->parseProfileCity();

        // grand company
        $this->parseProfileGrandCompany();
        $this->parseProfileFreeCompany();


        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        print_r("\n$duration ms\n\n");
        Logger::save($duration);
        die;
    }

    /**
     * Parse: Name, Server, Title, Picture
     */
    protected function parseProfileBasic()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('frame__chara', 'parts__connect--state');

        // name + servers
        list($name, $server) = $this->getArrayFromRange('frame__chara__name', 1, $html);
        $this->profile
            ->setName(trim(strip_tags($name)))
            ->setServer(trim(strip_tags($server)));

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
     * Extracts Biography from html
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
     * Extract race, clan and gender from html
     *
     * @param Document &$box
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

        $this
            ->profile
            ->setRace(strip_tags(trim($race)))
            ->setClan(strip_tags(trim($clan)))
            ->setGender(strip_tags(trim($gender)) == '♀' ? 'female' : 'male');

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract Nameday from html
     *
     * @param Element $box
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
     * Extract Guardian details from html
     *
     * @param Element $box
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

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract city from html
     */
    protected function parseProfileCity()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $name = $this->getArrayFromRange('City-state', 2);
        $name = trim(strip_tags($name[1]));
        $id = $this->xivdb->getTownId($name);

        $city = new City();
        $city
            ->setName($name)
            ->setId($id);

        $this->profile->setCity($city);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract grand company details from html
     *
     * @param Document &$box
     */
    protected function parseProfileGrandCompany()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Grand Company', 1, $html);

        // not all characters have a grand company
        if ($html[1]) {
            list($name, $rank) = explode('/', strip_tags($html[1]));

            $name = trim($name);
            $rank = trim($rank);

            $id = $this->xivdb->getGcId($name);

            $grandcompany = new GrandCompany();
            $grandcompany
                ->setId($id)
                ->setName($name)
                ->setRank($rank);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract free company details from html
     *
     * @param Document &$box
     */
    protected function parseProfileFreeCompany()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('character__profile__data__detail', 'character__level');
        $html = $this->getArrayFromRange('Free Company', 1, $html);

        // not all characters have a free company
        if ($html[1]) {
            $url = trim(explode('/', $html[1])[3]);
            $this->profile->setFreecompany($url);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }
}