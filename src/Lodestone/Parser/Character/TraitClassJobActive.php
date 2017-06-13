<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark;
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
        $role = explode("'", $mainhand->getCategory())[0];

        // get class job id from mainhand category name
        $id = $this->xivdb->getClassJobId($role);

        if (!$id) {
            $role = new ClassJob();
            $role->setName($mainhand->getCategory());
            $this->profile->setActiveClassJob($role);

            Benchmark::finish(__METHOD__,__LINE__);
            return;
        }

        // get classjob from the recorded class jobs and clone it
        /** @var ClassJob $role */
        $role = clone $this->profile->getClassjob($id);
        $name = $role->getName();

        // Handle jobs
        $soulcrystal = isset($this->profile->gear['soulcrystal'])
            ? $this->profile->gear['soulcrystal']->id
            : false;

        // if a soul crystal exists, get job id
        if ($soulcrystal) {
            $soulArray = [
                '4542' => 19, // pld
                '4543' => 20, // mnk
                '4544' => 21, // war
                '4545' => 22, // drg
                '4546' => 23, // brd
                '4547' => 24, // whm
                '4548' => 25, // blm
                '4549' => 27, // smn
                '4550' => 28, // sch
                '7886' => 30, // nin
                '8574' => 31, // mch
                '8839' => 32, // drk
                '8840' => 33, // ast
            ];

            $jobId = array_key_exists($soulcrystal, $soulArray);
            if ($jobId) {
                // convert soul id to job id
                $jobId = $soulArray[$soulcrystal];

                // set real name and id
                if ($jobId) {
                    $name = $this->xivdb->getClassJobName($jobId);
                    $id = $jobId;
                }

            }
        }

        // set id and name
        $role
            ->setId($id)
            ->setName($name);

        // save
        $this->profile->setActiveClassJob($role);
        Benchmark::finish(__METHOD__,__LINE__);
    }
}