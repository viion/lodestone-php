<?php

namespace Lodestone\Parser\Character;

use Lodestone\Modules\Benchmark;
use Lodestone\Dom\{
    Document,
    Element
};

/**
 * Class TraitAttributes
 *
 * @package Lodestone\Parser\Character
 */
trait TraitClassJobActive
{
    /**
     * Parse the characters active class/job
     * THIS HAS TO RUN AFTER GEAR AS IT NEEDS
     * TO LOOK FOR SOUL CRYSTAL EQUIPPED
     */
    protected function parseActiveClass()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $box = $this->getDocumentFromClassname('.character__profile__detail', 0);
        // level
        $level = trim($box->find('.character__class__data p', 0)->plaintext);
        $level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);
        // name
        $name = $box->find('.db-tooltip__item__category', 0)->plaintext;
        $name = explode("'", $name)[0];
        $name = str_ireplace(['Two-handed', 'One-handed'], null, $name);
        $name = trim($name);
        // classjob id
        $id = $this->xivdb->getRoleId($name);
        // Handle jobs
        $gear = $this->get('gear');
        $soulcrystal = isset($gear['soulcrystal']) ? $gear['soulcrystal']['id'] : false;
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
                $jobId = $soulArray[$soulcrystal];
                $name = $this->xivdb->get('classjobs')[$jobId]['name_en'];
                $id = $jobId;
            }
        }
        $this->add('active_class', [
            'id' => $id,
            'level' => $level,
            'name' => $name,
        ]);
        unset($box);
        Benchmark::finish(__METHOD__,__LINE__);
    }
}