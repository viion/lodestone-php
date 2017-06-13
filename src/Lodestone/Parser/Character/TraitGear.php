<?php

namespace Lodestone\Parser\Character;

use Lodestone\Dom\NodeList,
    Lodestone\Entities\Character\Item,
    Lodestone\Modules\Benchmark;

/**
 * Class TraitGear
 *
 * Handles parsing currently equipped character gear.
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

        // loop
        foreach($box->find('.item_detail_box') as $i => $node) {
            $item = new Item();

            /** @var NodeList $node */
            $html = $this->getArrayFromHtml($node->innerHtml());

            // get name
            $name = $this->getArrayFromRange('db-tooltip__item__name', 1, $html);

            // If this slot has no item name html
            // it's safe to assume empty slot
            if (!$name) {
                continue;
            }

            $name = strip_tags($name[1]);
            $item->setName($name);

            // get real id from xivdb
            $id = $this->xivdb->getItemId($name);
            $item->setId($id);

            // get lodestone id
            $lodestoneId = $this->getArrayFromRange('db-tooltip__bt_item_detail', 1, $html);
            $lodestoneId = trim(explode('/', $lodestoneId[1])[5]);
            $item->setLodestoneId($lodestoneId);

            // get category
            $category = $this->getArrayFromRange('db-tooltip__item__category', 1, $html);
            $category = trim(strip_tags($category[1]));
            $item->setCategory($category);

            // get slot from category
            $slot = ($i == 0) ? 'mainhand' : strtolower($category);

            // if item is secondary tool or shield, its off-hand
            $slot = (stripos($slot, 'secondary tool') !== false) ? 'offhand' : $slot;
            $slot = ($slot == 'shield') ? 'offhand' : $slot;

            // if item is a ring, check if its ring 1 or 2
            if ($slot == 'ring') {
                $slot = isset($gear['ring1']) ? 'ring2' : 'ring1';
            }

            // save slot
            $slot = str_ireplace(' ', '', $slot);
            $item->setSlot($slot);

            // add mirage
            $mirage = $this->getArrayFromRange('db-tooltip__item__mirage', 8, $html);
            if ($mirage) {
                $mirage = explode("/", $mirage[6]);
                $mirage = trim($mirage[5]);
                $item->setMirageId($mirage);
            }


            // add creator
            $creator = $this->getArrayFromRange('db-tooltip__signature-character', 4, $html);
            if ($creator) {
                $creator = explode("/", $creator[1]);
                $creator = trim($creator[3]);
                $item->setCreatorId($creator);
            }

            // add dye
            $dye = $this->getArrayFromRange('class="stain"', 4, $html);
            if ($dye) {
                $dye = explode("/", $dye[1]);
                $dye = trim($dye[5]);
                $item->setDyeId($dye);
            }

            // add materia
            // todo : Improve, adds on avg 18ms - Difficulty is due to
            // todo       unknown length of html (1-5 materia slots)
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

            $this->profile->addGear($slot, $item);
        }

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }
}