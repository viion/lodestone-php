<?php
namespace Lodestone\Search;

trait Converters {
    
    private function getFeastRankId(string $rank): string
    {
        switch(strtolower($rank)) {
            case '1':
            case 'bronze':
            case 'verteidiger':
                $id = '0'; break;
            case '2':
            case 'silver':
            case 'argent':
            case 'silber':
                $id = '1'; break;
            case '3':
            case 'gold':
            case 'or':
                $id = '2'; break;
            case '4':
            case 'platinum':
            case 'platine':
            case 'platin':
                $id = '3'; break;
            case '5':
            case 'diamond':
            case 'diamant':
                $id = '4'; break;
            default:
                $id = 'all';
        }
        return $id;
    }
    
    private function getSearchRolesId(string $role): string
    {
        switch(strtolower($role)) {
            case '0':
            case 'tank':
            case 'tanks':
            case 'verteidiger':
                $id = '0'; break;
            case '1':
            case 'healer':
            case 'soigneurs':
            case 'heiler':
                $id = '1'; break;
            case '2':
            case 'dps':
            case 'angreifer':
                $id = '2'; break;
            case '3':
            case 'crafter':
            case 'artisans':
            case 'handwerker':
                $id = '3'; break;
            case '4':
            case 'gatherer':
            case 'récolteurs':
            case 'sammler':
                $id = '4'; break;
            case '-1':
            case 'not specified':
            case '設定なし':
            case 'indéterminé':
            case 'keine angabe':
                $id = '-1'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    private function getSearchActivitiesId(string $act): string
    {
        switch(strtolower($act)) {
            case '0':
            case 'role-playing':
            case 'ロールプレイ':
            case 'jeu de rôle':
            case 'rollenspiel':
                $id = '0'; break;
            case '1':
            case 'leveling':
            case 'レベリング':
            case 'gain d\'expérience':
            case 'stufenaufstieg':
                $id = '1'; break;
            case '2':
            case 'casual':
            case 'カジュアル':
            case 'jeu décontracté':
            case 'gelegenheitsspieler':
                $id = '2'; break;
            case '3':
            case 'hardcore':
            case 'ハードコア':
            case 'jeu intense':
                $id = '3'; break;
            case '4':
            case 'Dungeons':
            case 'ダンジョン':
            case 'donjons':
                $id = '4'; break;
            case '5':
            case 'guildhests':
            case 'ギルドオーダー':
            case 'opérations de guilde':
            case 'gildengeheiße':
                $id = '5'; break;
            case '6':
            case 'trials':
            case '討伐・討滅戦':
            case 'défis':
            case 'prüfungen':
                $id = '6'; break;
            case '7':
            case 'raids':
            case 'レイド':
                $id = '7'; break;
            case '8':
            case 'pvp':
            case 'jcj':
                $id = '8'; break;
            case '-1':
            case 'not specified':
            case '設定なし':
            case 'indéterminé':
            case 'keine angabe':
                $id = '-1'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    private function getSearchHouseId(string $house): string
    {
        switch(strtolower($house)) {
            case '2':
            case 'estate built':
            case '所有あり':
            case 'logement construit':
            case 'besitzt unterkunft':
                $id = '2'; break;
            case '1':
            case 'plot only':
            case '土地のみ':
            case 'terrain seul':
            case 'nur grundstück':
                $id = '1'; break;
            case '0':
            case 'no estate or plot':
            case '所有なし':
            case 'sans logement ni terrain':
            case 'besitzt keine unterkunft':
                $id = '0'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    private function getSearchJoinId(string $join): string
    {
        switch(strtolower($join)) {
            case '1':
            case 'open':
            case '申請可':
            case 'candidatures acceptées':
            case 'nimmt gesuche an':
                $id = '1'; break;
            case '0':
            case 'closed':
            case '申請不可':
            case 'candidatures refusées':
            case 'nimmt keine gesuche an':
                $id = '0'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    private function getSearchActiveTimeId(string $active): string
    {
        switch(strtolower($active)) {
            case '1':
            case 'weekdays':
            case 'weekdays only':
            case '平日のみ':
            case 'en semaine seulement':
            case 'nur wochentags':
                $id = '1'; break;
            case '2':
            case 'weekends':
            case 'weekends only':
            case '週末のみ':
            case 'le week-end seulement':
            case 'nur Wochenende':
                $id = '2'; break;
            case '3':
            case 'always':
            case '平日/週末':
            case 'toute la semaine':
            case 'jeden Tag':
                $id = '3'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    private function membersCount(int $count): string
    {
        if ($count >= 1 && $count <= 10) {
            $count = '1-10';
        } elseif ($count >= 11 && $count <= 30) {
            $count = '11-30';
        } elseif ($count >= 31 && $count <= 50) {
            $count = '31-50';
        } elseif ($count >= 51) {
            $count = '51-';
        } else {
            $count = '';
        }
        return $count;
    }
    
    private function languageConvert(string $lang): string
    {
        if (!in_array($lang, self::langallowed)) {
            $lang = "na";
        }
        if (in_array($lang, ['na', 'eu'])) {$lang = 'en';}
        return $lang;
    }
    /**
     * Convert order type to Search id
     * @param $order
     * @return string
     */
    private function getSearchOrderId(string $order): string
    {
	    switch(strtolower($order)) {
            case '1':
            case 'charaz':
            case 'fcaz':
            case 'lsaz':
            case 'pvpaz':
                $id = '1'; break;
            case '2':
            case 'charza':
            case 'fcza':
            case 'lsza':
            case 'pvpza':
                $id = '2'; break;
            case '3':
            case 'worldaz':
            case 'fcmembersza':
            case 'lsmembersza':
                $id = '3'; break;
            case '4':
            case 'worldza':
            case 'fcmembersaz':
            case 'lsmembersaz':
                $id = '4'; break;
            case '5':
            case 'levelza':
            case 'fcfoundza':
                $id = '5'; break;
            case '6':
            case 'levelaz':
            case 'fcfoundaz':
                $id = '6'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    /**
     * Convert Grand Company affiliation to Search id
     * @param $gc
     * @return string
     */
    private function getSearchGCId(string $gc): string
    {
	    switch(strtolower($gc)) {
            case '1':
            case 'maelstrom':
            case '黒渦団':
            case 'le maelstrom':
            case 'mahlstrom':
                $id = '1'; break;
            case '2':
            case 'order of the twin adder':
            case '双蛇党 ':
            case 'l\'ordre des deux vipères':
            case 'bruderschaft der morgenviper':
                $id = '2'; break;
            case '3':
            case 'immortal flames':
            case '不滅隊':
            case 'les immortels':
            case 'legion der unsterblichen':
                $id = '3'; break;
            case '0':
            case 'no affiliation':
            case '所属なし':
            case 'sans allégeance':
            case 'keine gesellschaft':
                $id = '0'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    /**
     * Convert tribe/clan to Search id
     * @param $clan
     * @return string
     */
    private function getSearchClanId(string $clan): string
    {
	    switch(strtolower($clan)) {
            case 'hyur':
            case 'ヒューラン':
            case 'hyuran':
                $id = 'race_1'; break;
            case 'midlander':
            case 'ミッドランダー ':
            case 'hyurois':
            case 'wiesländer':
                $id = 'tribe_1'; break;
            case 'highlander':
            case 'ハイランダー':
            case 'hyurgoth':
            case 'hochländer':
                $id = 'tribe_2'; break;
            case 'elezen':
            case 'エレゼン':
            case 'élézen':
                $id = 'race_2'; break;
            case 'wildwood':
            case 'フォレスター':
            case 'sylvestre':
            case 'erlschatten':
                $id = 'tribe_3'; break;
            case 'duskwight':
            case 'シェーダー':
            case 'crépusculaire':
            case 'dunkelalb':
                $id = 'tribe_4'; break;
            case 'lalafell':
            case 'ララフェル':
                $id = 'race_3'; break;
            case 'plainsfolk':
            case 'プレーンフォーク':
            case 'peuple des plaines':
            case 'halmling':
                $id = 'tribe_5'; break;
            case 'dunesfolk':
            case 'デューンフォーク':
            case 'peuple des dunes':
            case 'sandling':
                $id = 'tribe_6'; break;
            case 'miqo\'te':
            case 'ミコッテ':
                $id = 'race_4'; break;
            case 'seeker of the sun':
            case 'サンシーカー':
            case 'tribu du soleil':
            case 'goldtatze':
                $id = 'tribe_7'; break;
            case 'keeper of the moon':
            case 'ムーンキーパー':
            case 'tribu de la lune':
            case 'mondstreuner':
                $id = 'tribe_8'; break;
            case 'roegadyn':
            case 'ルガディン':
                $id = 'race_5'; break;
            case 'sea wolf':
            case 'ゼーヴォルフ':
            case 'clan de la mer':
            case 'seewolf':
                $id = 'tribe_9'; break;
            case 'hellsguard':
            case 'ローエンガルデ':
            case 'clan du feu':
            case 'lohengarde':
                $id = 'tribe_10'; break;
            case 'au ra':
            case 'アウラ':
            case 'ao ra':
                $id = 'race_6'; break;
            case 'raen':
            case 'アウラ・レン':
                $id = 'tribe_11'; break;
            case 'xaela':
            case 'アウラ・ゼラ':
                $id = 'tribe_12'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    /**
     * Convert class name to Search id
     * @param $classname
     * @return string
     */
    private function getSearchClassId(string $classname): string
    {
	    switch(strtolower($classname)) {
            case 'tnk':
                $id = '_job_TANK&classjob=_class_TANK'; break;
            case 'hlr':
                $id = '_job_HEALER&classjob=_class_HEALER'; break;
            case 'dps':
                $id = '_job_DPS&classjob=_class_DPS'; break;
            case 'doh':
                $id = '_class_CRAFTER'; break;
            case 'dol':
                $id = '_class_GATHERER'; break;
            case 'gla':
                $id = '1'; break;
            case 'pld':
                $id = '19'; break;
            case 'mar':
                $id = '3'; break;
            case 'war':
                $id = '21'; break;
            case 'drk':
                $id = '32'; break;
            case 'cnj':
                $id = '6'; break;
            case 'whm':
                $id = '24'; break;
            case 'sch':
                $id = '28'; break;
            case 'ast':
                $id = '33'; break;
            case 'mnk':
                $id = '20'; break;
            case 'drg':
                $id = '22'; break;
            case 'nin':
                $id = '30'; break;
            case 'brd':
                $id = '23'; break;
            case 'mch':
                $id = '31'; break;
            case 'blm':
                $id = '25'; break;
            case 'smn':
                $id = '27'; break;
            case 'sam':
                $id = '34'; break;
            case 'rdm':
                $id = '35'; break;
            case 'pug':
                $id = '2'; break;
            case 'lnc':
                $id = '4'; break;
            case 'rog':
                $id = '29'; break;
            case 'arc':
                $id = '5'; break;
            case 'thm':
                $id = '7'; break;
            case 'acn':
                $id = '26'; break;
            case 'crp':
                $id = '8'; break;
            case 'bsm':
                $id = '9'; break;
            case 'arm':
                $id = '10'; break;
            case 'gsm':
                $id = '11'; break;
            case 'ltw':
                $id = '12'; break;
            case 'wvr':
                $id = '13'; break;
            case 'alc':
                $id = '14'; break;
            case 'cul':
                $id = '15'; break;
            case 'min':
                $id = '16'; break;
            case 'btn':
                $id = '17'; break;
            case 'fsh':
                $id = '18'; break;
            default:
                $id = '';
        }
        return $id;
    }
    
    /**
     * Convert class name to Deep Dungeon hash/id
     * @param $classname
     * @return string
     */
    private function getDeepDungeonClassId(string $classname): string
    {
        switch(strtolower($classname)) {
            case 'gla':
            case 'pld':
                $id = '125bf9c1198a3a148377efea9c167726d58fa1a5'; break;
            case 'mar':
            case 'war':
                $id = '741ae8622fa496b4f98b040ff03f623bf46d790f'; break;
            case 'drk':
                $id = 'c31f30f41ab1562461262daa74b4d374e633a790'; break;
            case 'cnj':
            case 'whm':
                $id = '56d60f8dbf527ab9a4f96f2906f044b33e7bd349'; break;
            case 'sch':
                $id = '56f91364620add6b8e53c80f0d5d315a246c3b94'; break;
            case 'ast':
                $id = 'eb7fb1a2664ede39d2d921e0171a20fa7e57eb2b'; break;
            case 'mnk':
            case 'pug':
                $id = '46fcce8b2166c8afb1d76f9e1fa3400427c73203'; break;
            case 'drg':
            case 'lnc':
                $id = 'b16807bd2ef49bd57893c56727a8f61cbaeae008'; break;
            case 'nin':
            case 'rog':
                $id = 'e8f417ab2afdd9a1e608cb08f4c7a1ae3fe4a441'; break;
            case 'brd':
            case 'arc':
                $id = 'f50dbaf7512c54b426b991445ff06a6697f45d2a'; break;
            case 'mch':
                $id = '773aae6e524e9a497fe3b09c7084af165bef434d'; break;
            case 'blm':
            case 'thm':
                $id = 'f28896f2b4a22b014e3bb85a7f20041452319ff2'; break;
            case 'acn':
            case 'smn':
                $id = '9ef51b0f36842b9566f40c5e3de2c55a672e4607'; break;
            case 'sam':
                $id = '7c3485028121b84720df20de7772371d279d097d'; break;
            case 'rdm':
                $id = '55a98ea6cf180332222184e9fb788a7941a03ec3'; break;
            default:
                $id = '';
        }
        return $id;
    }
}
?>