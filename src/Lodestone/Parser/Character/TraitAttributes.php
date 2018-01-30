<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entities\Character\Attribute,
    Lodestone\Modules\Logging\Benchmark;
use Lodestone\Dom\{
    Document,
    Element
};

/**
 * Class TraitAttributes
 *
 * Handles parsing characters attributes
 *
 * @package Lodestone\Parser\Character
 */
trait TraitAttributes
{
    /**
     * Parse attributes
     */
    protected function parseAttributes()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__AttributesPart1();

        // fetches:
        // * attributes
        // * offensive, defensive, physical and mental properties
        for ($i = 0; $i < 5; $i++) {
            foreach($box->find('.character__param__list', $i)->find('tr') as $node) {
                $this->profile->addAttribute($this->parseAttributeCommon($node));
            }
        }

        $box = $this->getSpecial__AttributesPart3();

        // hp, mp, tp, cp, gp etc
        foreach($box->find('li') as $node) {
            $attribute = new Attribute();
            $attribute
                ->setName($node->find('.character__param__text')->plaintext)
                ->setValue(intval($node->find('span')->plaintext));

            $this->profile->addAttribute($attribute);
        }

        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Nearly all attributes use this
     *
     * @param $node
     * @return Attribute
     */
    protected function parseAttributeCommon($node)
    {
        $attribute = new Attribute();
        $attribute
            ->setName($node->find('th')->plaintext)
            ->setValue(intval($node->find('td')->plaintext));

        unset($node);
        return $attribute;
    }
}