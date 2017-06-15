<?php

namespace Lodestone;

// use all the things
use Lodestone\Modules\{
    XIVDB, Logger, Routes
};
use Lodestone\Parser\{
    Achievements,
    Character\Parser as CharacterParser,
    CharacterFollowing,
    CharacterFriends,
    FreeCompany,
    FreeCompanyMembers,
    Linkshell,
    Lodestone,
    Search
};

/**
 * Provides quick functions to various parsing routes
 *
 * Class Api
 * @package Lodestone
 */
class Api
{
    /** @var XIVDB $xivdb */
    public $xivdb;

    /**
     * Api constructor.
     */
    public function __construct()
    {
        $this->xivdb = new XIVDB();

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
     * @return array|bool
     */
    public function searchCharacter($name, $server = false, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_CHARACTERS_SEARCH_URL;
        return (new Search())->url($url . $urlBuilder->get())->parseCharacterSearch();
    }

    /**
     * @test Equilibrium,Pheonix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return array|bool
     */
    public function searchFreeCompany($name, $server = false, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_FREECOMPANY_SEARCH_URL;
        return (new Search())->url($url . $urlBuilder->get())->parseFreeCompanySearch();
    }

    /**
     * @test Monster Hunt
     * @param $name
     * @param $server
     * @param $page
     * @return array|bool
     */
    public function searchLinkshell($name, $server = false, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_LINKSHELL_SEARCH_URL;
        return (new Search())->url($url . $urlBuilder->get())->parseLinkshellSearch();
    }

    /**
     * @test 730968
     * @param $id
     * @return Entities\Character\CharacterProfile
     */
    public function getCharacter($id)
    {
        $url = sprintf(Routes::LODESTONE_CHARACTERS_URL, $id);
        return (new CharacterParser($id))->url($url)->parse();
    }

    /**
     * @test 730968
     * @softfail true
     * @param $id
     * @param $page
     * @return array|bool
     */
    public function getCharacterFriends($id, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FRIENDS_URL, $id);
        return (new CharacterFriends())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 730968
     * @softfail true
     * @param $id
     * @param $page
     * @return array|bool
     */
    public function getCharacterFollowing($id, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FOLLOWING_URL, $id);
        return (new CharacterFollowing())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 730968
     * @param $id
     * @param int $kind
     * @return array|bool
     */
    public function getCharacterAchievements($id, $kind = 1)
    {
        $url = sprintf(Routes::LODESTONE_ACHIEVEMENTS_URL, $id, $kind);
        return (new Achievements())->url($url)->parse();
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @return array|bool
     */
    public function getFreeCompany($id)
    {
        $url = sprintf(Routes::LODESTONE_FREECOMPANY_URL, $id);
        return (new FreeCompany())->url($url)->parse($id);
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @param $page
     * @return array|bool
     */
    public function getFreeCompanyMembers($id, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_FREECOMPANY_MEMBERS_URL, $id);
        return (new FreeCompanyMembers())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 19984723346535274
     * @param $id
     * @param $page
     * @return array|bool
     */
    public function getLinkshellMembers($id, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_LINKSHELL_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new Linkshell())->url($url)->parse($id);
    }

    /**
     * @test .
     * @return Lodestone/Lodestone
     */
    private function getLodeStoneInstance()
    {
        return new Lodestone();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneBanners()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_BANNERS)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNews()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_NEWS)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneTopics()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_TOPICS)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNotices()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_NOTICES)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneMaintenance()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_MAINTENANCE)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneUpdates()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_UPDATES)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneStatus()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_STATUS)->parseBanners();
    }

    /**
     * @test .
     * @return array
     */
    public function getWorldStatus()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_WORLD_STATUS)->parseWorldStatus();
    }

    /**
     * @test .
     * @return mixed
     */
    public function getDevBlog()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_DEV_BLOG)->parseDevBlog();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getDevPosts()
    {
        $lodestone = new Lodestone();
        $lodestone->url(Routes::LODESTONE_FORUMS);

        // todo : support multiple languages
        $lang = 'en';
        $devTrackerUrl = $lodestone->parseDevTrackingUrl($lang);
        if (!$devTrackerUrl) {
            return false;
        }

        // get dev tracking search results
        $lodestone->url($devTrackerUrl);

        // get dev posts
        $devLinks = $lodestone->parseDevPostLinks();
        if (!$devLinks) {
            return false;
        }

        // get all dev posts
        $data = [];
        foreach($devLinks as $url) {
            $lodestone->url($url);
            $postId = str_ireplace('post', null, explode('#', $url)[1]);

            $data[] = [
                'id' => $postId,
                'thread' => $lodestone->parseDevPost($postId),
            ];
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
        $url = Routes::LODESTONE_FEAST_CURRENT;
        switch($season) {
            case 1: Routes::LODESTONE_FEAST_SEASON_1; break;
            case 2: Routes::LODESTONE_FEAST_SEASON_2; break;
            case 3: Routes::LODESTONE_FEAST_SEASON_3; break;
            case 4: Routes::LODESTONE_FEAST_SEASON_4; break;
        }

        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url($url . $urlBuilder->get())->parseFeast();
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

        return (new Lodestone())->url(Routes::LODESTONE_DEEP_DUNGEON . $urlBuilder->get())->parseDeepDungeon();
    }
}