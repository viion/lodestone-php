<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark,
    Lodestone\Entities\Character\Collectable;

/**
 * Class TraitCollectables
 *
 * Handles parsing collectables; Minions and Mounts
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

        // check mounts and minions exist (new characters don't have them)
        if (!$box || !$box->find('.character__mounts', 0) || !$box->find('.character__minion', 0)) {
            return;
        }

        // get mounts
        foreach($box->find('.character__mounts ul li') as &$node) {
            $this->profile
                ->getCollectables()
                ->addMount($this->parseCollectableCommon($node, 'Mount'));
        }

        // get minions
        foreach($box->find('.character__minion ul li') as &$node) {
            $this->profile
                ->getCollectables()
                ->addMinion($this->parseCollectableCommon($node, 'Minion'));
        }

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
    protected function parseCollectableCommon($node, $type)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $name = trim($node->find('.character__item_icon', 0)->getAttribute('data-tooltip'));

        $id = $this->xivdb->{'get'. $type .'Id'}($name);

        // todo - get icon from XIVDB and attach it

        $collectable = new Collectable();
        $collectable
            ->setId($id)
            ->setName($name);

        unset($node);
        Benchmark::finish(__METHOD__,__LINE__);

        return $collectable;
    }
}