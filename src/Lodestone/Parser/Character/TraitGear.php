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
        $a = round(microtime(true) * 1000);

        print_r("\n");
        Benchmark::start(__METHOD__,__LINE__);

        $box = $this->getSpecial__EquipGear();

        // loop
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

            // category - only important on first one as it's used
            // to determine active class
            if ($i == 0) {
                $category = $node->find('.db-tooltip__item__category')->plaintext;
                $category = explode("'", $category)[0];
                $category = str_ireplace(['Two-handed', 'One-handed'], null, $category);
                $item->setCategory(trim($category));
            }




            /*

            // add mirage
            $mirageNode = $node->find('.db-tooltip__item__mirage');
            if ($mirageNode) {
               $mirageNode = $mirageNode->find('a', 0);
               if ($mirageNode) {
                   $mirageId = explode('/', $mirageNode->getAttribute('href'))[5];
                   $item->setMirageId($mirageId);
                   // todo - parse name and convert to ingame id

               }
            }

            // add creator
            $creatorNode = $node->find('.db-tooltip__signature-character');
            if ($creatorNode) {
               $creatorNode = $creatorNode->find('a',0);
               if ($creatorNode) {
                   $creatorId = explode('/', $creatorNode->getAttribute('href'))[3];
                   $item->setCreatorId($creatorId);
               }
            }

            // add dye
            $dyeNode = $node->find('.stain');
            if ($dyeNode) {
               $dyeNode = $dyeNode->find('a',0);
               if ($dyeNode) {
                   $dyeId = explode('/', $dyeNode->getAttribute('href'))[5];
                   $item->setDyeId($dyeId);
                   // todo - parse name and convert to ingame id
               }
            }

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

        $b = round(microtime(true) * 1000) - $a;
        file_put_contents(__DIR__.'/gear.log', $b . "\n", FILE_APPEND);

        print_r('X = '. $b . "\n");
        print_r("\n");
    }
}