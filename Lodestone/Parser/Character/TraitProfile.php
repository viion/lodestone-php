<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Logging\Benchmark;
use Lodestone\Entities\Character\{
    Town,
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
            if (in_array($blocktitle, ['Race/Clan/Gender', 'Volk / Stamm / Geschlecht', 'Race / Ethnie / Sexe', '種族/部族/性別'])) {
                $this->parseProfileRaceTribeGender($row);
            } elseif (in_array($blocktitle, ['NamedayGuardian', 'NamenstagSchutzgott', 'Date de naissanceDivinité', '誕生日守護神'])) {
                $this->parseProfileNameDay($row);
            } elseif (in_array($blocktitle, ['City-state', 'Stadtstaat', 'Cité de départ', '開始都市'])) {
                $this->parseProfileTown($row);
            } elseif (in_array($blocktitle, ['Grand Company', 'Staatliche Gesellschaft', 'Grande compagnie', '所属グランドカンパニー'])) {
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
        $this->parseProfileBio();

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Name, Server, Title
     */
    protected function parseProfileBasic()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $this->getArrayFromRange('frame__chara', 'parts__connect--state', $this->html);

        // name
        $name = $this->getArrayFromRange('frame__chara__name', 0, $html);
        $name = trim(strip_tags($name[0]));
        $name = html_entity_decode($name, ENT_QUOTES, "UTF-8");
        $this->results->setName($name);
        
        // server
        $server = $this->getArrayFromRange('frame__chara__world', 0, $html);
        $this->results->setServer(trim(strip_tags($server[0])));

        // title
        $title = $this->getArrayFromRange('frame__chara__title', 0, $html);
        if ($title) {
            $this->results->setTitle(trim(strip_tags($title[0])));
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Bio
     */
    protected function parseProfileBio()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $bio = $this->getArrayFromRange('character__selfintroduction', 'btn__comment', $this->html);
        $bio = trim($bio[1]);
        $bio = html_entity_decode($bio, ENT_QUOTES, "UTF-8");

        if (strip_tags($bio)) {
            $this->results->setBio($bio);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Race, Tribe, Gender and Avatar
     */
    protected function parseProfileRaceTribeGender($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $html = $node->find('.character-block__name', 0)->innerHTML();
        $html = str_ireplace(['<br />','<br>','<br/>'], ' / ', $html);

        list($race, $tribe, $gender) = explode('/', strip_tags($html));

        $this->results
            ->setRace(strip_tags(trim($race)))
            ->setTribe(strip_tags(trim($tribe)))
            ->setGender(strip_tags(trim($gender)) == '♀' ? 'female' : 'male');
         
        // picture
        $avatar = $this->getImageSource($node->find('img', 0));
        $this->results
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

        $this->results->setNameday($node->find('.character-block__birth', 0)->plaintext);
        $guardian = new Guardian();
        $guardian
            ->setName(html_entity_decode($node->find('.character-block__name', 0)->plaintext, ENT_QUOTES, "UTF-8"))
            ->setIcon($this->getImageSource($node->find('img', 0)));
        $this->results->setGuardian($guardian);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Town
     */
    protected function parseProfileTown($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $town = new Town();
        $town->setName(html_entity_decode($node->find('.character-block__name', 0)->plaintext, ENT_QUOTES, "UTF-8"));
        $town->setIcon($this->getImageSource($node->find('img', 0)));

        $this->results->setTown($town);

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

        $this->results->setGrandCompany($grandcompany);

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse: Free Company
     */
    protected function parseProfileFreeCompany($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $this->results->setFreecompany(trim(explode('/', $node->find("a", 0)->getAttribute("href"))[3]));

        Benchmark::finish(__METHOD__,__LINE__);
    }
    
    /**
     * Parse: PvP Team
     */
    protected function parseProfilePvPTeam($node)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $this->results->setPvPTeam(trim(explode('/', $node->find("a", 0)->getAttribute("href"))[3]));

        Benchmark::finish(__METHOD__,__LINE__);
    }
}
