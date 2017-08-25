<?php

namespace Lodestone\Entities\FreeCompany;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Traits\CharacterListTrait,
    Entities\Traits\ListTrait
};

/**
 * Class FreeCompany
 *
 * @package Lodestone\Entities\Character
 */
class FreeCompanyMembers extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
}