<?php

namespace Lodestone\Parser\Character;

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
        $rolename = explode("'", $mainhand->getCategory())[0];

        // get class job id from the main-hand category name
        $id = $this->xivdb->getClassJobId($rolename);

        // Get soul crystal
        $soulcrystal = $this->profile->getGear('soulcrystal');

        // if a soul crystal exists, get job id
        if ($soulcrystal && $soulcrystal->isset()) {
            // if soul crystal exists, convert role name
            $rolename = $this->xivdb->convertClassToJob($rolename);

            // get the classjob id
            $id = $this->xivdb->getClassJobId($rolename, false);
        }

        //print_r($id);
        //die;

        // set id and name
        $role = $this->profile->getClassjob($id);
        $role = $role ? clone $role : false;
    
        $this->profile->setActiveClassJob($role);

        // save
        Benchmark::finish(__METHOD__,__LINE__);
    }
}