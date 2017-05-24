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
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $box = $this->getDocumentFromRange('class="frame__chara__link"', 'class="parts__connect--state js__toggle_trigger"');
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        $this->parseProfileName($box);
        $this->parseProfileServer($box);
        $this->parseProfileTitle($box);
        $this->parseProfilePicture($box);
        $this->parseProfileBiography();

        // ----------------------
        // move to character profile detail
        $box = $this->getDocumentFromRange('class="character__profile__data__detail"', 'class="btn__comment"');
        // ----------------------
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        $this->parseProfileDetails($box);

        $node = $box->find('.character-block', 1);
        $this->parseProfileNameDay($node);
        $this->parseProfileGuardian($node);
        $this->parseProfileCity();

        $box = $this->getDocumentFromRangeCustom(48,64);
        if ($box)
        {
            // Grand Company
            $this->parseProfileGrandcompany($box);

            // Free Company
            $this->parseProfileFreeCompany($box);
        }

        // TODO: do something with profile

        unset($box);
        unset($node);
        unset($this->profile);
    }

    /**
     * Extract name of character from html
     *
     * @param $box
     */
    private function parseProfileName(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $this->profile->setName($box->find('.frame__chara__name', 0)->plaintext);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract server name of character from html
     *
     * @param $box
     */
    private function parseProfileServer(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $this->profile->setServer($box->find('.frame__chara__world', 0)->plaintext);

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract title of Character from html
     *
     * @param $box
     */
    private function parseProfileTitle(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        if ($title = $box->find('.frame__chara__title', 0)) {
            $this->profile->setTitle(trim($title->plaintext));
        }

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extracts Character image urls from html
     *
     * @param $box
     */
    private function parseProfilePicture(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $data = trim(explode('?', $box->find('.frame__chara__face', 0)->find('img', 0)->src)[0]);
        $this->profile
            ->setAvatar($data)
            ->setPortrait(str_ireplace('c0_96x96', 'l0_640x873', $data));

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extracts Biography from html
     */
    private function parseProfileBiography()
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        $this->profile->setBiography(trim(
                $this
                    ->getDocumentFromRange('class="character__selfintroduction"', 'class="btn__comment"')
                    ->plaintext)
        );

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract race, clan and gender from html
     *
     * @param Document &$box
     */
    private function parseProfileDetails(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
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
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract Nameday from html
     *
     * @param Element $box
     */
    private function parseProfileNameDay(Element &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        // nameday
        $this->profile->setNameday(
            $box->find('.character-block__birth', 0)->plaintext
        );
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract Guardian details from html
     *
     * @param Element $box
     */
    private function parseProfileGuardian(Element &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getGuardianId($name);

        $this->profile->getGuardian()
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract city from html
     */
    private function parseProfileCity()
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        $box = $this->getDocumentFromRangeCustom(42,47);
        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getTownId($name);

        $this->profile->getCity()
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract grand company details from html
     *
     * @param Document &$box
     */
    private function parseProfileGrandcompany(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
        if ($node = $box->find('.character-block__name', 0)) {
            list($name, $rank) = explode('/', $node->plaintext);
            $id = $this->xivdb->getGrandCompanyId(trim($name));

            $this->profile->getGrandcompany()
                ->setId($id)
                ->setName($name)
                ->setRank($rank)
                ->setIcon(explode('?', $box->find('img', 0)->src)[0]);
        }

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }

    /**
     * Extract free company details from html
     *
     * @param Document &$box
     */
    private function parseProfileFreeCompany(Document &$box)
    {
        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);

        if ($node = $box->find('.character__freecompany__name', 0)) {
            $this->profile->setFreecompany(explode('/', $node->find('a', 0)->href)[3]);
        }

        Benchmark::record(__CLASS__,__FUNCTION__,__LINE__);
    }
}