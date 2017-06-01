<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entities\Character\Item;
use Lodestone\Modules\Benchmark;

/**
 * Class TraitAttributes
 *
 * @package Lodestone\Parser\Character
 */
trait TraitGear
{
    /**
     * Parse gear for the current role
     */
    protected function parseEquipGear()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__EquipGear();

        foreach($box->find('.item_detail_box') as $i => $node) {
            $item = new Item();

            // name and id
            $name = $node->find('.db-tooltip__item__name')->plaintext;
            $lodestoneId = explode('/', $node->find('.db-tooltip__bt_item_detail', 0)->find('a', 0)->getAttribute('href'))[5];
            $id = $this->xivdb->getItemId($name);

            $item
                ->setName($name)
                ->setLodestoneId($lodestoneId)
                ->setId($id);

            // add mirage
            $mirageId = false;
            $mirageNode = $node->find('.db-tooltip__item__mirage');
            if ($mirageNode) {
                $mirageNode = $mirageNode->find('a', 0);
                if ($mirageNode) {
                    $mirageId = explode('/', $mirageNode->getAttribute('href'))[5];
                    // todo - parse name and convert to ingame id
                }
            }
            $item->setMirageId($mirageId);

            // add creator
            $creatorId = false;
            $creatorNode = $node->find('.db-tooltip__signature-character');
            if ($creatorNode) {
                $creatorNode = $creatorNode->find('a',0);
                if ($creatorNode) {
                    $creatorId = explode('/', $creatorNode->getAttribute('href'))[3];
                }
            }
            $item->setCreatorId($creatorId);

            // add dye
            $dyeId = false;
            $dyeNode = $node->find('.stain');
            if ($dyeNode) {
                $dyeNode = $dyeNode->find('a',0);
                if ($dyeNode) {
                    $dyeId = explode('/', $dyeNode->getAttribute('href'))[5];
                    // todo - parse name and convert to ingame id
                }
            }
            $item->setDyeId($dyeId);

            // add materia
            $materiaNodes = $node->find('.db-tooltip__materia',0);
            if ($materiaNodes) {
                if ($materiaNodes = $materiaNodes->find('li')) {
                    foreach ($materiaNodes as $mnode) {
                        $mhtml = $mnode->find('.db-tooltip__materia__txt')->innerHtml();
                        if (!$mhtml) {
                            continue;
                        }

                        list($mname, $mvalue) = explode('<br>', html_entity_decode($mhtml));

                        $item->addMateria([
                            'name' => trim(strip_tags($mname)),
                            'value' => trim(strip_tags($mvalue)),
                        ]);
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

            $item->setSlot($slot);

            // save
            $slot = str_ireplace(' ', '', $slot);
            $this->profile->gear[$slot] = $item;
        }

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }
}