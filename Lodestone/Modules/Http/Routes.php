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
    const LODESTONE_CHARACTERS_URL = self::LODESTONE_URL_BASE . 'lodestone/character/%s/';
    const LODESTONE_CHARACTERS_FRIENDS_URL = self::LODESTONE_URL_BASE . 'lodestone/character/%s/friend';
    const LODESTONE_CHARACTERS_FOLLOWING_URL = self::LODESTONE_URL_BASE . 'lodestone/character/%s/following';
    const LODESTONE_CHARACTERS_SEARCH_URL = self::LODESTONE_URL_BASE .'lodestone/character';
    const LODESTONE_ACHIEVEMENTS_URL = self::LODESTONE_URL_BASE . 'lodestone/character/%s/achievement/kind/%s/';
    const LODESTONE_ACHIEVEMENTS_CAT_URL = self::LODESTONE_URL_BASE . 'lodestone/character/%s/achievement/category/%s/';
    const LODESTONE_ACHIEVEMENTS_DET_URL = self::LODESTONE_URL_BASE . 'lodestone/character/%s/achievement/detail/%s/';
    // free company
    const LODESTONE_FREECOMPANY_URL = self::LODESTONE_URL_BASE . 'lodestone/freecompany/%s/';
    const LODESTONE_FREECOMPANY_SEARCH_URL = self::LODESTONE_URL_BASE . 'lodestone/freecompany';
    const LODESTONE_FREECOMPANY_MEMBERS_URL = self::LODESTONE_URL_BASE .'lodestone/freecompany/%s/member/';
    // linkshell
    const LODESTONE_LINKSHELL_SEARCH_URL = self::LODESTONE_URL_BASE . 'lodestone/linkshell';
    const LODESTONE_LINKSHELL_MEMBERS_URL = self::LODESTONE_URL_BASE .'lodestone/linkshell/%s/';
    
    // linkshell
    const LODESTONE_PVPTEAM_SEARCH_URL = self::LODESTONE_URL_BASE . 'lodestone/pvpteam';
    const LODESTONE_PVPTEAM_MEMBERS_URL = self::LODESTONE_URL_BASE .'lodestone/pvpteam/%s/';
    // homepage
    const LODESTONE_BANNERS = self::LODESTONE_URL_BASE .'lodestone/';
    const LODESTONE_NEWS = self::LODESTONE_URL_BASE .'lodestone/news/';
    const LODESTONE_TOPICS = self::LODESTONE_URL_BASE .'lodestone/topics/';
    const LODESTONE_NOTICES = self::LODESTONE_URL_BASE .'lodestone/news/category/1';
    const LODESTONE_MAINTENANCE = self::LODESTONE_URL_BASE .'lodestone/news/category/2';
    const LODESTONE_UPDATES = self::LODESTONE_URL_BASE .'lodestone/news/category/3';
    const LODESTONE_STATUS = self::LODESTONE_URL_BASE .'lodestone/news/category/4';
    // feast
    const LODESTONE_FEAST = self::LODESTONE_URL_BASE .'lodestone/ranking/thefeast/result/%s/';
    // deep dungeon
    const LODESTONE_DEEP_DUNGEON = self::LODESTONE_URL_BASE .'lodestone/ranking/deepdungeon%s/';
    // world status
    const LODESTONE_WORLD_STATUS = self::LODESTONE_URL_BASE .'lodestone/worldstatus/';
    // devblog
    const LODESTONE_DEV_BLOG = self::LODESTONE_URL_BASE .'/pr/blog/atom.xml';
    // forums
    const LODESTONE_FORUMS = 'https://forum.square-enix.com/ffxiv/';
    const LODESTONE_FORUMS_HOMEPAGE = self::LODESTONE_FORUMS .'forum.php';
}
