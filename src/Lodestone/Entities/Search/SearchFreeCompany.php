<?php

namespace Lodestone\Entities\Search;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Traits\FreeCompanyListTrait,
    Entities\Traits\ListTrait
};

/**
 * Class SearchFreeCompany
 *
 * @package Lodestone\Entities\Search
 */
class SearchFreeCompany extends AbstractEntity
{
    use ListTrait;
    use FreeCompanyListTrait;
}