<?php

namespace Lodestone\Parser\Character;

/**
 * Class HashTrait
 *
 * @package Lodestone\Entities\Character
 */
trait Hash
{
    /**
     * Generate a sha1 hash of this character
     */
    public function hash()
    {
        $data = $this->profile->toArray();

        // remove hash, obvs (its blank anyway)
        unset($data['hash']);

        // remove images, urls can change
        unset($data['avatar']);
        unset($data['portrait']);
        unset($data['guardian']['icon']);
        unset($data['city']['icon']);
        unset($data['grandcompany']['icon']);

        // remove free company id, being kicked
        // should not generate a new hash
        unset($data['freecompany']);

        // remove biography as this is too "open"
        // and could become malformed easily.
        unset($data['biography']);

        // remove stats, SE can change the formula
        unset($data['stats']);

        //print_r($data); die;

        $this->hash = sha1(serialize($data));
    }
}