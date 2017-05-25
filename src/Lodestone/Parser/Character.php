<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Benchmark;
use Lodestone\Entities\Character\{
    Collectable,
    Profile
};
use Lodestone\Modules\{
    Logger,
    XIVDB
};

/**
 * Class Character
 *
 * @package Lodestone\Parser
 */
class Character extends ParserHelper
{
    use CharacterProfileTrait;

    /** @var XIVDB $xivdb */
    private $xivdb;

    /**
     * @var Profile $profile
     */
    private $profile;

    /**
     * Character constructor.
     * @param int|null $id
     */
    function __construct(int $id)
    {
        $this->xivdb = new XIVDB();
        $this->profile = new Profile();
        $this->profile->setId($id);
    }

    /**
     * @param bool $hash
     * @return bool|Profile
     */
    public function parse()
    {
        // setup html
        $this->ensureHtml();
        $this->html = $this->trim($this->html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($this->html);

        // parse
        $started = microtime(true);
        Benchmark::start(__METHOD__,__LINE__);
        $this->parseProfile();
        $this->parseClassJobs();
        $this->parseAttributes();
        $this->parseCollectables();
        $this->parseEquipGear();
        $this->parseActiveClass();
        Benchmark::finish(__METHOD__,__LINE__);
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', round(microtime(true) - $started, 3)));

        // generate hash
        $this->profile->generateHash();

        return $this->profile->toArray();
    }

    /**
     * Parse the characters class/jobs levels and exp.
     */
    private function parseClassJobs()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__ClassJobs();

        // class jobs
        $cj = [];
        foreach($box->find('.character__job') as $node)
        {
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

        }

        $this->add('classjobs', $cj);
        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse the characters active class/job
     * THIS HAS TO RUN AFTER GEAR AS IT NEEDS
     * TO LOOK FOR SOUL CRYSTAL EQUIPPED
     */
    private function parseActiveClass()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getDocumentFromClassname('.character__profile__detail', 0);

        // level
        $level = trim($box->find('.character__class__data p', 0)->plaintext);
        $level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);

        // name
        $name = $box->find('.db-tooltip__item__category', 0)->plaintext;
        $name = explode("'", $name)[0];
        $name = str_ireplace(['Two-handed', 'One-handed'], null, $name);
        $name = trim($name);

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


        $this->add('active_class', [
            'id' => $id,
            'level' => $level,
            'name' => $name,
        ]);

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Parse stats
     */
    private function parseAttributes()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__AttributesPart1();

        $stats = [];

        // attributes
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

        $this->add('stats', $stats);
        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Minions and Mounts
     */
    private function parseCollectables()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__Collectables();
        if (!$box) {
            return;
        }

        if (!$box->find('.character__mounts', 0) || !$box->find('.character__minion', 0)) {
            return;
        }

        // get mounts
        $mounts = [];
        foreach($box->find('.character__mounts ul li') as &$node) {
            $mounts[] = $this->fetchCollectable($node, 'Mount');
        }

        $this->add('mounts', $mounts);

        // get minions
        $minions = [];
        foreach($box->find('.character__minion ul li') as &$node) {
            $minions[] = $this->fetchCollectable($node, 'Minion');
        }

        $this->add('minions', $minions);

        // fin
        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * @param $node
     * @param $type
     * @return Collectable
     */
    private function fetchCollectable(&$node, $type)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $name = trim($node->find('.character__item_icon', 0)->getAttribute('data-tooltip'));
        $id = $this->xivdb->{'get'. $type .'Id'}($name);
        $icon = $this->xivdb->{'get'. $type .'Icon'}($id);

        $collectable = new Collectable();

        return $collectable
            ->setId($id)
            ->setName($name)
            ->setIcon($icon);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Gear
     */
    private function parseEquipGear()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__EquipGear();
        //$box = $this->getDocumentFromClassname('.character__content', 0);

        $gear = [];
        foreach($box->find('.item_detail_box') as $i => $node) {

            $name = $node->find('.db-tooltip__item__name')->plaintext;
            $id = explode('/', $node->find('.db-tooltip__bt_item_detail', 0)->find('a', 0)->getAttribute('href'))[5];

            // add mirage
            $mirageId = false;

            $mirageNode = $node->find('.db-tooltip__item__mirage');
            if ($mirageNode) {
                $mirageNode = $mirageNode->find('a', 0);
                if ($mirageNode) {
                    $mirageId = explode('/', $mirageNode->getAttribute('href'))[5];
                }
            }

            // add creator
            $creatorId = false;

            $creatorNode = $node->find('.db-tooltip__signature-character');
            if ($creatorNode) {
                $creatorNode = $creatorNode->find('a',0);
                if ($creatorNode) {
                    $creatorId = explode('/', $creatorNode->getAttribute('href'))[3];
                }
            }

            // add dye
            $dyeId = false;

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
        Benchmark::finish(__METHOD__,__LINE__);
    }
}
