<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entities\Character\Attribute;
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
trait TraitAttributes
{
    /**
     * Parse attributes
     */
    protected function parseAttributes()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__AttributesPart1();

        // attributes
        foreach($box->find('.character__param__list', 0)->find('tr') as $node) {
            $this->profile->attributes[] = $this->parseAttributeCommon($node);
        }

        // offensive properties
        foreach($box->find('.character__param__list', 1)->find('tr') as $node) {
            $this->profile->attributes[] = $this->parseAttributeCommon($node);
        }

        // defensive properties
        foreach($box->find('.character__param__list', 2)->find('tr') as $node) {
            $this->profile->attributes[] = $this->parseAttributeCommon($node);
        }

        // physical properties
        foreach($box->find('.character__param__list', 3)->find('tr') as $node) {
            $this->profile->attributes[] = $this->parseAttributeCommon($node);
        }

        // mental properties
        foreach($box->find('.character__param__list', 4)->find('tr') as $node) {
            $this->profile->attributes[] = $this->parseAttributeCommon($node);
        }

        $box = $this->getSpecial__AttributesPart2();

        // status resistances
        foreach($box->find('.character__param__list', 0)->find('tr') as $node) {
            $this->profile->attributes[] = $this->parseAttributeCommon($node);
        }

        $box = $this->getSpecial__AttributesPart3();

        // hp, mp, tp, cp, gp etc
        foreach($box->find('li') as $node) {
            $attribute = new Attribute();
            $attribute
                ->setName($node->find('.character__param__text')->plaintext)
                ->setId($this->xivdb->getBaseParamId($attribute->name))
                ->setValue(intval($node->find('span')->plaintext));

            $this->profile->attributes[] = $attribute;
        }

        $box = $this->getSpecial__AttributesPart4();

        // elemental
        foreach($box->find('li') as $node) {
            $name = explode('__', $node->innerHtml())[1];
            $name = explode(' ', $name)[0];
            $name = ucwords($name);

            $attribute = new Attribute();
            $attribute
                ->setName($name)
                ->setId($this->xivdb->getBaseParamId($attribute->name))
                ->setValue(intval($node->plaintext));

            $this->profile->attributes[] = $attribute;
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
    protected function parseAttributeCommon(&$node)
    {
        $attribute = new Attribute();
        $attribute
            ->setName($node->find('th')->plaintext)
            ->setId($this->xivdb->getBaseParamId($attribute->name))
            ->setValue(intval($node->find('td')->plaintext));

        return $attribute;
    }
}