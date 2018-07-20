<?php

namespace Lodestone\Search;

/**
 * Class Search
 *
 * @package Lodestone\Parser\Character
 */
class Character
{
    public $results;
    
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
            '/<div class="entry"><a href="\/lodestone\/character\/(?<id>\d*)\/" class="entry__link"><div class="entry__chara__face"><img src="(?<avatar>https:.*\.jpg).*" alt=".*"><\/div><div class="entry__box entry__box--world"><p class="entry__name">(?<name>.*)<\/p><p class="entry__world">(?<server>.*)<\/p><ul class="entry__chara_info"><li><i class="list__ic__class"><img src=".*" width="20" height="20" alt=""><\/i><span>.*<\/span><\/li>(<li class="js__tooltip" data-tooltip="(?<gcname>.*) \/ (?<gcrank>.*)"><img src="(?<gcrankicon>https:.*\.png)" width="20" height="20" alt=""><\/li>){0,1}?<\/ul><\/div><div class="entry__chara__lang">(?<language>.*)<\/div><\/a>(<a href="\/lodestone\/freecompany\/(?<fcid>\d*)\/" class="entry__freecompany__link"><i class="list__ic__crest"><img src="(?<fccrestimg1>https:.{74}\.png)" width="18" height="18">(<img src="(?<fccrestimg2>https:.{74}\.png)" width="18" height="18">){0,1}(<img src="(?<fccrestimg3>https:.{74}\.png)" width="18" height="18">){0,1}<\/i><span>(?<fcname>.*)<\/span><\/a>){0,1}<\/div>/mi',
            $html,
            $characters,
            PREG_SET_ORDER
        );
        foreach ($characters as $key=>$character) {
            foreach ($character as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($characters[$key][$key2]);
                }
            }
            if (!empty($character['gcname'])) {
                $characters[$key]['grandCompany'] = [
                    'name'=>$character['gcname'],
                    'rank'=>$character['gcrank'],
                    'icon'=>$character['gcrankicon'],
                ];
            }
            if (!empty($character['fcid'])) {
                $characters[$key]['freeCompany'] = [
                    'id'=>$character['fcid'],
                    'name'=>$character['fcname'],
                    'crest'=>[],
                ];
                $characters[$key]['freeCompany']['crest'][] = $character['fccrestimg1'];
                if (!empty($character['fccrestimg2'])) {
                    $characters[$key]['freeCompany']['crest'][] = $character['fccrestimg2'];
                }
                if (!empty($character['fccrestimg3'])) {
                    $characters[$key]['freeCompany']['crest'][] = $character['fccrestimg3'];
                }
            }
            unset($characters[$key]['gcname'], $characters[$key]['gcrank'], $characters[$key]['gcrankicon'], $characters[$key]['fcid'], $characters[$key]['fcname'], $characters[$key]['fccrestimg1'], $characters[$key]['fccrestimg2'], $characters[$key]['fccrestimg3']);
        }
        $this->results['characters'] = $characters;
    }
}