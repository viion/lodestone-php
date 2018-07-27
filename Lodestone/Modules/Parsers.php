<?php
namespace Lodestone\Modules;

trait Parsers
{    
    private function FreeCompany()
    {
        preg_match_all(
            '/<div class="entry">\s*<a href="\/lodestone\/freecompany\/(?<id>\d*)\/" class="entry__freecompany">\s*<div class="entry__freecompany__crest">\s*<div class="entry__freecompany__crest--position">\s*<img src=".{66}\.png" width="68" height="68" alt="" class="entry__freecompany__crest__base">\s*<div class="entry__freecompany__crest__image">\s*<img src="(?<crest1>.{80}\.png)" width="64" height="64">(\s*<img src="(?<crest2>.{80}\.png)" width="64" height="64">)?(\s*<img src="(?<crest3>.{80}\.png)" width="64" height="64">)?\s*<\/div>\s*<\/div>\s*<\/div>\s*<div class="entry__freecompany__box">\s*<p class="entry__freecompany__gc">(?<grandCompany>.{1,40})&#60;.{1,20}<\/p>\s*<p class="entry__freecompany__name">(?<name>.{1,40})<\/p>\s*<p class="entry__freecompany__gc">\s*(?<server>.{1,40})\s*<\/p>\s*<\/div>\s*<\/a>\s*<\/div>\s*<h3 class="heading--lead">.{1,40}<\/h3>\s*<p class="freecompany__text freecompany__text__message">(?<slogan>.{1,200})<\/p>\s*<h3 class="heading--lead">.{1,100}<span class="freecompany__text__tag">.{1,100}<\/span><\/h3>\s*<p class="freecompany__text__name">.{1,40}<p>\s*<p class="freecompany__text freecompany__text__tag">&laquo;(?<tag>.{1,5})&raquo;<\/span><\/p>\s*<h3 class="heading--lead">.{1,20}<\/h3>\s*<p class="freecompany__text">\s*<span id="datetime-0\.\d*">-<\/span>\s*<script>\s*document\.getElementById\(\'datetime-0\.\d*\'\)\.innerHTML = ldst_strftime\((?<formed>\d*), \'YMD\'\);\s*<\/script>\s*<\/p>\s*<h3 class="heading--lead">.{1,50}<\/h3>\s*<p class="freecompany__text">(?<members>\d*)<\/p>\s*<h3 class="heading--lead">.{1,15}<\/h3>\s*<p class="freecompany__text">(?<rank>\d*)<\/p>\s*<h3 class="heading--lead">.{1,20}<\/h3>\s*<div class="freecompany__reputation">\s*<div class="freecompany__reputation__icon">\s*<img src=".{66}\.png" alt="" width="32" height="32">\s*<\/div>\s*<div class="freecompany__reputation__data">\s*<p class="freecompany__reputation__gcname">(?<gcname1>.{1,40})<\/p>\s*<p class="freecompany__reputation__rank color_\d*">(?<gcrepu1>.{1,40})<\/p>\s*<div class="character__bar">\s*<div style="width:\d*%;"><\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<div class="freecompany__reputation">\s*<div class="freecompany__reputation__icon">\s*<img src=".{66}\.png" alt="" width="32" height="32">\s*<\/div>\s*<div class="freecompany__reputation__data">\s*<p class="freecompany__reputation__gcname">(?<gcname2>.{1,40})<\/p>\s*<p class="freecompany__reputation__rank color_\d*">(?<gcrepu2>.{1,40})<\/p>\s*<div class="character__bar">\s*<div style="width:\d*%;"><\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<div class="freecompany__reputation last">\s*<div class="freecompany__reputation__icon">\s*<img src=".{66}\.png" alt="" width="32" height="32">\s*<\/div>\s*<div class="freecompany__reputation__data">\s*<p class="freecompany__reputation__gcname">(?<gcname3>.{1,40})<\/p>\s*<p class="freecompany__reputation__rank color_\d*">(?<gcrepu3>.{1,40})<\/p>\s*<div class="character__bar">\s*<div style="width:\d*%;"><\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<h3 class="heading--lead">.{1,40}<\/h3>\s*<table class="character__ranking__data parts__space--reset">\s*<tr>\s*<th>[^\d\-]{1,17}(?<weekly_rank>[\d\-]{1,})?[^\d]{0,20}<\/th>\s*<\/tr>\s*<tr>\s*<th>[^\d\-]{1,17}(?<monthly_rank>[\d\-]{1,})?[^\d]{0,20}<\/th>\s*<\/tr>\s*<\/table>\s*<p class="freecompany__ranking__notes">.{1,100}\/p>\s*<h3 class="heading--lead">.{1,100}<\/h3>\s*((<p class="freecompany__estate__name">(?<estate_name>.{1,20})<\/p>|<p class="parts__text">.{1,40}<\/p>)\s*<p class="freecompany__estate__title">.{1,40}<\/p>\s*<p class="freecompany__estate__text">(?<estate_address>.{1,200})<\/p>\s*<p class="freecompany__estate__title">.{1,20}<\/p>\s*<p class="freecompany__estate__greeting">(?<estate_greeting>.{0,200})<\/p>|<p class="freecompany__estate__none">.{1,50}<\/p>)\s*<\/div>\s*<div class="ldst__window">\s*<h2 class="heading--lg parts__space--add" id="anchor__focus">.{1,40}<\/h2>\s*<h3 class="heading--lead">.{1,40}<\/h3>\s*<p class="freecompany__text">\s*(?<active>.{1,40})\s*<\/p>\s*<h3 class="heading--lead">.{1,40}<\/h3>\s*<p class="freecompany__text( freecompany__recruitment)?">\s*(?<recruitment>.{1,40})\s*<\/p>\s*<h3 class="heading--lead">.{1,40}<\/h3>\s*(<ul class="freecompany__focus_icon clearfix">\s*<li( class="(?<focusoff1>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon1>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname1>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff2>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon2>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname2>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff3>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon3>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname3>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff4>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon4>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname4>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff5>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon5>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname5>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff6>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon6>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname6>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff7>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon7>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname7>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff8>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon8>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname8>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<focusoff9>freecompany__focus_icon--off)")?>\s*<div><img src="(?<focusicon9>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<focusname9>.{1,40})<\/p>\s*<\/li>\s*<\/ul>|<p class="freecompany__text">.{1,40}<\/p>)\s*<h3 class="heading--lead">.{1,40}<\/h3>\s*(<ul class="freecompany__focus_icon freecompany__focus_icon--role clearfix">\s*<li( class="(?<seekingoff1>freecompany__focus_icon--off)")?>\s*<div><img src="(?<seekingicon1>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<seekingname1>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<seekingoff2>freecompany__focus_icon--off)")?>\s*<div><img src="(?<seekingicon2>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<seekingname2>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<seekingoff3>freecompany__focus_icon--off)")?>\s*<div><img src="(?<seekingicon3>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<seekingname3>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<seekingoff4>freecompany__focus_icon--off)")?>\s*<div><img src="(?<seekingicon4>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<seekingname4>.{1,40})<\/p>\s*<\/li>\s*<li( class="(?<seekingoff5>freecompany__focus_icon--off)")?>\s*<div><img src="(?<seekingicon5>.{66}\.png)" alt="" width="32" height="32"><\/div>\s*<p>(?<seekingname5>.{1,40})<\/p>\s*<\/li>\s*<\/ul>|<p class="parts__text">.{1,30}<\/p>)/mi',
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
            $characters[$key]['crest'][] = str_replace('64x64', '128x128', $character['crest1']);
            if (!empty($character['crest2'])) {
                $characters[$key]['crest'][] = str_replace('64x64', '128x128', $character['crest2']);
            }
            if (!empty($character['crest3'])) {
                $characters[$key]['crest'][] = str_replace('64x64', '128x128', $character['crest3']);
            }
            //ranking checks for --
            if ($character['weekly_rank'] == '--') {
                unset($characters[$key]['weekly_rank']);
            }
            if ($character['monthly_rank'] == '--') {
                unset($characters[$key]['monthly_rank']);
            }
            #Estates
            if (!empty($character['estate_name'])) {
                $characters[$key]['estate']['name'] = $character['estate_name'];
            }
            if (!empty($character['estate_address'])) {
                $characters[$key]['estate']['address'] = $character['estate_address'];
            }
            if (!empty($character['estate_greeting']) && !in_array($character['estate_greeting'], ['No greeting available.', 'グリーティングメッセージが設定されていません。', 'Il n\'y a aucun message d\'accueil.', 'Keine Begrüßung vorhanden.'])) {
                $characters[$key]['estate']['greeting'] = $character['estate_greeting'];
            }
            #Grand companies reputation
            $characters[$key]['reputation'] = [
                $character['gcname1']=>$character['gcrepu1'],
                $character['gcname2']=>$character['gcrepu2'],
                $character['gcname3']=>$character['gcrepu3'],
            ];
            #Focus
            if (!empty($character['focusname1'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname1'],
                    'enabled'=>($character['focusoff1'] ? 0 : 1),
                    'icon'=>$character['focusicon1'],
                ];
            }
            if (!empty($character['focusname2'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname2'],
                    'enabled'=>($character['focusoff2'] ? 0 : 1),
                    'icon'=>$character['focusicon2'],
                ];
            }
            if (!empty($character['focusname3'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname3'],
                    'enabled'=>($character['focusoff3'] ? 0 : 1),
                    'icon'=>$character['focusicon3'],
                ];
            }
            if (!empty($character['focusname4'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname4'],
                    'enabled'=>($character['focusoff4'] ? 0 : 1),
                    'icon'=>$character['focusicon4'],
                ];
            }
            if (!empty($character['focusname5'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname5'],
                    'enabled'=>($character['focusoff5'] ? 0 : 1),
                    'icon'=>$character['focusicon5'],
                ];
            }
            if (!empty($character['focusname6'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname6'],
                    'enabled'=>($character['focusoff6'] ? 0 : 1),
                    'icon'=>$character['focusicon6'],
                ];
            }
            if (!empty($character['focusname7'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname7'],
                    'enabled'=>($character['focusoff7'] ? 0 : 1),
                    'icon'=>$character['focusicon7'],
                ];
            }
            if (!empty($character['focusname8'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname8'],
                    'enabled'=>($character['focusoff8'] ? 0 : 1),
                    'icon'=>$character['focusicon8'],
                ];
            }
            if (!empty($character['focusname9'])) {
                $characters[$key]['focus'][] = [
                    'name'=>$character['focusname9'],
                    'enabled'=>($character['focusoff9'] ? 0 : 1),
                    'icon'=>$character['focusicon9'],
                ];
            }
            #Seeking
            if (!empty($character['seekingname1'])) {
                $characters[$key]['seeking'][] = [
                    'name'=>$character['seekingname1'],
                    'enabled'=>($character['seekingoff1'] ? 0 : 1),
                    'icon'=>$character['seekingicon1'],
                ];
            }
            if (!empty($character['seekingname2'])) {
                $characters[$key]['seeking'][] = [
                    'name'=>$character['seekingname2'],
                    'enabled'=>($character['seekingoff2'] ? 0 : 1),
                    'icon'=>$character['seekingicon2'],
                ];
            }
            if (!empty($character['seekingname3'])) {
                $characters[$key]['seeking'][] = [
                    'name'=>$character['seekingname3'],
                    'enabled'=>($character['seekingoff3'] ? 0 : 1),
                    'icon'=>$character['seekingicon3'],
                ];
            }
            if (!empty($character['seekingname4'])) {
                $characters[$key]['seeking'][] = [
                    'name'=>$character['seekingname4'],
                    'enabled'=>($character['seekingoff4'] ? 0 : 1),
                    'icon'=>$character['seekingicon4'],
                ];
            }
            if (!empty($character['seekingname5'])) {
                $characters[$key]['seeking'][] = [
                    'name'=>$character['seekingname5'],
                    'enabled'=>($character['seekingoff5'] ? 0 : 1),
                    'icon'=>$character['seekingicon5'],
                ];
            }
            #Trim slogan
            $characters[$key]['slogan'] = trim($character['slogan']);
            unset($characters[$key]['crest1'], $characters[$key]['crest2'], $characters[$key]['crest3'], $characters[$key]['focusname1'], $characters[$key]['focusoff1'], $characters[$key]['focusicon1'], $characters[$key]['focusname2'], $characters[$key]['focusoff2'], $characters[$key]['focusicon2'], $characters[$key]['focusname3'], $characters[$key]['focusoff3'], $characters[$key]['focusicon3'], $characters[$key]['focusname4'], $characters[$key]['focusoff4'], $characters[$key]['focusicon4'], $characters[$key]['focusname5'], $characters[$key]['focusoff5'], $characters[$key]['focusicon5'], $characters[$key]['focusname6'], $characters[$key]['focusoff6'], $characters[$key]['focusicon6'], $characters[$key]['focusname7'], $characters[$key]['focusoff7'], $characters[$key]['focusicon7'], $characters[$key]['focusname8'], $characters[$key]['focusoff8'], $characters[$key]['focusicon8'], $characters[$key]['focusname9'], $characters[$key]['focusoff9'], $characters[$key]['focusicon9'], $characters[$key]['seekingname1'], $characters[$key]['seekingoff1'], $characters[$key]['seekingicon1'], $characters[$key]['seekingname2'], $characters[$key]['seekingoff2'], $characters[$key]['seekingicon2'], $characters[$key]['seekingname3'], $characters[$key]['seekingoff3'], $characters[$key]['seekingicon3'], $characters[$key]['seekingname4'], $characters[$key]['seekingoff4'], $characters[$key]['seekingicon4'], $characters[$key]['seekingname5'], $characters[$key]['seekingoff5'], $characters[$key]['seekingicon5'],
            $characters[$key]['gcname1'], $characters[$key]['gcrepu1'],
            $characters[$key]['gcname2'], $characters[$key]['gcrepu2'],
            $characters[$key]['gcname3'], $characters[$key]['gcrepu3'], $characters[$key]['estate_greeting'],  $characters[$key]['estate_address'],  $characters[$key]['estate_name']);
        }
        $this->result = $characters[0];
        return $this;
    }
    
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
            '/<(li|div) class="entry">\s*<a href="\/lodestone\/character\/(?<id>\d*)\/" class="entry__(bg|link)">(\s*<div class="entry__flex">)?\s*<div class="entry__chara__face">\s*<img src="(?<avatar>.{109}\.jpg)\?\d*" alt=".{0,40}">\s*<\/div>\s*<div class="(entry__freecompany__center|entry__box entry__box--world)">\s*<p class="entry__name">(?<name>.{1,40})<\/p>\s*<p class="entry__world">(?<server>.{1,40})<\/p>\s*<ul class="entry__(chara_|freecompany__)info">(\s*<li>\s*<img src="(?<rankicon>.{66}\.png)" width="20" height="20" alt=""><span>(?<rank>.{1,15})<\/span><\/li>)?\s*<li>\s*<i class="list__ic__class">\s*<img src=".{66}\.png" width="20" height="20" alt="">\s*<\/i>\s*<span>\d*<\/span>\s*<\/li>(\s*<li class="js__tooltip" data-tooltip="(?<gcname>.*) \/ (?<gcrank>.*)">\s*<img src="(?<gcrankicon>.{66}\.png)" width="20" height="20" alt="">\s*<\/li>)?(\s*<li>\s*<img src=".{66}\.png" width="20" height="20" class="entry__pvpteam__battle__icon js__tooltip" data-tooltip=".{1,40}">\s*<span>(?<feasts>\d*)<\/span>\s*<\/li>)?\s*<\/ul>(\s*<div class="entry__chara_info__linkshell">\s*<img src="(?<lsrankicon>.{66}\.png)" width="20" height="20" alt="">\s*<span>(?<lsrank>.{1,40})<\/span>\s*<\/div>)?\s*<\/div>(\s*<div class="entry__chara__lang">(?<language>.{1,40})<\/div>)?(\s*<\/div>)?\s*<\/a>(\s*<a href="\/lodestone\/freecompany\/(?<fcid>\d*)\/" class="entry__freecompany__link">\s*<i class="list__ic__crest">\s*<img src="(?<fccrestimg1>https:.{58,74}\.png)" width="18" height="18">(\s*<img src="(?<fccrestimg2>https:.{58,74}\.png)" width="18" height="18">)?(\s*<img src="(?<fccrestimg3>https:.{58,74}\.png)" width="18" height="18">)?\s*<\/i>\s*<span>(?<fcname>.{1,40})<\/span>\s*<\/a>)?\s*<\/(li|div)>/mi',
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
            if (!empty($character['lsrank'])) {
                $characters[$key]['rank'] = $character['lsrank'];
                $characters[$key]['rankicon'] = $character['lsrankicon'];
                if (empty($this->result['server'])) {
                    $this->result['server'] = $character['server'];
                }
                unset($characters[$key]['server']);
            }
            unset($characters[$key]['gcname'], $characters[$key]['gcrank'], $characters[$key]['gcrankicon'], $characters[$key]['fcid'], $characters[$key]['fcname'], $characters[$key]['fccrestimg1'], $characters[$key]['fccrestimg2'], $characters[$key]['fccrestimg3'], $characters[$key]['lsrank'], $characters[$key]['lsrankicon']);
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
            '/(<div class="entry__pvpteam__crest__image">\s*<img src="https:.{58,74}\.png" width="64" height="64">\s*<img src="(?<crest1>https:.{58,74}\.png)" width="64" height="64">(\s*<img src="(?<crest2>https:.{58,74}\.png)" width="64" height="64">)?(\s*<img src="(?<crest3>https:.{58,74}\.png)" width="64" height="64">)?\s*<\/div>\s*<\/div>\s*<\/div>\s*<div class="entry__pvpteam__name">\s*<h2 class="entry__pvpteam__name--team">(?<pvpname>.{1,40})<\/h2>\s*<p class="entry__pvpteam__name--dc">(?<server>.{1,40})<\/p>\s*<\/div>\s*<div class="entry__pvpteam__data">\s*<span class="entry__pvpteam__data--formed">\s*.{1,100}<span id="datetime-0\.\d*">-<\/span><script>document\.getElementById\(\'datetime-0\.\d*\'\)\.innerHTML = ldst_strftime\((?<formed>\d*), \'YMD\'\);<\/script>\s*<\/span>\s*<\/div>\s*<\/div>\s*<\/div>)|((<h3 class="heading__linkshell__name">(?<linkshellname>.{1,40})<\/h3>.{1,2000})?(<div class="parts__total">(?<total>\d*).{0,20}<\/div>.{1,3000})?<li class="btn__pager__current">(Page |Seite )*(?<pageCurrent>\d*)[^\d]*(?<pageTotal>\d*).{0,20}<\/li>)/mis',
            $this->html,
            $pages,
            PREG_SET_ORDER
        );
        if (!empty($pages[0]['linkshellname'])) {
            $this->result['name'] = $pages[0]['linkshellname'];
        }
        if (!empty($pages[0]['pageCurrent'])) {
            $this->result['pageCurrent'] = $pages[0]['pageCurrent'];
        }
        if (!empty($pages[0]['pageTotal'])) {
            $this->result['pageTotal'] = $pages[0]['pageTotal'];
        }
        if (!empty($pages[0]['total'])) {
            $this->result['total'] = $pages[0]['total'];
        }
        if (!empty($pages[0]['pvpname'])) {
            $this->result['name'] = $pages[0]['pvpname'];
            if (!empty($pages[0]['server'])) {
                $this->result['dataCenter'] = $pages[0]['server'];
            }
            if (!empty($pages[0]['formed'])) {
                $this->result['formed'] = $pages[0]['formed'];
            }
            $this->result['crest'][] = str_replace('64x64', '128x128', $pages[0]['crest1']);
            if (!empty($pages[0]['crest2'])) {
                $this->result['crest'][] = str_replace('64x64', '128x128', $pages[0]['crest2']);
            }
            if (!empty($pages[0]['crest3'])) {
                $this->result['crest'][] = str_replace('64x64', '128x128', $pages[0]['crest3']);
            }
        }
        return $this;
    }
}
?>