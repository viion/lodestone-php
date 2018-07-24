<?php
namespace Lodestone\Modules;

trait Parsers
{    
    private function DeepDungeon()
    {
        preg_match_all(
            '/<li class="deepdungeon__ranking__list__item"\s*data-href="\/lodestone\/character\/(?<id>\d*)\/"\s*>\s*<div class="deepdungeon__ranking__order">\s*<p class="deepdungeon__ranking__result__order">(?<rank>\d*)<\/p>\s*<\/div>\s*<div class="deepdungeon__ranking__face">\s*<div class="deepdungeon__ranking__face__inner">\s*<img src="(?<avatar>.{109}\.jpg)\?\d*" width="50" height="50" alt="">\s*<\/div>\s*<\/div>(\s*<div class="deepdungeon__ranking__job">\s*<img src="(?<jobform>.{66}\.png)" width="52" height="60" alt="" title=".{1,40}" class="tooltip">\s*<\/div>)?\s*<div class="deepdungeon__ranking__name">\s*<div class="deepdungeon__ranking__result__name">\s*<h3>(?<name>.{1,40})<\/h3>\s*<\/div>\s*<span class="deepdungeon__ranking__result__world">(?<server>.{1,40})<\/span>\s*<\/div>\s*<div class="deepdungeon__ranking__data">\s*<p class="deepdungeon__ranking__data--score">(?<score>\d*)<\/p>\s*<p class="deepdungeon__ranking__data--reaching">(.{1,9} |B)(?<floor>\d*)<\/p>\s*<p class="deepdungeon__ranking__data--time"><span id="datetime-0\.\d*">-<\/span><script>document\.getElementById\(\'datetime-0\.\d*\'\)\.innerHTML = ldst_strftime\((?<time>\d*), \'YMDHM\'\);<\/script><\/p>\s*<\/div>\s*<div class="deepdungeon__ranking__icon">\s*<img src="(?<jobicon>.{66}\.png)" width="32" height="32" alt="" title="(?<job>.{1,40})" class="tooltip">\s*<\/div>\s*<\/li>/mi',
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
            $characters[$key]['job'] = [
                'name'=>$character['job'],
                'icon'=>$character['jobicon'],
                'form'=>$character['jobform'],
            ];
            unset($characters[$key]['jobicon'], $characters[$key]['jobform']);
        }
        $this->result = $characters;
        return $this;
    }
    
    private function Feast()
    {
        preg_match_all(
            '/<tr\s*data-href="\/lodestone\/character\/(?<id>\d*)\/"\s*>\s*<td class="wolvesden__ranking__td__order">\s*<p class="wolvesden__ranking__result__order">(?<rank>\d*)<\/p>\s*<\/td>\s*<td class="wolvesden__ranking__td__prev_order">(?<rank_previous>\d*)<\/td>\s*<td class="wolvesden__ranking__td__face">\s*<div class="wolvesden__ranking__result__face">\s*<img src="(?<avatar>.{109}\.jpg)\?\d*" width="50" height="50" alt="">\s*<\/div>\s*<\/td>\s*<td class="wolvesden__ranking__td__name">\s*<div class="wolvesden__ranking__result__name">\s*<h3>(?<name>.{1,40})<\/h3>\s*<\/div>\s*<span class="wolvesden__ranking__result__world">(?<server>.{1,40})<\/span>\s*<\/td>(\s*<td class="wolvesden__ranking__td__win_count">\s*<p class="wolvesden__ranking__result__win_count">(?<win_count>\d*)<\/p>\s*<p class="wolvesden__ranking__result__winning_rate">(?<win_rate>[0-9\.]*)%<\/p>\s*<\/td>\s*<td class="wolvesden__ranking__td__separator">	\s*<p class="wolvesden__ranking__result__separator">\/<\/p>\s*<\/td>\s*<td class="wolvesden__ranking__td__match_count">\s*<p class="wolvesden__ranking__result__match_count">(?<matches>\d*)<\/p>\s*<\/td>)?\s*<td class="wolvesden__ranking__td__match_rate">\s*<p class="wolvesden__ranking__result__match_rate">(?<rating>\d*)<\/p>\s*<\/td>\s*<td class="wolvesden__ranking__td__rank">\s*<img src="(?<league_image>.{66}\.png)" width="88" height="60" alt=".{1,20}" title="(?<league>.{1,20})" class="js--wolvesden-tooltip">\s*<\/td>\s*<\/tr>/mi',
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
        }
        $this->result = $characters;
        return $this;
    }
    
    private function Worlds()
    {
        preg_match_all(
            '/<div class="item-list__worldstatus">\s*<h3 class="">(?<server>.*)<\/h3>\s*<p>\s*(?<status>.{1,10})\s*<\/p>/mi',
            $this->html,
            $worlds,
            PREG_SET_ORDER
        );
        foreach ($worlds as $key=>$world) {
            foreach ($world as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($worlds[$key][$key2]);
                }
            }
        }
        $this->result = $worlds;
        return $this;
    }
    
    private function Notices()
    {
        #required to skipp "special" notices
        preg_match_all(
            '/<ul>(<li class="news__list">.*<\/li>)*<\/ul>/im',
            $this->html,
            $notices,
            PREG_SET_ORDER
        );
        preg_match_all(
            '/<li class="news__list"><a href="(?<url>.{63})" class="news__list--link ic__info--list"><div class="clearfix"><p class="news__list--title">(<span class="news__list--tag">\[(?<tag>.{1,20})\]<\/span>)?(?<title>.{1,100})<\/p><time class="news__list--time"><span id="datetime-0\.\d*">-<\/span><script>document\.getElementById\(\'datetime-0\.\d*\'\)\.innerHTML = ldst_strftime\((?<time>\d*), \'YMD\'\);<\/script><\/time><\/div><\/a><\/li>/mi',
            $notices[0][0],
            $notices,
            PREG_SET_ORDER
        );
        foreach ($notices as $key=>$notice) {
            foreach ($notice as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($notices[$key][$key2]);
                }
            }
            $notices[$key]['url'] = $this->language.Routes::LODESTONE_URL_BASE.$notice['url'];
        }
        $this->result['notices'] = $notices;
        unset($this->result['total']);
        return $this;
    }
    
    private function News()
    {
        preg_match_all(
            '/<li class="news__list--topics ic__topics--list( news__content__bottom-radius)?"><header class="news__list--header clearfix"><p class="news__list--title"><a href="(?<url>.{65})">(?<title>.{1,100})<\/a><\/p><time class="news__list--time"><span id="datetime-0\.\d*">.{1,20}<\/span><script>document\.getElementById\(\'datetime-0\.\d*\'\)\.innerHTML = ldst_strftime\((?<time>\d*), \'YMD\'\);<\/script><\/time><\/header><div class="news__list--banner"><a href=".{65}" class="news__list--img"><img src="(?<banner>.{74})\.(png|jpg)\?\d*" width="570"( height="149")? alt=""><\/a>(?<html>.{0,1200})<\/div><\/li>/im',
            $this->html,
            $news,
            PREG_SET_ORDER
        );
        foreach ($news as $key=>$new) {
            foreach ($new as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($news[$key][$key2]);
                }
            }
            $news[$key]['url'] = $this->language.Routes::LODESTONE_URL_BASE.$new['url'];
        }
        if ($this->type == 'Topics') {
            unset($this->result['total']);
            $this->result['topics'] = $news;
        } else {
            $this->result = $news;
        }
        return $this;
    }
    
    private function Banners()
    {
        preg_match('/<ul id="slider_bnr_area">(\s*<li.*><a href=".*".*><img src=".*".*><\/a><\/li>\s*)*<\/ul>/im', $this->html, $banners);
        preg_match_all(
            '/<li><a href="(?<url>.{19,19})"><img src="(?<banner>.{74,74}\.png)\?\d*" width="\d*" height="\d*"><\/a><\/li>/ims',
            $banners[0],
            $banners,
            PREG_SET_ORDER
        );
        foreach ($banners as $key=>$banner) {
            foreach ($banner as $key2=>$details) {
                if (is_numeric($key2) || empty($details)) {
                    unset($banners[$key][$key2]);
                }
            }
        }
        $this->result = $banners;
        return $this;
    }
    
    private function CharacterList()
    {
        preg_match_all(
            '/<(li|div) class="entry">\s*<a href="\/lodestone\/character\/(?<id>\d*)\/" class="entry__(bg|link)">(\s*<div class="entry__flex">)?\s*<div class="entry__chara__face">\s*<img src="(?<avatar>.{109}\.jpg)\?\d*" alt=".{0,40}">\s*<\/div>\s*<div class="(entry__freecompany__center|entry__box entry__box--world)">\s*<p class="entry__name">(?<name>.{1,40})<\/p>\s*<p class="entry__world">(?<server>.{1,40})<\/p>\s*<ul class="entry__(chara_|freecompany__)info">(\s*<li>\s*<img src="(?<rankicon>.{66}\.png)" width="20" height="20" alt=""><span>(?<rank>.{1,15})<\/span><\/li>)?\s*<li>\s*<i class="list__ic__class">\s*<img src=".{66}\.png" width="20" height="20" alt="">\s*<\/i>\s*<span>\d*<\/span>\s*<\/li>(\s*<li class="js__tooltip" data-tooltip="(?<gcname>.*) \/ (?<gcrank>.*)">\s*<img src="(?<gcrankicon>.{66}\.png)" width="20" height="20" alt="">\s*<\/li>)?\s*<\/ul>\s*<\/div>(\s*<div class="entry__chara__lang">(?<language>.*)<\/div>)?(\s*<\/div>)?\s*<\/a>(\s*<a href="\/lodestone\/freecompany\/(?<fcid>\d*)\/" class="entry__freecompany__link">\s*<i class="list__ic__crest">\s*<img src="(?<fccrestimg1>https:.{58,74}\.png)" width="18" height="18">(\s*<img src="(?<fccrestimg2>https:.{58,74}\.png)" width="18" height="18">)?(\s*<img src="(?<fccrestimg3>https:.{58,74}\.png)" width="18" height="18">)?\s*<\/i>\s*<span>(?<fcname>.{1,40})<\/span>\s*<\/a>)?\s*<\/(li|div)>/m',
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
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
    }
    
    private function pageCount()
    {
        preg_match_all(
            '/(<div class="parts__total">(?<total>\d*).{0,20}<\/div>.*)?<li class="btn__pager__current">(Page |Seite )*(?<pageCurrent>\d*)[^\d]*(?<pageTotal>\d*).{0,20}<\/li>/mis',
            $this->html,
            $pages,
            PREG_SET_ORDER
        );
        if (isset($pages[0]['pageCurrent'])) {
            $this->result['pageCurrent'] = $pages[0]['pageCurrent'];
        }
        if (isset($pages[0]['pageTotal'])) {
            $this->result['pageTotal'] = $pages[0]['pageTotal'];
        }
        if (isset($pages[0]['total'])) {
            $this->result['total'] = $pages[0]['total'];
        }
        return $this;
    }
}
?>