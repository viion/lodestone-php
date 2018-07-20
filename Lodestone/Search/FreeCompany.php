<?php

namespace Lodestone\Search;

/**
 * Class Search
 *
 * @package Lodestone\SearchFreeCompany
 */
class FreeCompany
{
    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(string $html)
    {
        preg_match_all(
            '/<div class="parts__total">(?<total>\d*).*<\/div>.*<li class="btn__pager__current">(Page |Seite )*(?<pageCurrent>\d*)[^\d]*(?<pageTotal>\d*).*<\/li>/mis',
            $html,
            $pages,
            PREG_SET_ORDER
        );
        $this->results = [
            'pageCurrent'=>$pages[0]['pageCurrent'],
            'pageTotal'=>$pages[0]['pageTotal'],
            'total'=>$pages[0]['total'],
        ];
        preg_match_all(
            '/<div class="entry"><a href="\/lodestone\/freecompany\/(?<fcid>\d*)\/" class="entry__block"><div class="entry__freecompany__inner"><div class="entry__freecompany__crest"><div class="entry__freecompany__crest--position"><img src=".*" width="68" height="68" alt="" class="entry__freecompany__crest__base"><div class="entry__freecompany__crest__image"><img src="(?<fccrestimg1>https:.{58,74}\.png)" width="64" height="64"( alt=".*")?>(<img src="(?<fccrestimg2>https:.{58,74}\.png)" width="64" height="64"( alt=".*")?>)?(<img src="(?<fccrestimg3>https:.{58,74}\.png)" width="64" height="64"( alt=".*")?>)?<\/div><\/div><\/div><div class="entry__freecompany__box"><p class="entry__world">(?<gcname>.*)<\/p><p class="entry__name">(?<name>.*)<\/p><p class="entry__world">(?<server>.*)<\/p><\/div><div class="entry__freecompany__data">.*<\/div><\/div><ul class="entry__freecompany__fc-data clearix"><li class="entry__freecompany__fc-member">(?<members>\d*)<\/li><li class="entry__freecompany__fc-housing">(?<housing>.*)<\/li><li class="entry__freecompany__fc-day"><span id="datetime-.*">.*<\/span><script>document\.getElementById\(\'datetime-.*\'\)\.innerHTML = ldst_strftime\((?<found>.*), \'YMD\'\);<\/script><\/li><li class="entry__freecompany__fc-active">.*: (?<active>.*)<\/li><li class="entry__freecompany__fc-active">.*: (?<recruitment>.*)<\/li><\/ul><\/a><\/div>/mi',
            $html,
            $freecompanies,
            PREG_SET_ORDER
        );
        foreach ($freecompanies as $key=>$freecompany) {
            foreach ($freecompany as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($freecompanies[$key][$key2]);
                }
            }
            $freecompanies[$key]['crest'][] = str_replace('64x64', '128x128', $freecompany['fccrestimg1']);
            if (!empty($freecompany['fccrestimg2'])) {
                $freecompanies[$key]['crest'][] = str_replace('64x64', '128x128', $freecompany['fccrestimg2']);
            }
            if (!empty($freecompany['fccrestimg3'])) {
                $freecompanies[$key]['crest'][] = str_replace('64x64', '128x128', $freecompany['fccrestimg3']);
            }
            unset($freecompanies[$key]['fccrestimg1'], $freecompanies[$key]['fccrestimg2'], $freecompanies[$key]['fccrestimg3']);
        }
        $this->results['freeCompanies'] = $freecompanies;
    }
}
