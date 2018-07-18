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
        $mainhand = $this->results->getGear('mainhand');
        $name = explode("'", $mainhand->getCategory())[0];    
        $this->results->setActiveClassJob($name);

        // save
        Benchmark::finish(__METHOD__,__LINE__);
    }
}