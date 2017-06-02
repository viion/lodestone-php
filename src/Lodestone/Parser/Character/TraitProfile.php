<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entities\Character\City;
use Lodestone\Entities\Character\GrandCompany;
use Lodestone\Entities\Character\Guardian;
use Lodestone\Modules\Benchmark;
use Lodestone\Dom\{
    Document,
    Element
};

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
        $box = $this->getDocumentFromRange('class="frame__chara__link"', 'class="parts__connect--state js__toggle_trigger"');

        $this->parseProfileName($box);
        $this->parseProfileServer($box);
        $this->parseProfileTitle($box);
        $this->parseProfilePicture($box);
        $this->parseProfileBiography();

        // move to character profile detail
        $box = $this->getDocumentFromRange('class="character__profile__data__detail"', 'class="btn__comment"');
        $this->parseProfileRaceClanGender($box);

        // move onto profile
        $node = $box->find('.character-block', 1);
        $this->parseProfileNameDay($node);
        $this->parseProfileGuardian($node);
        $this->parseProfileCity();

        // handle grand company and free company
        if ($box = $this->getDocumentFromRangeCustom(48,64)) {
            // Grand Company
            $this->parseProfileGrandcompany($box);

            // Free Company
            $this->parseProfileFreeCompany($box);
        }

        unset($box);
        unset($node);
    }

    /**
     * Extract name of character from html
     *
     * @param $box
     */
    protected function parseProfileName(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $this->profile->setName($box->find('.frame__chara__name', 0)->plaintext);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract server name of character from html
     *
     * @param $box
     */
    protected function parseProfileServer(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $this->profile->setServer($box->find('.frame__chara__world', 0)->plaintext);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract title of Character from html
     *
     * @param $box
     */
    protected function parseProfileTitle(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        if ($title = $box->find('.frame__chara__title', 0)) {
            $this->profile->setTitle(trim($title->plaintext));
        }
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extracts Character image urls from html
     *
     * @param $box
     */
    protected function parseProfilePicture(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $data = trim(explode('?', $box->find('.frame__chara__face', 0)->find('img', 0)->src)[0]);
        $this->profile
            ->setAvatar($data)
            ->setPortrait(str_ireplace('c0_96x96', 'l0_640x873', $data));
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extracts Biography from html
     */
    protected function parseProfileBiography()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $this->profile->setBiography(trim(
            $this
                ->getDocumentFromRange('class="character__selfintroduction"', 'class="btn__comment"')
                ->plaintext)
        );
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
        $box = $this->getDocumentFromRangeCustom(42,47);

        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getTownId($name);

        $city = new City();
        $city
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

        $this->profile->city = $city;

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Extract grand company details from html
     *
     * @param Document &$box
     */
    protected function parseProfileGrandcompany(Document $box)
    {
        Benchmark::start(__METHOD__,__LINE__);

        if ($node = $box->find('.character-block__name', 0)) {
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