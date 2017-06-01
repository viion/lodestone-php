<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark;
use Lodestone\Entities\Character\Collectable;

/**
 * Class TraitAttributes
 *
 * @package Lodestone\Parser\Character
 */
trait TraitCollectables
{
    /**
     * Parse minions and mounts
     */
    protected function parseCollectables()
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
            $mounts[] = $this->parseCollectableCommon($node, 'Mount');
        }

        $this->add('mounts', $mounts);

        // get minions
        $minions = [];
        foreach($box->find('.character__minion ul li') as &$node) {
            $minions[] = $this->parseCollectableCommon($node, 'Minion');
        }

        $this->add('minions', $minions);

        // fin
        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Common html parser for collectables based on type
     *
     * @param $node
     * @param $type
     * @return mixed
     */
    protected function parseCollectableCommon(&$node, $type)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $name = trim($node->find('.character__item_icon', 0)->getAttribute('data-tooltip'));
        $id = $this->xivdb->{'get'. $type .'Id'}($name);
        $icon = $this->xivdb->{'get'. $type .'Icon'}($id);

        $collectable = new Collectable();
        Benchmark::finish(__METHOD__,__LINE__);

        return $collectable
            ->setId($id)
            ->setName($name)
            ->setIcon($icon);
    }
}