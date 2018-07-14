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
class CharacterFriends extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
}