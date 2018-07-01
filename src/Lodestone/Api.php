<?php

namespace Lodestone;

// use all the things
use Lodestone\Modules\{
    Logging\Logger, Http\Routes
};

use Lodestone\Entities\Search\{
    SearchCharacter,
    SearchFreeCompany,
    SearchLinkshell
};

use Lodestone\Parser\{
    Character\Parser as CharacterParser,
    Character\Search as CharacterSearch,
    CharacterFriends\Parser as CharacterFriendsParser,
    CharacterFollowing\Parser as CharacterFollowingParser,
    
    Achievements\Parser as AchievementsParser,
    
    FreeCompany\Parser as FreeCompanyParser,
    FreeCompany\Search as FreeCompanySearch,
    FreeCompanyMembers\Parser as FreeCompanyMembersParser,
    
    Linkshell\Parser as LinkshellParser,
    Linkshell\Search as LinkshellSearch,
    
    PvPTeam\Parser as PvPTeamParser,
    PvPTeam\Search as PvPTeamSearch,
    
    Lodestone
};

/**
 * Provides quick functions to various parsing routes
 *
 * Class Api
 * @package Lodestone
 */
class Api
{
    private $useragent = '';
    private $language = 'na';
    
    /**
     * @test .
     * @return Lodestone/Lodestone
     */
    private function getLodeStoneInstance()
    {
        return new Lodestone();
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
     * @test Premium Virtue,Phoenix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return SearchCharacter
     */
    public function searchCharacter($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = (new Routes($this->language))::$LODESTONE_CHARACTERS_SEARCH_URL;
        return (new CharacterSearch())->url($url . $urlBuilder->get(), $this->useragent)->parse();
    }

    /**
     * @test Equilibrium,Pheonix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return SearchFreeCompany
     */
    public function searchFreeCompany($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = (new Routes($this->language))::$LODESTONE_FREECOMPANY_SEARCH_URL;
        return (new FreeCompanySearch())->url($url . $urlBuilder->get(), $this->useragent)->parse();
    }

    /**
     * @test Monster Hunt
     * @param $name
     * @param $server
     * @param $page
     * @return SearchLinkshell
     */
    public function searchLinkshell($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = (new Routes($this->language))::$LODESTONE_LINKSHELL_SEARCH_URL;
        return (new LinkshellSearch())->url($url . $urlBuilder->get(), $this->useragent)->parse();
    }
    
    /**
     * @test Ankora
     * @param $name
     * @param $server
     * @param $page
     * @return SearchPvPTeam
     */
    public function searchPvPTeam($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = (new Routes($this->language))::$LODESTONE_PVPTEAM_SEARCH_URL;
        return (new PvPTeamSearch())->url($url . $urlBuilder->get(), $this->useragent)->parse();
    }

    /**
     * @test 730968
     * @param $id
     * @return Entities\Character\CharacterProfile
     */
    public function getCharacter($id)
    {
        $url = sprintf((new Routes($this->language))::$LODESTONE_CHARACTERS_URL, $id);
        return (new CharacterParser($id))->url($url, $this->useragent)->parse();
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
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf((new Routes($this->language))::$LODESTONE_CHARACTERS_FRIENDS_URL, $id);
        return (new CharacterFriendsParser())->url($url . $urlBuilder->get(), $this->useragent)->parse();
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
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf((new Routes($this->language))::$LODESTONE_CHARACTERS_FOLLOWING_URL, $id);
        return (new CharacterFollowingParser())->url($url . $urlBuilder->get(), $this->useragent)->parse();
    }

    /**
     * @test 730968
     * @param $id
     * @param int $type = 1
     * @param bool $includeUnobtained = false
     * @param int $category = false
     * @return Entities\Character\Achievements
     */
    public function getCharacterAchievements($id, $type = 1, bool $includeUnobtained = false, $category = false, bool $details = false, $detailsAchievementId = false)
    {
        if ($details === true && $detailsAchievementId !== false) {
            return (new AchievementsParser($type, $id))->parse($includeUnobtained, $details, $detailsAchievementId, $this->useragent, $this->language);
        } else {
            $url = $category === false
                ? sprintf((new Routes($this->language))::$LODESTONE_ACHIEVEMENTS_URL, $id, $type)
                : sprintf((new Routes($this->language))::$LODESTONE_ACHIEVEMENTS_CAT_URL, $id, $type);
            
            return (new AchievementsParser($type, $id))->url($url, $this->useragent)->parse($includeUnobtained, $details, $this->useragent, $this->language);
        }
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @return Entities\FreeCompany\FreeCompany
     */
    public function getFreeCompany($id)
    {
        $url = sprintf((new Routes($this->language))::$LODESTONE_FREECOMPANY_URL, $id);
        return (new FreeCompanyParser($id))->url($url, $this->useragent)->parse();
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @param bool $page
     * @return Entities\FreeCompany\FreeCompanyMembers
     */
    public function getFreeCompanyMembers($id, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf((new Routes($this->language))::$LODESTONE_FREECOMPANY_MEMBERS_URL, $id);
        return (new FreeCompanyMembersParser())->url($url . $urlBuilder->get(), $this->useragent)->parse();
    }

    /**
     * @test 19984723346535274
     * @param $id
     * @param bool $page
     * @return Entities\Linkshell\Linkshell
     */
    public function getLinkshellMembers($id, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf((new Routes($this->language))::$LODESTONE_LINKSHELL_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new LinkshellParser($id))->url($url, $this->useragent)->parse();
    }
    
    /**
     * @test c7a8e4e6fbb5aa2a9488015ed46a3ec3d97d7d0d
     * @param $id
     * @return Entities\PvPTeam\PvPTeam
     */
    public function getPvPTeamMembers($id)
    {
        $urlBuilder = new UrlBuilder();

        $url = sprintf((new Routes($this->language))::$LODESTONE_PVPTEAM_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new PvPTeamParser($id))->url($url, $this->useragent)->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneBanners()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_BANNERS, $this->useragent)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNews()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_NEWS, $this->useragent)->parseTopics();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneTopics()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_TOPICS, $this->useragent)->parseTopics();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNotices()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_NOTICES, $this->useragent)->parseNotices();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneMaintenance()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_MAINTENANCE, $this->useragent)->parseMaintenance();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneUpdates()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_UPDATES, $this->useragent)->parseUpdates();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneStatus()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_STATUS, $this->useragent)->parseStatus();
    }

    /**
     * @test .
     * @return array
     */
    public function getWorldStatus()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_WORLD_STATUS, $this->useragent)->parseWorldStatus();
    }

    /**
     * @test .
     * @return mixed
     */
    public function getDevBlog()
    {
        return $this->getLodeStoneInstance()->url((new Routes($this->language))::$LODESTONE_DEV_BLOG, $this->useragent)->parseDevBlog();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getDevPosts()
    {
        $lodestone = new Lodestone();
        $lodestone->url((new Routes($this->language))::$LODESTONE_FORUMS, $this->useragent);

        // todo : support multiple languages
        $lang = 'en';
        $devTrackerUrl = $lodestone->parseDevTrackingUrl($lang);
        if (!$devTrackerUrl) {
            return false;
        }

        // get dev tracking search results
        $lodestone->url($devTrackerUrl, $this->useragent);

        // get dev posts
        $devLinks = $lodestone->parseDevPostLinks();
        if (!$devLinks) {
            return false;
        }

        // get all dev posts
        $data = [];
        foreach($devLinks as $url) {
            $lodestone->url($url, $this->useragent);
            $postId = str_ireplace('post', null, explode('#', $url)[1]);
            $post = $lodestone->parseDevPost($postId);
            $post['id'] = $postId;
            $data[] = $post;
        }

        return $data;
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/thefeast/
     *
     * @test .
     * @param bool $season
     * @param array $params
     * @return array
     */
    public function getFeast($season = false, $params = [])
    {
        if ($season !== false && is_numeric($season)) {
            $url = sprintf((new Routes($this->language))::$LODESTONE_FEAST_SEASON, $season);
        } else {
            $url = (new Routes($this->language))::$LODESTONE_FEAST_CURRENT;
        }

        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url($url . $urlBuilder->get(), $this->useragent)->parseFeast();
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/deepdungeon/
     *
     * @test .
     * @param array $params
     * @return array
     */
    public function getDeepDungeon($params = [])
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url((new Routes($this->language))::$LODESTONE_DEEP_DUNGEON . $urlBuilder->get(), $this->useragent)->parseDeepDungeon();
    }
    
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
        if (!in_array($language, ['na', 'jp', 'eu', 'fr', 'de'])) {
            $language = "";
        }
        $this->language = $language;
        return $this;
    }
}
