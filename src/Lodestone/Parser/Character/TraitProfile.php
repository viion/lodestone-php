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

        // move to character profile detail
        $box = $this->getDocumentFromRange('character__profile__data__detail', 'character__view');
        $this->parseProfileRaceClanGender($box);

        // move onto profile
        $node = $box->find('.character-block', 1);
        $this->parseProfileNameDay($node);
        $this->parseProfileGuardian($node);
        $this->parseProfileCity();

        // handle grand company and free company
        if ($box = $this->getDocumentFromRangeCustom(40,100)) {
            // Grand Company
            $this->parseProfileGrandCompany($box);

            // Free Company
            $this->parseProfileFreeCompany($box);
        }

        unset($box);
        unset($node);

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
    protected function parseProfileRaceClanGender(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $data = $box
            ->find('.character-block', 0)
            ->find('.character-block__name')
            ->innerHtml();

        list($race, $data) = explode('<br>', html_entity_decode(trim($data)));
        list($clan, $gender) = explode('/', $data);

        $this->profile
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
    protected function parseProfileNameDay(Element $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $this->profile->setNameday(
            $box->find('.character-block__birth', 0)->plaintext
        );
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract Guardian details from html
     *
     * @param Element $box
     */
    protected function parseProfileGuardian(Element $box)
    {
        Benchmark::start(__METHOD__,__LINE__);

        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getGuardianId($name);

        $guardian = new Guardian();
        $guardian
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

        $this->profile->guardian = $guardian;

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract city from html
     */
    protected function parseProfileCity()
    {
        Benchmark::start(__METHOD__,__LINE__);

        $name = $this->getArrayFromRange('City-state', 2);
        if ($name) {
            $name = trim(strip_tags($name[1]));
            $id = $this->xivdb->getTownId($name);

            $city = new City();
            $city
                ->setName($name)
                ->setId($id);

            $this->profile->setCity($city);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract grand company details from html
     *
     * @param Document &$box
     */
    protected function parseProfileGrandCompany(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);

        if ($node = $box->find('.character-block__name', 2)) {
            list($name, $rank) = explode('/', $node->plaintext);
            $id = $this->xivdb->getGcId(trim($name));

            $grandcompany = new GrandCompany();
            $grandcompany
                ->setId(trim($id))
                ->setName(trim($name))
                ->setRank(trim($rank))
                ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

            $this->profile->grandcompany = $grandcompany;

            unset($node);
        }

        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract free company details from html
     *
     * @param Document &$box
     */
    protected function parseProfileFreeCompany(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        if ($node = $box->find('.character__freecompany__name', 0)) {
            $this->profile->setFreecompany(explode('/', $node->find('a', 0)->href)[3]);
            unset($node);
        }
        Benchmark::finish(__METHOD__,__LINE__);
    }
}