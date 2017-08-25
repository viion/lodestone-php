<?php

namespace Lodestone\Entities\Search;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Traits\CharacterListTrait,
    Entities\Traits\ListTrait
};

/**
 * Class SearchCharacter
 *
 * @package Lodestone\Entities\Search
 */
class SearchCharacter extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
}