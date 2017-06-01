<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entities\Character\{ClassJob,Item};
use Lodestone\Modules\Benchmark;

/**
 * Class TraitAttributes
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
        $mainhand = $this->profile->gear['mainhand'];

        // get class job id from mainhand category name
        $id = $this->xivdb->getRoleId($mainhand->category);

        // get classjob from the recorded class jobs and clone it
        /** @var ClassJob $role */
        $role = clone $this->profile->classjobs[$id];
        $name = $role->name;

        // Handle jobs
        $soulcrystal = isset($this->profile->gear['soulcrystal'])
            ? $this->profile->gear['soulcrystal']->id
            : false;

        // if a soul crystal exists, get job id
        if ($soulcrystal) {
            $soulArray = [
                '67fd81c209e' => 19, // pld
                'a03321484cc' => 20, // mnk
                '2b81316eeed' => 21, // war
                'f6720135c8b' => 22, // drg
                '3e5b5adfe7b' => 23, // brd
                '9cca5eb0fd2' => 24, // whm
                'a4302cc8e2f' => 25, // blm
                'e1570c3d994' => 27, // smn
                'eb511e3871f' => 28, // sch
                'ec798591c4e' => 30, // nin
                'b95eca0caf9' => 31, // mch
                'b57f6b930d5' => 32, // drk
                'fe184c7b6e2' => 33, // ast
            ];

            $jobId = array_key_exists($soulcrystal, $soulArray);
            if ($jobId) {
                // convert soul id to job id
                $jobId = $soulArray[$soulcrystal];

                // set real name and id
                $name = $this->xivdb->get('classjobs')[$jobId]['name_en'];
                $id = $jobId;
            }
        }

        // set id and name
        $role
            ->setId($id)
            ->setName($name);

        // save
        $this->profile->activeClassJob = $role;
        Benchmark::finish(__METHOD__,__LINE__);
    }
}