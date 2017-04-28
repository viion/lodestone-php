<?php

namespace Lodestone;

// use all the things
use Lodestone\Modules\{XIVDB, Logger,Routes};
use Lodestone\Parser\{
    Achievements,
    Character,
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
     * @param $id
     * @return array|bool
     */
    public function searchCharacter($name, $server, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_CHARACTERS_SEARCH_URL;
        return (new Search())->url($url . $urlBuilder->get())->parseCharacterSearch();
    }

    /**
     * @param $id
     * @return array|bool
     */
    public function searchFreeCompany($name, $server, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_FREECOMPANY_SEARCH_URL;
        return (new Search())->url($url . $urlBuilder->get())->parseFreeCompanySearch();
    }

    /**
     * @param $name
     * @param $server
     * @param $page
     * @return array|bool
     */
    public function searchLinkshell($name, $server, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_LINKSHELL_SEARCH_URL;
        return (new Search())->url($url . $urlBuilder->get())->parseLinkshellSearch();
    }

    /**
     * @param $id
     * @param bool $hash
     * @return array|bool
     */
    public function getCharacter($id, $hash = false)
    {
        $url = sprintf(Routes::LODESTONE_CHARACTERS_URL, $id);
        return $hash
            ? (new Character())->url($url)->parse(true)
            : (new Character())->url($url)->parse();
    }

    /**
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
     * @param $id
     * @return array|bool
     */
    public function getFreeCompany($id)
    {
        $url = sprintf(Routes::LODESTONE_FREECOMPANY_URL, $id);
        return (new FreeCompany())->url($url)->parse();
    }

    /**
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
     * @param $id
     * @param $page
     * @return array|bool
     */
    public function getLinkshellMembers($id, $page = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_LINKSHELL_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new Linkshell())->url($url)->parse();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneBanners()
    {
        return (new Lodestone())->url(Routes::LODESTONE_BANNERS)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneNews()
    {
        return (new Lodestone())->url(Routes::LODESTONE_NEWS)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneTopics()
    {
        return (new Lodestone())->url(Routes::LODESTONE_TOPICS)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneNotices()
    {
        return (new Lodestone())->url(Routes::LODESTONE_NOTICES)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneMaintenance()
    {
        return (new Lodestone())->url(Routes::LODESTONE_MAINTENANCE)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneUpdates()
    {
        return (new Lodestone())->url(Routes::LODESTONE_UPDATES)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneStatus()
    {
        return (new Lodestone())->url(Routes::LODESTONE_STATUS)->parseBanners();
    }

    /**
     * @return array
     */
    public function getWorldStatus()
    {
        return (new Lodestone())->url(Routes::LODESTONE_WORLD_STATUS)->parseWorldStatus();
    }

    /**
     * @return mixed
     */
    public function getDevBlog()
    {
        return (new Lodestone())->url(Routes::LODESTONE_DEV_BLOG)->parseDevBlog();
    }

    /**
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
     * @param bool $season
     * @param bool $params
     * @return array
     */
    public function getFeast($season = false, $params = false)
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
     * @param bool $params
     * @return array
     */
    public function getDeepDungeon($params = false)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url(Routes::LODESTONE_DEEP_DUNGEON . $urlBuilder->get())->parseDeepDungeon();
    }
}