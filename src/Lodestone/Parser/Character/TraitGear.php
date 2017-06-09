<?php

namespace Lodestone\Parser\Character;

use Lodestone\Dom\NodeList;
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
        $a = round(microtime(true) * 1000);

        print_r("\n");
        Benchmark::start(__METHOD__,__LINE__);

        $box = $this->getSpecial__EquipGear();

        // loop
        foreach($box->find('.item_detail_box') as $i => $node) {
            $item = new Item();

            /** @var NodeList $node */
            $html = $this->getPlaintextHtmlArray($node->innerHtml());

            // get name
            $name = $this->findEntryFromHtmlRange($html, 'db-tooltip__item__name', 1);

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
            $lodestoneId = $this->findEntryFromHtmlRange($html, 'db-tooltip__bt_item_detail', 1);
            $lodestoneId = trim(explode('/', $lodestoneId[1])[5]);
            $item->setLodestoneId($lodestoneId);

            // get category
            $category = $this->findEntryFromHtmlRange($html, 'db-tooltip__item__category', 1);
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
            $mirage = $this->findEntryFromHtmlRange($html, 'db-tooltip__item__mirage', 8);
            if ($mirage) {
                $mirage = explode("/", $mirage[6]);
                $mirage = trim($mirage[5]);
                $item->setMirageId($mirage);
            }


            // add creator
            $creator = $this->findEntryFromHtmlRange($html, 'db-tooltip__signature-character', 4);
            if ($creator) {
                $creator = explode("/", $creator[1]);
                $creator = trim($creator[3]);
                $item->setCreatorId($creator);
            }

            // add dye
            $dye = $this->findEntryFromHtmlRange($html, 'class="stain"', 4);
            if ($dye) {
                $dye = explode("/", $dye[1]);
                $dye = trim($dye[3]);
                $item->setDyeId($dye);
            }

            // tricky as there can be multiple, will have to do a "from - to" range, similar
            // to "trim"
            //$materia = $this->findEntryFromHtmlRange($html, 'class="stain"', 4);

            /*
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
            */

            // save

            $this->profile->gear[$slot] = $item;
        }

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);

        $b = round(microtime(true) * 1000) - $a;
        file_put_contents(__DIR__.'/gear.log', $b . "\n", FILE_APPEND);

        print_r('X = '. $b . "\n");
        print_r("\n");

        die;
    }
}