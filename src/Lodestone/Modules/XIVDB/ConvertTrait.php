<?php

namespace Lodestone\Modules\XIVDB;

/**
 * Class DataTrait
 *
 * @package Lodestone\Modules
 */
trait ConvertTrait
{
    /**
     * Convert a JOB name to a CLASS name,
     * this is hard coded and it should never
     * need updating as SE have not added
     * new classes, only jobs.
     *
     * To update, take the name from: http://api.xivdb.com/data/classjobs?pretty=1
     * use lower case.
     *
     * The reason this is done is because you do not
     * level a "JOB" if it has a "CLASS", even when
     * Paladin is equip you always level Gladiator.
     *
     * In future, existing classes may be ditched
     * in favor of jobs, even if the job is "not unlocked"
     *
     * @param $name
     */
    protected function convertJobToClass($name)
    {
        $list = [
            'paladin' => 'gladiator',
            'monk' => 'pugilist',
            'warrior' => 'marauder',
            'dragoon' => 'lancer',
            'bard' => 'archer',
            'white mage' => 'conjurer',
            'black mage' => 'thaumaturge',
            'summoner' => 'arcanist',
            'scholar' => 'arcanist',
            'ninja' => 'rogue',
        ];

        return str_ireplace(array_keys($list), $list, strtolower($name));
    }
}
