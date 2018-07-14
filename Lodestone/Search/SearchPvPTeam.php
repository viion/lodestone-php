<?php

namespace Lodestone\Search;

use Lodestone\{
    Entities\AbstractEntity,
    Entities\Traits\PvPTeamListTrait,
    Entities\Traits\ListTrait
};

/**
 * Class SearchPvPTeam
 *
 * @package Lodestone\Entities\Search
 */
class SearchPvPTeam extends AbstractEntity
{
    use ListTrait;
    use PvPTeamListTrait;
}
