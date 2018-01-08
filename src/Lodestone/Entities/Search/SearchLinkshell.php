<?php

namespace Lodestone\Entities\Search;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Traits\LinkshellListTrait,
    Entities\Traits\ListTrait
};

/**
 * Class SearchLinkshell
 *
 * @package Lodestone\Entities\Search
 */
class SearchLinkshell extends AbstractEntity
{
    use ListTrait;
    use LinkshellListTrait;
}