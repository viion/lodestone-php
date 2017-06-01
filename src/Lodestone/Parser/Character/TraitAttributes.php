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
trait TraitAttributes
{
    /**
     * Parse attributes
     */
    protected function parseAttributes()
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
}