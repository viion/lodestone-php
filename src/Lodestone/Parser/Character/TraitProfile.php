<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Logging\Benchmark;
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
        $rows = $this->getSpecial__Profile_Data_Details()->find('.character-block');
        $this->parseProfileRaceClanGender($rows[0]);
        $this->parseProfileNameDay($rows[1]);
        $this->parseProfileCity($rows[2]);
        if (!empty($rows[4])) {
            $this->parseProfileGrandCompany($rows[3]);
            $this->parseProfileFreeCompany($rows[4]);
        } else {
            if (!empty($rows[3])) {
                if ($rows[3]->find('.character__freecompany__name')->plaintext != "") {
                    $this->parseProfileFreeCompany($rows[3]);
                } else {
                    $this->parseProfileGrandCompany($rows[3]);
                }
            }
        }
        $this->parseProfileBasic();
        $this->parseProfileBiography();

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Name, Server, Title
     */
    protected function parseProfileBasic()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('frame__chara', 'parts__connect--state');

        // name
        $name = $this->getArrayFromRange('frame__chara__name', 0, $html);
        $name = trim(strip_tags($name[0]));
        $name = html_entity_decode($name, ENT_QUOTES, "UTF-8");
        $this->profile->setName($name);
        
        // server
        $server = $this->getArrayFromRange('frame__chara__world', 0, $html);
        $this->profile->setServer(trim(strip_tags($server[0])));

        // title
        $title = $this->getArrayFromRange('frame__chara__title', 0, $html);
        if ($title) {
            $this->profile->setTitle(trim(strip_tags($title[0])));
        }

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
        $bio = html_entity_decode($bio, ENT_QUOTES, "UTF-8");

        if (strip_tags($bio)) {
            $this->profile->setBiography($bio);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Race, Clan, Gender and Avatar
     */
    protected function parseProfileRaceClanGender($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $node->find('.character-block__name', 0)->innerHTML();
        $html = str_ireplace(['<br />','<br>','<br/>'], ' / ', $html);

        list($race, $clan, $gender) = explode('/', strip_tags($html));

        $this->profile
            ->setRace(strip_tags(trim($race)))
            ->setClan(strip_tags(trim($clan)))
            ->setGender(strip_tags(trim($gender)) == '♀' ? 'female' : 'male');
         
        // picture
        $avatar = $this->getImageSource($node->find('img', 0));
        $this->profile
            ->setAvatar($avatar)
            ->setPortrait(str_ireplace('c0_96x96', 'l0_640x873', $avatar));

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Nameday and Guardian
     */
    protected function parseProfileNameDay($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $this->profile->setNameday($node->find('.character-block__birth', 0)->plaintext);
        $guardian = new Guardian();
        $guardian
            ->setName(html_entity_decode($node->find('.character-block__name', 0)->plaintext, ENT_QUOTES, "UTF-8"))
            ->setIcon($this->getImageSource($node->find('img', 0)));
        $this->profile->setGuardian($guardian);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: City
     */
    protected function parseProfileCity($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $city = new City();
        $city->setName(html_entity_decode($node->find('.character-block__name', 0)->plaintext, ENT_QUOTES, "UTF-8"));
        $city->setIcon($this->getImageSource($node->find('img', 0)));

        $this->profile->setCity($city);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Grand Company
     */
    protected function parseProfileGrandCompany($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $node->find('.character-block__name', 0)->innerHTML();

        // not all characters have a grand company
        list($name, $rank) = explode('/', strip_tags($html));

        $grandcompany = new GrandCompany();
        $grandcompany
            ->setName(trim($name))
            ->setIcon($this->getImageSource($node->find('img', 0)))
            ->setRank(trim($rank));

        $this->profile->setGrandCompany($grandcompany);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Free Company
     */
    protected function parseProfileFreeCompany($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $this->profile->setFreecompany(trim(explode('/', $node->find("a", 0)->getAttribute("href"))[3]));

        Benchmark::finish(__METHOD__,__LINE__);
    }
}
