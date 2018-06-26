<?php

namespace Lodestone\Entities\Search;

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
