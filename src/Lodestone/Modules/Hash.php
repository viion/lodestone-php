<?php

namespace Lodestone\Modules;

use Lodestone\Entities\Character\CharacterProfile;

/**
 * Class Hash
 *
 * Generates SHA1 for different types of objects
 *
 * @package Lodestone\Modules
 */
class Hash
{
    /**
     * Get the sha1 for a character
     *
     * This will remove some data which can change without
     * the character being logged in or doing anything. This
     * means the hash will stay consistent throughout the
     * games lifetime.
     *
     * @param CharacterProfile $profile
     * @return string
     */
    public function hashCharacter(CharacterProfile $profile)
    {
        $data = $profile->toArray();

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

        return sha1(serialize($data));
    }
}