<?php

namespace Lodestone\Modules\Http;

/**
 * URL's for Lodestone content
 *
 * The API is currently built based on the NA lodestone,
 * changing the lodestone to de/fr may work but this has
 * not been tested and can't be guaranteed.
 *
 * Class Routes
 * @package Lodestone\Modules
 */
class Routes
{
    // base URL
    const LODESTONE_URL_BASE = '.finalfantasyxiv.com/';
    
    // characters
    static $LODESTONE_URL = '';
    static $LODESTONE_CHARACTERS_URL = '';
    static $LODESTONE_CHARACTERS_FRIENDS_URL = '';
    static $LODESTONE_CHARACTERS_FOLLOWING_URL = '';
    static $LODESTONE_CHARACTERS_SEARCH_URL = '';
    static $LODESTONE_ACHIEVEMENTS_URL = '';
    static $LODESTONE_ACHIEVEMENTS_CAT_URL = '';
    static $LODESTONE_ACHIEVEMENTS_DET_URL = '';

    // free company
    static $LODESTONE_FREECOMPANY_URL = '';
    static $LODESTONE_FREECOMPANY_SEARCH_URL = '';
    static $LODESTONE_FREECOMPANY_MEMBERS_URL = '';

    // linkshell
    static $LODESTONE_LINKSHELL_SEARCH_URL = '';
    static $LODESTONE_LINKSHELL_MEMBERS_URL = '';
    
    // linkshell
    static $LODESTONE_PVPTEAM_SEARCH_URL = '';
    static $LODESTONE_PVPTEAM_MEMBERS_URL = '';

    // homepage
    static $LODESTONE_BANNERS = '';
    static $LODESTONE_NEWS = '';
    static $LODESTONE_TOPICS = '';
    static $LODESTONE_NOTICES = '';
    static $LODESTONE_MAINTENANCE = '';
    static $LODESTONE_UPDATES = '';
    static $LODESTONE_STATUS = '';

    // feast
    static $LODESTONE_FEAST_CURRENT = '';
    static $LODESTONE_FEAST_SEASON = '';

    // deep dungeon
    static $LODESTONE_DEEP_DUNGEON = '';

    // world status
    static $LODESTONE_WORLD_STATUS = '';

    // devblog
    static $LODESTONE_DEV_BLOG = '';

    // forums
    const LODESTONE_FORUMS = 'https://forum.square-enix.com/ffxiv/';
    const LODESTONE_FORUMS_HOMEPAGE = self::LODESTONE_FORUMS .'forum.php';
    
    public function __construct($language = "")
    {
        if (!in_array($language, ['na', 'jp', 'eu', 'fr', 'de'])) {
            $language = "";
        }
        if (empty($language)) {
            self::$LODESTONE_URL = 'https://na'.self::LODESTONE_URL_BASE;
        } else {
            self::$LODESTONE_URL = 'https://'.$language.self::LODESTONE_URL_BASE;
        }
        self::$LODESTONE_CHARACTERS_URL = self::$LODESTONE_URL . 'lodestone/character/%s/';
        self::$LODESTONE_CHARACTERS_FRIENDS_URL = self::$LODESTONE_URL . 'lodestone/character/%s/friend';
        self::$LODESTONE_CHARACTERS_FOLLOWING_URL = self::$LODESTONE_URL . 'lodestone/character/%s/following';
        self::$LODESTONE_CHARACTERS_SEARCH_URL = self::$LODESTONE_URL .'lodestone/character';
        self::$LODESTONE_ACHIEVEMENTS_URL = self::$LODESTONE_URL . 'lodestone/character/%s/achievement/kind/%s/';
        self::$LODESTONE_ACHIEVEMENTS_CAT_URL = self::$LODESTONE_URL . 'lodestone/character/%s/achievement/category/%s/';
        self::$LODESTONE_ACHIEVEMENTS_DET_URL = self::$LODESTONE_URL . 'lodestone/character/%s/achievement/detail/%s/';
    
        // free company
        self::$LODESTONE_FREECOMPANY_URL = self::$LODESTONE_URL . 'lodestone/freecompany/%s/';
        self::$LODESTONE_FREECOMPANY_SEARCH_URL = self::$LODESTONE_URL . 'lodestone/freecompany';
        self::$LODESTONE_FREECOMPANY_MEMBERS_URL = self::$LODESTONE_URL .'lodestone/freecompany/%s/member/';
    
        // linkshell
        self::$LODESTONE_LINKSHELL_SEARCH_URL = self::$LODESTONE_URL . 'lodestone/linkshell';
        self::$LODESTONE_LINKSHELL_MEMBERS_URL = self::$LODESTONE_URL .'lodestone/linkshell/%s/';
        
        // linkshell
        self::$LODESTONE_PVPTEAM_SEARCH_URL = self::$LODESTONE_URL . 'lodestone/pvpteam';
        self::$LODESTONE_PVPTEAM_MEMBERS_URL = self::$LODESTONE_URL .'lodestone/pvpteam/%s/';
    
        // homepage
        self::$LODESTONE_BANNERS = self::$LODESTONE_URL .'lodestone/';
        self::$LODESTONE_NEWS = self::$LODESTONE_URL .'lodestone/news/';
        self::$LODESTONE_TOPICS = self::$LODESTONE_URL .'lodestone/topics/';
        self::$LODESTONE_NOTICES = self::$LODESTONE_URL .'lodestone/news/category/1';
        self::$LODESTONE_MAINTENANCE = self::$LODESTONE_URL .'lodestone/news/category/2';
        self::$LODESTONE_UPDATES = self::$LODESTONE_URL .'lodestone/news/category/3';
        self::$LODESTONE_STATUS = self::$LODESTONE_URL .'lodestone/news/category/4';
    
        // feast
        self::$LODESTONE_FEAST_CURRENT = self::$LODESTONE_URL .'lodestone/ranking/thefeast/';
        self::$LODESTONE_FEAST_SEASON = self::$LODESTONE_FEAST_CURRENT . 'result/%s/';
    
        // deep dungeon
        self::$LODESTONE_DEEP_DUNGEON = self::$LODESTONE_URL .'lodestone/ranking/deepdungeon/';
    
        // world status
        self::$LODESTONE_WORLD_STATUS = self::$LODESTONE_URL .'lodestone/worldstatus/';
    
        // devblog
        self::$LODESTONE_DEV_BLOG = self::$LODESTONE_URL .'/pr/blog/atom.xml';
    }
}
