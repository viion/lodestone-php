<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Game\ClassJobsData;
use Lodestone\Modules\Logging\Benchmark;
use Lodestone\Entities\Character\{
    ClassJob,
    Item
};


/**
 * Class TraitClassJobActive
 *
 * Handles parsing current active class/job,
 * this requires that "TraitGear" has been run.
 *
 * @package Lodestone\Parser\Character
 */
trait TraitClassJobActive
{
    /**
     * Get the characters active class/job
     *
     * THIS HAS TO RUN AFTER GEAR AS IT NEEDS
     * TO LOOK FOR SOUL CRYSTAL EQUIPPED
     */
    protected function parseActiveClass()
    {
        Benchmark::start(__METHOD__,__LINE__);

        // get main hand previously parsed
        /** @var Item $mainhand */
        $mainhand = $this->profile->getGear('mainhand');
        $name = explode("'", $mainhand->getCategory())[0];

        // get class job id from the main-hand category name
        $classjobs = new ClassJobsData();
        $ids = $classjobs->getClassJobIds($name);

        // set id and name
        $role = $this->profile->getClassjob($ids->key);
        $role = $role ? clone $role : false;
    
        $this->profile->setActiveClassJob($role);

        // save
        Benchmark::finish(__METHOD__,__LINE__);
    }
}