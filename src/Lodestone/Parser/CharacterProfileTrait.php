<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Benchmark;
use Lodestone\Entities\Character\Profile;
use Lodestone\Dom\{
    Document,
    Element
};

/**
 * Class CharacterProfileTrait
 *
 * @package Lodestone\Parser
 */
trait CharacterProfileTrait
{
    /**
     * Parse main profile bits
     */
    private function parseProfile()
    {
        $profile = new Profile();

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $box = $this->getDocumentFromRange('class="frame__chara__link"', 'class="parts__connect--state js__toggle_trigger"');
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        $this->parseProfileId($box, $profile);
        $this->parseProfileName($box, $profile);
        $this->parseProfileServer($box, $profile);
        $this->parseProfileTitle($box, $profile);
        $this->parseProfilePicture($box, $profile);
        $this->parseProfileBiography($profile);

        // ----------------------
        // move to character profile detail
        $box = $this->getDocumentFromRange('class="character__profile__data__detail"', 'class="btn__comment"');
        // ----------------------
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        $this->parseProfileDetails($box, $profile);

        $node = $box->find('.character-block', 1);
        $this->parseProfileNameDay($node, $profile);
        $this->parseProfileGuardian($node, $profile);
        $this->parseProfileCity($profile);

        $box = $this->getDocumentFromRangeCustom(48,64);
        if ($box)
        {
            // Grand Company
            $this->parseProfileGrandcompany($box, $profile);

            // Free Company
            $this->parseProfileFreeCompany($box, $profile);
        }

        // TODO: do something with profile

        unset($box);
        unset($node);
        unset($profile);
    }

    /**
     * Extract id of character from html
     *
     * @param $box
     * @param Profile &$profile
     */
    private function parseProfileId(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $id = explode('/', $box->find('.frame__chara__link', 0)->getAttribute('href'))[3];
        $profile->setId($id);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract name of character from html
     *
     * @param $box
     * @param Profile &$profile
     */
    private function parseProfileName(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $name = $box->find('.frame__chara__name', 0)->plaintext;
        $profile->setName($name);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract server name of character from html
     *
     * @param $box
     * @param Profile &$profile
     */
    private function parseProfileServer(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $server = $box->find('.frame__chara__world', 0)->plaintext;
        $profile->setServer($server);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract title of Character from html
     *
     * @param $box
     * @param Profile &$profile
     */
    private function parseProfileTitle(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        if ($title = $box->find('.frame__chara__title', 0)) {
            $profile->setTitle(trim($title));
        }

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extracts Character image urls from html
     *
     * @param $box
     * @param Profile &$profile
     */
    private function parseProfilePicture(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $data = trim(explode('?', $box->find('.frame__chara__face', 0)->find('img', 0)->src)[0]);
        $profile
            ->setAvatar($data)
            ->setPortrait(str_ireplace('c0_96x96', 'l0_640x873', $data));

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extracts Biography from html
     *
     * @param Profile &$profile
     */
    private function parseProfileBiography(Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $box = $this->getDocumentFromRange('class="character__selfintroduction"', 'class="btn__comment"');
        $profile->setBiography(trim($box->plaintext));

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract race, clan and gender from html
     *
     * @param Document &$box
     * @param Profile &$profile
     */
    private function parseProfileDetails(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $data = $box
            ->find('.character-block', 0)
            ->find('.character-block__name')
            ->innerHtml();

        list($race, $data) = explode('<br>', html_entity_decode(trim($data)));
        list($clan, $gender) = explode('/', $data);

        $profile
            ->setRace(strip_tags(trim($race)))
            ->setClan(strip_tags(trim($clan)))
            ->setGender(strip_tags(trim($gender)) == '♀' ? 'female' : 'male');

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract Nameday from html
     *
     * @param Element $box
     * @param Profile &$profile
     */
    private function parseProfileNameDay(Element &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        // nameday
        $profile->setNameday(
            $box->find('.character-block__birth', 0)
                ->plaintext
        );
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract Guardian details from html
     *
     * @param Element $box
     * @param Profile &$profile
     */
    private function parseProfileGuardian(Element &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $guardian = new Profile\Guardian();

        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getGuardianId($name);

        $guardian
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

        $profile->setGuardian($guardian);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract city from html
     *
     * @param Profile &$profile
     */
    private function parseProfileCity(Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $city = new Profile\City();

        $box = $this->getDocumentFromRangeCustom(42,47);
        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getTownId($name);

        $city
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

        $profile->setCity($city);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract grand company details from html
     *
     * @param Document &$box
     * @param Profile &$profile
     */
    private function parseProfileGrandcompany(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        if ($node = $box->find('.character-block__name', 0)) {
            $grandcompany = new Profile\GrandCompany();

            list($name, $rank) = explode('/', $node->plaintext);
            $id = $this->xivdb->getGrandCompanyId(trim($name));

            $grandcompany
                ->setId($id)
                ->setName($name)
                ->setRank($rank)
                ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

            $profile->setGrandcompany($grandcompany);
        }

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract free company details from html
     *
     * @param Document &$box
     * @param Profile &$profile
     */
    private function parseProfileFreeCompany(Document &$box, Profile &$profile)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        if ($node = $box->find('.character__freecompany__name', 0)) {
            $profile->setFreecompany(explode('/', $node->find('a', 0)->href)[3]);
        }

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }
}