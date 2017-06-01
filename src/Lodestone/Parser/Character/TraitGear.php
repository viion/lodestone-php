<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark;
use Lodestone\Dom\{
    Document,
    Element
};

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