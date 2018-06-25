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
        foreach ($rows as $row) {
            $blocktitle = $row->find('.character-block__title')->plaintext;
            if (in_array($blocktitle, array('Race/Clan/Gender', 'Volk / Stamm / Geschlecht', 'Race / Ethnie / Sexe', '種族/部族/性別'))) {
                $this->parseProfileRaceClanGender($row);
            } elseif (in_array($blocktitle, array('NamedayGuardian', 'NamenstagSchutzgott', 'Date de naissanceDivinité', '誕生日守護神'))) {
                $this->parseProfileNameDay($row);
            } elseif (in_array($blocktitle, array('City-state', 'Stadtstaat', 'Cité de départ', '開始都市'))) {
                $this->parseProfileCity($row);
            } elseif (in_array($blocktitle, array('Grand Company', 'Staatliche Gesellschaft', 'Grande compagnie', '所属グランドカンパニー'))) {
                $this->parseProfileGrandCompany($row);
            } else {
                if ($row->find('.character__freecompany__name')->plaintext != "") {
                    $this->parseProfileFreeCompany($row);
                } elseif ($row->find('.character__pvpteam__name')->find('h4')->plaintext != "") {
                    $this->parseProfilePvPTeam($row);
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
    
    /**
     * Parse: PvP Team
     */
    protected function parseProfilePvPTeam($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $this->profile->setPvPTeam(trim(explode('/', $node->find("a", 0)->getAttribute("href"))[3]));

        Benchmark::finish(__METHOD__,__LINE__);
    }
}
