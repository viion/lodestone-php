<?php

namespace Lodestone\Parser;

use Lodestone\Dom\Document;
use Lodestone\Dom\Element;
use Lodestone\Dom\NodeList;
use Lodestone\Entities\Character\Collectable;
use Lodestone\Entities\Character\Profile,
    Lodestone\Modules\Logger,
    Lodestone\Modules\XIVDB;

/**
 * Class Character
 * @package src\Parser
 */
class Character extends ParserHelper
{
    private $xivdb;
    /**
     * Character constructor.
     */
    function __construct()
    {
        $this->xivdb = new XIVDB();
    }

    /**
     * @param bool $hash
     * @return array|bool
     */
    public function parse($hash = false)
    {
        $this->ensureHtml();
        $html = $this->html;

        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');

        $this->setInitialDocument($html);

        $started = microtime(true);
        $this->parseProfile();
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION PROFILE: %s ms', round(microtime(true) - $started, 3)));
        $this->parseClassJobs();
        $this->parseAttributes();
        $this->parseCollectables();
        $this->parseEquipGear();
        $this->parseActiveClass();
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', round(microtime(true) - $started, 3)));

        if ($hash) {
            return $this->hash();
        }

        return $this->data;
    }

    /**
     * @return bool
     */
    public function hash()
    {
        if (!$this->data) {
            Logger::write(__CLASS__, __LINE__, 'No data to hash against, have you done a parse() call?');
            return false;
        }

        // remove data that could change outside
        // of the players controller (inconsistent fake hash)
        $data = $this->data;

        // ---
        // Much of the data is pulled out and stripped down, this
        // is to try keep it consistent and not use array keys
        // that are implemented through the parser and are not
        // part of lodestone, it also bypasses json config issues.
        // ---

        // build a tight small array
        $arr = [];
        $arr[] = $data['id'];
        $arr[] = $data['name'];
        $arr[] = $data['server'];
        $arr[] = $data['title'];
        $arr[] = $data['race'];
        $arr[] = $data['clan'];
        $arr[] = $data['gender'];
        $arr[] = $data['nameday'];
        $arr[] = $data['guardian']['name'];
        $arr[] = $data['city']['name'];
        $arr[] = $data['grand_company']['name'];
        $arr[] = $data['grand_company']['rank'];

        foreach($data['classjobs'] as $classjob) {
            // classjob _ level _ current exp _ max exp
            $arr[] = sprintf('classjob_%s_%s_%s', $classjob['level'], $classjob['exp']['current'], $classjob['exp']['max']);
        }

        foreach($data['stats'] as $statlist) {
            foreach($statlist as $name => $attr) {
                // stat _ value
                $arr[] = 'stat_'. $attr['value'];
            }
        }

        foreach($data['mounts'] as $mount) {
            // mount _ value
            $arr[] = 'mount_'. strtolower($mount['name']);
        }

        foreach($data['minions'] as $minion) {
            // minion _ value
            $arr[] = 'minion_'. strtolower($minion['name']);
        }

        foreach($data['gear'] as $gear) {
            // gear _ id _ materia count _ dye id _ mirage id
            $arr[] = sprintf('gear_%s_%s_%s_%s', $gear['id'], count($gear['materia']), $gear['dye_id'], $gear['mirage_id']);
        }

        // active role _ id _ level
        $arr[] = 'active_role_'. $data['active_class']['id'] .'_'. $data['active_class']['level'];

        // lower all values
        array_walk($arr, function(&$value) {
            $value = strtolower($value);
        });

        // ensure same sorting
        asort($arr);

        // reduce to string
        $arr = implode('|', $arr);

        // provide sha1
        return sha1($arr);
    }
    /**
     * Extract id of character from html
     *
     * @param $box
     * @param Profile $profile
     */
    private function parseProfileId(Document $box, Profile $profile) {
        $id = explode('/', $box->find('.frame__chara__link', 0)->getAttribute('href'))[3];
        $profile->setId($id);

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract name of character from html
     *
     * @param $box
     * @param Profile $profile
     */
    private function parseProfileName(Document $box, Profile $profile) {
        $name = $box->find('.frame__chara__name', 0)->plaintext;
        $profile->setName($name);

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract server name of character from html
     *
     * @param $box
     * @param Profile $profile
     */
    private function parseProfileServer(Document $box, Profile $profile) {
        $server = $box->find('.frame__chara__world', 0)->plaintext;
        $profile->setServer($server);

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract title of Character from html
     *
     * @param $box
     * @param Profile $profile
     */
    private function parseProfileTitle(Document $box, Profile $profile) {
        if ($title = $box->find('.frame__chara__title', 0)) {
            $profile->setTitle(trim($title));
        }

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extracts Character image urls from html
     *
     * @param $box
     * @param Profile $profile
     */
    private function parseProfilePicture(Document $box, Profile $profile) {
        $data = trim(explode('?', $box->find('.frame__chara__face', 0)->find('img', 0)->src)[0]);
        $profile
            ->setAvatar($data)
            ->setPortrait(str_ireplace('c0_96x96', 'l0_640x873', $data));

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extracts Biography from html
     *
     * @param Profile $profile
     */
    private function parseProfileBiography(Profile $profile) {
        $box = $this->getDocumentFromRange('class="character__selfintroduction"', 'class="btn__comment"');
        $profile->setBiography(trim($box->plaintext));

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract race, clan and gender from html
     *
     * @param Document $box
     * @param Profile $profile
     */
    private function parseProfileDetails(Document $box, Profile $profile) {
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

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract Nameday from html
     *
     * @param NodeList $box
     * @param Profile $profile
     */
    private function parseProfileNameDay(Element $box, Profile $profile) {
        // nameday
        $profile->setNameday(
            $box->find('.character-block__birth', 0)
                ->plaintext
        );
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract Guardian details from html
     *
     * @param NodeList $box
     * @param Profile $profile
     */
    private function parseProfileGuardian(Element $box, Profile $profile) {
        $guardian = new Profile\Guardian();

        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getGuardianId($name);

        $guardian
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

        $profile->setGuardian($guardian);

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract city from html
     *
     * @param Profile $profile
     */
    private function parseProfileCity(Profile $profile) {
        $city = new Profile\City();

        $box = $this->getDocumentFromRangeCustom(42,47);
        $name = $box->find('.character-block__name', 0)->plaintext;
        $id = $this->xivdb->getTownId($name);

        $city
            ->setName($name)
            ->setId($id)
            ->setIcon(explode('?', $box->find('img', 0)->src)[0]);

        $profile->setCity($city);

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract grand company details from html
     *
     * @param Document $box
     * @param Profile $profile
     */
    private function parseProfileGrandcompany(Document $box, Profile $profile) {
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
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Extract free company details from html
     *
     * @param Document $box
     * @param Profile $profile
     */
    private function parseProfileFreeCompany(Document $box, Profile $profile) {
        if ($node = $box->find('.character__freecompany__name', 0))
        {
            $profile->setFreecompany(explode('/', $node->find('a', 0)->href)[3]);
        }
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
    }

    /**
     * Parse main profile bits
     */
    private function parseProfile()
    {
        $profile = new Profile();

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getDocumentFromRange('class="frame__chara__link"', 'class="parts__connect--state js__toggle_trigger"');
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

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
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

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

        unset($box);
        unset($node);
    }

    /**
     * Parse the characters class/jobs levels and exp.
     */
    private function parseClassJobs()
    {
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getSpecial__ClassJobs();

        // class jobs
        $cj = [];
        foreach($box->find('.character__job') as $node)
        {
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
            $node = $this->getDocumentFromHtml($node->outertext);

            foreach($node->find('li') as $li)
            {
                // class name
                $name = trim($li->find('.character__job__name', 0)->plaintext);
                $nameIndex = strtolower($name);

                // get id
                $id = $this->xivdb->getRoleId($name);

                // level
                $level = trim($li->find('.character__job__level', 0)->plaintext);
                $level = ($level == '-') ? 0 : intval($level);

                // current exp
                list($current, $max) = explode('/', $li->find('.character__job__exp', 0)->plaintext);
                $current = ($current == '-') ? 0 : intval($current);
                $max = ($max == '-') ? 0 : intval($max);

                // store
                $cj[$nameIndex] = [
                    'id' => $id,
                    'name' => $name,
                    'level' => $level,
                    'exp' => [
                        'current' => $current,
                        'max' => $max,
                    ],
                ];
            }
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
        }

        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        $this->add('classjobs', $cj);
        unset($box);
    }

    /**
     * Parse the characters active class/job
     * THIS HAS TO RUN AFTER GEAR AS IT NEEDS
     * TO LOOK FOR SOUL CRYSTAL EQUIPPED
     */
    private function parseActiveClass()
    {
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        $box = $this->getDocumentFromClassname('.character__profile__detail', 0);
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        // level
        $level = trim($box->find('.character__class__data p', 0)->plaintext);
        $level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        // name
        $name = $box->find('.db-tooltip__item__category', 0)->plaintext;
        $name = explode("'", $name)[0];
        $name = str_ireplace(['Two-handed', 'One-handed'], null, $name);
        $name = trim($name);
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        // classjob id
        $id = $this->xivdb->getRoleId($name);

        // Handle jobs
        $gear = $this->get('gear');
        $soulcrystal = isset($gear['soulcrystal']) ? $gear['soulcrystal']['id'] : false;

        if ($soulcrystal) {
            $soulArray = [
                '67fd81c209e' => 19, // pld
                'a03321484cc' => 20, // mnk
                '2b81316eeed' => 21, // war
                'f6720135c8b' => 22, // drg
                '3e5b5adfe7b' => 23, // brd
                '9cca5eb0fd2' => 24, // whm
                'a4302cc8e2f' => 25, // blm
                'e1570c3d994' => 27, // smn
                'eb511e3871f' => 28, // sch
                'ec798591c4e' => 30, // nin
                'b95eca0caf9' => 31, // mch
                'b57f6b930d5' => 32, // drk
                'fe184c7b6e2' => 33, // ast
            ];

            $jobId = array_key_exists($soulcrystal, $soulArray);

            if ($jobId) {
                $jobId = $soulArray[$soulcrystal];
                $name = $this->xivdb->get('classjobs')[$jobId]['name_en'];
                $id = $jobId;
            }
        }

        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        $this->add('active_class', [
            'id' => $id,
            'level' => $level,
            'name' => $name,
        ]);

        unset($box);
    }

    /**
     * Parse stats
     */
    private function parseAttributes()
    {
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getSpecial__AttributesPart1();

        $stats = [];

        // attributes
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__param__list', 0)->find('tr') as $node) {
            $name = $node->find('th')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('td')->plaintext);
            $stats['attributes'][] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        // offensive properties
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__param__list', 1)->find('tr') as $node) {
            $name = $node->find('th')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('td')->plaintext);
            $stats['offensive'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        // defensive properties
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__param__list', 2)->find('tr') as $node) {
            $name = $node->find('th')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('td')->plaintext);
            $stats['defensive'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        // physical properties
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__param__list', 3)->find('tr') as $node) {
            $name = $node->find('th')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('td')->plaintext);
            $stats['physical'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        // mental properties
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__param__list', 4)->find('tr') as $node) {
            $name = $node->find('th')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('td')->plaintext);
            $stats['mental'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        $box = $this->getSpecial__AttributesPart2();

        // status resistances
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__param__list', 0)->find('tr') as $node) {
            $name = $node->find('th')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('td')->plaintext);
            $stats['resistances'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        $box = $this->getSpecial__AttributesPart3();

        // hp, mp, tp, cp, gp etc
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('li') as $node) {
            $name = $node->find('.character__param__text')->plaintext;
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->find('span')->plaintext);
            $stats['core'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        $box = $this->getSpecial__AttributesPart4();

        // elementals
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('li') as $node) {
            $name = explode('__', $node->innerHtml())[1];
            $name = explode(' ', $name)[0];
            $id = $this->xivdb->getBaseParamId($name);
            $value = intval($node->plaintext);
            $stats['elemental'][$name] = [
                'id' => $id,
                'name' => $name,
                'value' => $value
            ];
        }

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $this->add('stats', $stats);
        unset($box);
    }

    /**
     * Minions and Mounts
     */
    private function parseCollectables()
    {
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getSpecial__Collectables();
        if (!$box) {
            return;
        }

        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        if (!$box->find('.character__mounts', 0) || !$box->find('.character__minion', 0)) {
            return;
        }

        // get mounts
        $mounts = [];
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__mounts ul li') as $node) {
            $mounts[] = $this->fetchCollectable($node);
        }

        $this->add('mounts', $mounts);

        // get minions
        $minions = [];
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        foreach($box->find('.character__minion ul li') as $node) {
            $minions[] = $this->fetchCollectable($node);
        }

        $this->add('minions', $minions);

        // fin
        unset($box);
    }

    private function fetchCollectable($node) {
        $name = trim($node->find('.character__item_icon', 0)->getAttribute('data-tooltip'));
        $id = $this->xivdb->getMountId($name);
        $icon = $this->xivdb->getMountIcon($id);

        Logger::write(__CLASS__, __LINE__, $icon);
        $collectable = new Collectable();

        return $collectable
            ->setId($id)
            ->setName($name)
            ->setIcon($icon);
    }

    /**
     * Gear
     */
    private function parseEquipGear()
    {
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getSpecial__EquipGear();
        //$box = $this->getDocumentFromClassname('.character__content', 0);

        $gear = [];
        foreach($box->find('.item_detail_box') as $i => $node) {
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
            $name = $node->find('.db-tooltip__item__name')->plaintext;
            $id = explode('/', $node->find('.db-tooltip__bt_item_detail', 0)->find('a', 0)->getAttribute('href'))[5];

            // add mirage
            $mirageId = false;
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
            $mirageNode = $node->find('.db-tooltip__item__mirage');
            if ($mirageNode) {
                $mirageNode = $mirageNode->find('a', 0);
                if ($mirageNode) {
                    $mirageId = explode('/', $mirageNode->getAttribute('href'))[5];
                }
            }

            // add creator
            $creatorId = false;
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
            $creatorNode = $node->find('.db-tooltip__signature-character');
            if ($creatorNode) {
                $creatorNode = $creatorNode->find('a',0);
                if ($creatorNode) {
                    $creatorId = explode('/', $creatorNode->getAttribute('href'))[3];
                }
            }

            // add dye
            $dyeId = false;
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
            $dyeNode = $node->find('.stain');
            if ($dyeNode) {
                $dyeNode = $dyeNode->find('a',0);
                if ($dyeNode) {
                    $dyeId = explode('/', $dyeNode->getAttribute('href'))[5];
                }
            }

            // add materia
            $materia = [];
            $materiaNodes = $node->find('.db-tooltip__materia',0);
            if ($materiaNodes) {
                if ($materiaNodes = $materiaNodes->find('li')) {
                    foreach ($materiaNodes as $mnode) {
                        $mhtml = $mnode->find('.db-tooltip__materia__txt')->innerHtml();
                        if (!$mhtml) {
                            continue;
                        }

                        list($mname, $mvalue) = explode('<br>', html_entity_decode($mhtml));

                        $materia[] = [
                            'name' => trim(strip_tags($mname)),
                            'value' => trim(strip_tags($mvalue)),
                        ];
                    }
                }
            }

            // slot conditions, based on category
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
            $slot = $node->find('.db-tooltip__item__category', 0)->plaintext;

            // if this is first item, its main-hand
            $slot = ($i == 0) ? 'mainhand' : strtolower($slot);

            // if item is secondary tool or shield, its off-hand
            $slot = (stripos($slot, 'secondary tool') !== false) ? 'offhand' : $slot;
            $slot = ($slot == 'shield') ? 'offhand' : $slot;

            // if item is a ring, check if its ring 1 or 2
            if ($slot == 'ring') {
                $slot = isset($gear['ring1']) ? 'ring2' : 'ring1';
            }

            $slot = str_ireplace(' ', '', $slot);
            $gear[$slot] = [
                'id' => $id,
                'name' => $name,
                'mirage_id' => $mirageId,
                'creator_id' => $creatorId,
                'dye_id' => $dyeId,
                'materia' => $materia,
            ];
        }

        $this->add('gear', $gear);
        unset($box);
    }
}
