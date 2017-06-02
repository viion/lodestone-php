<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entities\Character\ClassJob;
use Lodestone\Modules\Benchmark;

/**
 * Class TraitClassJob
 *
 * @package Lodestone\Parser\Character
 */
trait TraitClassJob
{
    /**
     * Parse the characters class/jobs levels and exp.
     */
    protected function parseClassJob()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getSpecial__ClassJobs();

        // class jobs
        foreach($box->find('.character__job') as $node)
        {
            $node = $this->getDocumentFromHtml($node->outertext);

            // loop through roles
            foreach($node->find('li') as $li)
            {
                $role = new ClassJob();

                // class name
                $name = trim($li->find('.character__job__name', 0)->plaintext);
                $role->setName($name);

                // get id
                $id = $this->xivdb->getClassJobId($name);
                $role->setId($id);

                // level
                $level = trim($li->find('.character__job__level', 0)->plaintext);
                $level = ($level == '-') ? 0 : intval($level);
                $role->setLevel($level);

                // current exp
                list($current, $max) = explode('/', $li->find('.character__job__exp', 0)->plaintext);
                $current = ($current == '-') ? 0 : intval($current);
                $max = ($max == '-') ? 0 : intval($max);

                $role
                    ->setExpLevel($current)
                    ->setExpLevelMax($max)
                    ->setExpLevelTogo($max - $current)
                    ->setExpTotal($this->xivdb->getExpGained($level, $current))
                    ->setExpTotalMax($this->xivdb->getTotalExp())
                    ->setExpTotalTogo($role->expTotalMax - $role->expTotal);

                // save
                $this->profile->classjobs[$id] = $role;
            }
        }

        unset($box);
        unset($node);

        Benchmark::finish(__METHOD__,__LINE__);
    }
}