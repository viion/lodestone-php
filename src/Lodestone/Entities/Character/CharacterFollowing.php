<?php

namespace Lodestone\Entities\Character;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Traits\CharacterListTrait,
    Entities\Traits\ListTrait
};

/**
 * Class Profile
 *
 * @package Lodestone\Entities\Character
 */
class CharacterFollowing extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
}