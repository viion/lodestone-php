<?php

namespace Lodestone;

// use all the things
use Lodestone\Modules\{
    Logging\Benchmark, Logging\Logger, Http\Routes, Http\HttpRequest
};

use Lodestone\Modules\Validator;

use Lodestone\Search\{
    Character as CharacterSearch,
    FreeCompany as FreeCompanySearch,
    Linkshell as LinkshellSearch,
    PvPTeam as PvPTeamSearch,
};

use Lodestone\Parser\{
    Character as CharacterParser,
    Friends as CharacterFriendsParser,
    Following as CharacterFollowingParser,
    
    Achievements as AchievementsParser,
    
    FreeCompany as FreeCompanyParser,
    FreeCompanyMembers as FreeCompanyMembersParser,
    
    Linkshell as LinkshellParser,
    
    PvPTeam as PvPTeamParser,
    
    Lodestone as LodeStoneParser
};

/**
 * Provides quick functions to various parsing routes
 *
 * Class Api
 * @package Lodestone
 */
class Api
{
    #Use traits
    use Modules\Converters;
    use Search\Functions;
    
    const langallowed = ['na', 'jp', 'eu', 'fr', 'de'];
    
    private $useragent = '';
    private $language = 'https://na';
    private $lang = 'na';
    private $url = '';
    private $type = '';
    private $typesettings = [];
    private $html = '';
    public $result = null;
    
    /**
     * Set optional useragent
     *
     * @test .
     * @param string $useragent
     * @return this
     */
    public function setUseragent(string $useragent = "")
    {
        $this->useragent = $useragent;
        return $this;
    }
    
    /**
     * Set optional langauge
     *
     * @test .
     * @param string $useragent
     * @return this
     */
    public function setLanguage(string $language = "")
    {
        if (!in_array($language, self::langallowed)) {
            $language = "na";
        }
        $this->lang = $language;
        $this->language = 'https://'.$language;
        return $this;
    }
    
    /**
     * Parse the generated URL
     * @return array
     */
    private function parse()
    {
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        if (empty($this->url) | empty($this->type) | empty($this->language)) {
            // return error;
        } else {
            $http = new HttpRequest($this->useragent);
            $this->html = $http->get($this->url);
            switch($this->type) {
                case 'searchCharacter':
                    $this->result = (new CharacterSearch($this->html))->results;
                    break;
                case 'searchFreeCompany':
                    $this->result = (new FreeCompanySearch($this->html))->results;
                    break;
                case 'searchLinkshell':
                    $this->result = (new LinkshellSearch($this->html))->results;
                    break;
                case 'searchPvPTeam':
                    $this->result = (new PvPTeamSearch($this->html))->results;
                    break;
                case 'Character':
                    if (!empty($this->typesettings)) {
                        $this->result = (new CharacterParser($this->typesettings['id'], $this->html))->results;
                    }
                    break;
                case 'CharacterFriends':
                    $this->result = (new CharacterFriendsParser($this->html))->results;
                    break;
                case 'CharacterFollowing':
                    $this->result = (new CharacterFollowingParser($this->html))->results;
                    break;
                case 'Achievements':
                    if (!empty($this->typesettings)) {
                        if ($this->typesettings['achievementId']) {
                            $this->result = (new AchievementsParser($this->html, false, $this->typesettings['achievementId']))->results;
                        } else {
                            $this->result = (new AchievementsParser($this->html, $this->typesettings['includeUnobtained'], false))->results;
                            if ($this->typesettings['details']) {
                                foreach ($this->result->achievements as $key=>$ach) {
                                    $this->result->achievements[$key] = (new Api)->setLanguage($this->lang)->setUseragent($this->useragent)->getCharacterAchievements($this->typesettings['id'], $ach->id, 1, false, false, true);
                                }
                            }
                        }
                    }
                    break;
                case 'FreeCompany':
                    if (!empty($this->typesettings)) {
                        $this->result = (new FreeCompanyParser($this->typesettings['id'], $this->html))->results;
                    }
                    break;
                case 'FreeCompanyMembers':
                    if (!empty($this->typesettings)) {
                        $this->result = (new FreeCompanyMembersParser($this->typesettings['id'], $this->html))->results;
                    }
                    break;
                case 'LinkshellMembers':
                    if (!empty($this->typesettings)) {
                        $this->result = (new LinkshellParser($this->typesettings['id'], $this->html))->results;
                    }
                    break;
                case 'PvPTeamMembers':
                    if (!empty($this->typesettings)) {
                        $this->result = (new PvPTeamParser($this->typesettings['id'], $this->html))->results;
                    }
                    break;
                case 'Banners':
                    $this->result = (new LodeStoneParser($this->html))->parseBanners();
                    break;
                case 'News':
                    $this->result = (new LodeStoneParser($this->html))->parseTopics();
                    break;
                case 'Notices':
                    $this->result = (new LodeStoneParser($this->html))->parseNotices($this->language);
                    break;
                case 'WorldStatus':
                    $this->result = (new LodeStoneParser($this->html))->parseWorldStatus();
                    break;
                case 'DevBlog':
                    $xml = simplexml_load_string($this->html, null, LIBXML_NOCDATA);
                    $this->result = json_decode(json_encode($xml), true)['entry'];
                    break;
                case 'Feast':
                    $this->result = (new LodeStoneParser($this->html))->parseFeast();
                    break;
                case 'DeepDungeon':
                    $this->result = (new LodeStoneParser($this->html))->parseDeepDungeon();
                    break;
            }
        }
        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
        return $this->result;
    }

    /**
     * Get all entries in the log (Accessible
     * even with log disabled)
     * @return array
     */
    public function getLog()
    {
        return Logger::$log;
    }

    /**
     * @test 730968
     * @param $id
     * @return Entities\Character\CharacterProfile
     */
    public function getCharacter($id)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_CHARACTERS_URL, $id);
        $this->type = 'Character';
        $this->typesettings = ['id'=>$id];
        return $this->parse();
    }

    /**
     * @test 730968
     * @softfail true
     * @param $id
     * @param $page
     * @return Entities\Character\CharacterFriends
     */
    public function getCharacterFriends($id, $page = 1)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_CHARACTERS_FRIENDS_URL.'/?page='.$page, $id);
        $this->type = 'CharacterFriends';
        return $this->parse();
    }

    /**
     * @test 730968
     * @softfail true
     * @param $id
     * @param $page
     * @return Entities\Character\CharacterFollowing
     */
    public function getCharacterFollowing($id, $page = 1)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_CHARACTERS_FOLLOWING_URL.'/?page='.$page, $id);
        $this->type = 'CharacterFollowing';
        return $this->parse();
    }

    /**
     * @test 730968
     * @param $id
     * @param int $kind = 1
     * @param bool $includeUnobtained = false
     * @param int $category = false
     * @return Entities\Character\Achievements
     */
    public function getCharacterAchievements($id, $achievementId = false, int $kind = 1, bool $includeUnobtained = false, bool $category = false, bool $details = false)
    {
        if ($details === true && $achievementId !== false) {
            $this->url = sprintf($this->language.Routes::LODESTONE_ACHIEVEMENTS_DET_URL, $id, $achievementId);
        } else {
            if ($category === false) {
                $this->url = sprintf($this->language.Routes::LODESTONE_ACHIEVEMENTS_URL, $id, $this->getAchKindId($kind));
            } else {
                $this->url = sprintf($this->language.Routes::LODESTONE_ACHIEVEMENTS_CAT_URL, $id, $this->getAchCatId($kind));
            }
        }
        $this->typesettings['id'] = $id;
        $this->typesettings['includeUnobtained'] = $includeUnobtained;
        $this->typesettings['details'] = $details;
        $this->typesettings['achievementId'] = $achievementId;
        $this->type = 'Achievements';
        return $this->parse();
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @return Entities\FreeCompany\FreeCompany
     */
    public function getFreeCompany($id)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_FREECOMPANY_URL, $id);
        $this->type = 'FreeCompany';
        $this->typesettings = ['id'=>$id];
        return $this->parse();
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @param bool $page
     * @return Entities\FreeCompany\FreeCompanyMembers
     */
    public function getFreeCompanyMembers($id, $page = 1)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_FREECOMPANY_MEMBERS_URL.'/?page='.$page, $id);
        $this->type = 'FreeCompanyMembers';
        $this->typesettings = ['id'=>$id];
        return $this->parse();
    }

    /**
     * @test 19984723346535274
     * @param $id
     * @param bool $page
     * @return Entities\Linkshell\Linkshell
     */
    public function getLinkshellMembers($id, $page = 1)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_LINKSHELL_MEMBERS_URL.'/?page='.$page, $id);
        $this->type = 'LinkshellMembers';
        $this->typesettings = ['id'=>$id];
        return $this->parse();
    }
    
    /**
     * @test c7a8e4e6fbb5aa2a9488015ed46a3ec3d97d7d0d
     * @param $id
     * @return Entities\PvPTeam\PvPTeam
     */
    public function getPvPTeamMembers($id)
    {
        $this->url = sprintf($this->language.Routes::LODESTONE_PVPTEAM_MEMBERS_URL, $id);
        $this->type = 'PvPTeamMembers';
        $this->typesettings = ['id'=>$id];
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneBanners()
    {
        $this->url = $this->language.Routes::LODESTONE_BANNERS;
        $this->type = 'Banners';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNews()
    {
        $this->url = $this->language.Routes::LODESTONE_NEWS;
        $this->type = 'News';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneTopics(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_TOPICS.'?page='.$page;
        $this->type = 'News';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNotices(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_NOTICES.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneMaintenance(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_MAINTENANCE.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneUpdates(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_UPDATES.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneStatus(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_STATUS.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array
     */
    public function getWorldStatus()
    {
        $this->url = $this->language.Routes::LODESTONE_WORLD_STATUS;
        $this->type = 'WorldStatus';
        return $this->parse();
    }

    /**
     * @test .
     * @return mixed
     */
    public function getDevBlog()
    {
        $this->url = $this->language.Routes::LODESTONE_DEV_BLOG;
        $this->type = 'DevBlog';
        return $this->parse();
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/thefeast/
     *
     * @test .
     * @param bool $season
     * @param array $params
     * @return array
     */
    public function getFeast(int $season = 0, string $dcgroup = '', string $rank_type = 'all')
    {
        if ($season == 0) {
            $season = '';
        }
        $query = $this->queryBuilder([
            'dcgroup' => $dcgroup,
            'rank_type' => $rank_type,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_FEAST, strval($season)).$query;
        $this->type = 'Feast';
        return $this->parse();
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/deepdungeon/
     *
     * @test .
     * @param array $params
     * @return array
     */
    public function getDeepDungeon(int $id = 1, string $dcgroup = '', string $solo_party = 'party', string $subtype = '')
    {
        if ($id == 1) {
            $id = '';
        }
        if ($subtype) {
            $solo_party = 'solo';
        }
        $query = $this->queryBuilder([
            'dcgroup' => $dcgroup,
            'solo_party' => $solo_party,
            'subtype' => $subtype,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_DEEP_DUNGEON, strval($id)).$query;
        $this->type = 'DeepDungeon';
        return $this->parse();
    }
    
    private function queryBuilder(array $params): string
    {
        Validator::getInstance()
            ->check($params, "Query params provided to the API")
            ->isArray();
        $query = [];
        foreach($params as $param => $value) {
            if (empty($value) && $value !== '0') {
                continue;
            }
            if (in_array($param, ['class_job', 'subtype'])) {
                $param = 'subtype';
                $value = $this->getDeepDungeonClassId($value);
            }
            if ($param == 'solo_party') {
                if (!in_array($value, ['party', 'solo'])) {
                    $value = '';
                }
            }
            if ($param == 'classjob') {
                $value = $this->getSearchClassId($value);
            }
            if ($param == 'race_tribe') {
                $value = $this->getSearchClanId($value);
            }
            if ($param == 'order') {
                $value = $this->getSearchOrderId($value);
            }
            if ($param == 'blog_lang') {
                $value = $this->languageConvert($value);
            }
            if ($param == 'character_count') {
                $value = $this->membersCount($value);
            }
            if ($param == 'activetime') {
                $value = $this->getSearchActiveTimeId($value);
            }
            if ($param == 'join') {
                $value = $this->getSearchJoinId($value);
            }
            if ($param == 'house') {
                $value = $this->getSearchHouseId($value);
            }
            if ($param == 'activities') {
                $value = $this->getSearchActivitiesId($value);
            }
            if ($param == 'roles') {
                $value = $this->getSearchRolesId($value);
            }
            if ($param == 'rank_type') {
                $value = $this->getFeastRankId($value);
            }
            if (!empty($value) || $value === '0') {
                $query[] = $param .'='. $value;
            }
        }
        return '?'. implode('&', $query);
    }
}
