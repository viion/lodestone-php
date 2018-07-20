<?php
#Functions used to convert textual filters to appropriate IDs used by Lodestone

namespace Lodestone\Modules;

trait Parsers {
    
    private function CharacterList()
    {
        preg_match_all(
            '/<div class="entry"><a href="\/lodestone\/character\/(?<id>\d*)\/" class="entry__link"><div class="entry__chara__face"><img src="(?<avatar>https:.*\.jpg).*" alt=".*"><\/div><div class="entry__box entry__box--world"><p class="entry__name">(?<name>.*)<\/p><p class="entry__world">(?<server>.*)<\/p><ul class="entry__chara_info"><li><i class="list__ic__class"><img src=".*" width="20" height="20" alt=""><\/i><span>.*<\/span><\/li>(<li class="js__tooltip" data-tooltip="(?<gcname>.*) \/ (?<gcrank>.*)"><img src="(?<gcrankicon>https:.*\.png)" width="20" height="20" alt=""><\/li>)?<\/ul><\/div><div class="entry__chara__lang">(?<language>.*)<\/div><\/a>(<a href="\/lodestone\/freecompany\/(?<fcid>\d*)\/" class="entry__freecompany__link"><i class="list__ic__crest"><img src="(?<fccrestimg1>https:.{58,74}\.png)" width="18" height="18">(<img src="(?<fccrestimg2>https:.{58,74}\.png)" width="18" height="18">)?(<img src="(?<fccrestimg3>https:.{58,74}\.png)" width="18" height="18">)?<\/i><span>(?<fcname>.*)<\/span><\/a>)?<\/div>/mi',
            $this->html,
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
                $characters[$key]['freeCompany']['crest'][] = str_replace('40x40', '128x128', $character['fccrestimg1']);
                if (!empty($character['fccrestimg2'])) {
                    $characters[$key]['freeCompany']['crest'][] = str_replace('40x40', '128x128', $character['fccrestimg2']);
                }
                if (!empty($character['fccrestimg3'])) {
                    $characters[$key]['freeCompany']['crest'][] = str_replace('40x40', '128x128', $character['fccrestimg3']);
                }
            }
            unset($characters[$key]['gcname'], $characters[$key]['gcrank'], $characters[$key]['gcrankicon'], $characters[$key]['fcid'], $characters[$key]['fcname'], $characters[$key]['fccrestimg1'], $characters[$key]['fccrestimg2'], $characters[$key]['fccrestimg3']);
        }
        $this->result['characters'] = $characters;
    }
    
    private function FreeCompaniesList()
    {
        preg_match_all(
            '/<div class="entry"><a href="\/lodestone\/freecompany\/(?<fcid>\d*)\/" class="entry__block"><div class="entry__freecompany__inner"><div class="entry__freecompany__crest"><div class="entry__freecompany__crest--position"><img src=".*" width="68" height="68" alt="" class="entry__freecompany__crest__base"><div class="entry__freecompany__crest__image"><img src="(?<fccrestimg1>https:.{58,74}\.png)" width="64" height="64"( alt=".*")?>(<img src="(?<fccrestimg2>https:.{58,74}\.png)" width="64" height="64"( alt=".*")?>)?(<img src="(?<fccrestimg3>https:.{58,74}\.png)" width="64" height="64"( alt=".*")?>)?<\/div><\/div><\/div><div class="entry__freecompany__box"><p class="entry__world">(?<gcname>.*)<\/p><p class="entry__name">(?<name>.*)<\/p><p class="entry__world">(?<server>.*)<\/p><\/div><div class="entry__freecompany__data">.*<\/div><\/div><ul class="entry__freecompany__fc-data clearix"><li class="entry__freecompany__fc-member">(?<members>\d*)<\/li><li class="entry__freecompany__fc-housing">(?<housing>.*)<\/li><li class="entry__freecompany__fc-day"><span id="datetime-.*">.*<\/span><script>document\.getElementById\(\'datetime-.*\'\)\.innerHTML = ldst_strftime\((?<found>.*), \'YMD\'\);<\/script><\/li><li class="entry__freecompany__fc-active">.*: (?<active>.*)<\/li><li class="entry__freecompany__fc-active">.*: (?<recruitment>.*)<\/li><\/ul><\/a><\/div>/mi',
            $this->html,
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
        $this->result['freeCompanies'] = $freecompanies;
    }
    
    private function LinkshellsList()
    {
        preg_match_all(
            '/<div class="entry">\s*<a href="\/lodestone\/linkshell\/(?<id>\d*)\/" class="entry__link--line">\s*<div class="entry__linkshell__icon">\s*<i class="icon-menu__linkshell_40"><\/i>\s*<\/div>\s*<div class="entry__linkshell">\s*<p class="entry__name">(?<name>.*)<\/p>\s*<p class="entry__world">(?<server>.*)<\/p>\s*<\/div>\s*<div class="entry__linkshell__member">\s*.*: <span>(?<members>\d*)<\/span>\s*<\/div>\s*<\/a>\s*<\/div>/mi',
            $this->html,
            $linkshells,
            PREG_SET_ORDER
        );
        foreach ($linkshells as $key=>$linkshell) {
            foreach ($linkshell as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($linkshells[$key][$key2]);
                }
            }
        }
        $this->result['linkshells'] = $linkshells;
    }
    
    private function PvPTeamsList()
    {
        preg_match_all(
            '/<div class="entry"><a href="\/lodestone\/pvpteam\/(?<id>.*)\/" class="entry__block"><div class="entry__pvpteam__search__inner"><div class="entry__pvpteam__search__crest"><div class="entry__pvpteam__search__crest--position"><img src=".*\.png" width="50" height="50" alt="" class="entry__pvpteam__search__crest__base"><div class="entry__pvpteam__search__crest__image"><img src="(?<crest1>https:.{58,74}\.png)" width="48" height="48">(<img src="(?<crest2>https:.{58,74}\.png)" width="48" height="48">)?(<img src="(?<crest3>https:.{58,74}\.png)" width="48" height="48">)?<\/div><\/div><\/div><div class="entry__freecompany__box"><p class="entry__name">(?<name>.*)<\/p><p class="entry__world">(?<dataCentre>.*)<\/p><\/div><\/div><\/a><\/div>/mi',
            $this->html,
            $pvpteams,
            PREG_SET_ORDER
        );
        foreach ($pvpteams as $key=>$pvpteam) {
            foreach ($pvpteam as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($pvpteams[$key][$key2]);
                }
            }
            $pvpteams[$key]['crest'][] = str_replace('64x64', '128x128', $pvpteam['crest1']);
            if (!empty($pvpteam['crest2'])) {
                $pvpteams[$key]['crest'][] = str_replace('64x64', '128x128', $pvpteam['crest2']);
            }
            if (!empty($pvpteam['crest3'])) {
                $pvpteams[$key]['crest'][] = str_replace('64x64', '128x128', $pvpteam['crest3']);
            }
            unset($pvpteams[$key]['crest1'], $pvpteams[$key]['crest2'], $pvpteams[$key]['crest3']);
        }
        $this->result['PvPTeams'] = $pvpteams;
    }
    
    private function pageCount()
    {
        preg_match_all(
            '/<div class="parts__total">(?<total>\d*).*<\/div>.*<li class="btn__pager__current">(Page |Seite )*(?<pageCurrent>\d*)[^\d]*(?<pageTotal>\d*).*<\/li>/mis',
            $this->html,
            $pages,
            PREG_SET_ORDER
        );
        $this->result = [
            'pageCurrent'=>$pages[0]['pageCurrent'],
            'pageTotal'=>$pages[0]['pageTotal'],
            'total'=>$pages[0]['total'],
        ];
        return $this;
    }
}
?>