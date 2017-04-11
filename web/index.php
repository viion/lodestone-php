<?php

// list of urls
define('LODESTONE_URL', 'http://na.finalfantasyxiv.com/');
define('LODESTONE_CHARACTERS_URL', LODESTONE_URL . 'lodestone/character/%s/');
define('LODESTONE_CHARACTERS_FRIENDS_URL', LODESTONE_URL . 'lodestone/character/%s/friend');
define('LODESTONE_CHARACTERS_FOLLOWING_URL', LODESTONE_URL . 'lodestone/character/%s/following');
define('LODESTONE_CHARACTERS_SEARCH_URL', LODESTONE_URL .'lodestone/character');
define('LODESTONE_ACHIEVEMENTS_URL', LODESTONE_URL . 'lodestone/character/%s/achievement/kind/%s/');
define('LODESTONE_FREECOMPANY_URL', LODESTONE_URL . 'lodestone/freecompany/%s/');
define('LODESTONE_FREECOMPANY_SEARCH_URL', LODESTONE_URL . 'lodestone/freecompany');
define('LODESTONE_FREECOMPANY_MEMBERS_URL', LODESTONE_URL .'lodestone/freecompany/%s/member/');
define('LODESTONE_LINKSHELL_SEARCH_URL', LODESTONE_URL . 'lodestone/linkshell');
define('LODESTONE_LINKSHELL_MEMBERS_URL', LODESTONE_URL .'lodestone/linkshell/%s/');

define('LODESTONE_NEWS', LODESTONE_URL .'lodestone/news/');
define('LODESTONE_TOPICS', LODESTONE_URL .'lodestone/topics/');
define('LODESTONE_NOTICES', LODESTONE_URL .'lodestone/news/category/1');
define('LODESTONE_MAINTENANCE', LODESTONE_URL .'lodestone/news/category/2');
define('LODESTONE_UPDATES', LODESTONE_URL .'lodestone/news/category/3');
define('LODESTONE_STATUS', LODESTONE_URL .'lodestone/news/category/4');
define('LODESTONE_FEAST_SEASON_1', LODESTONE_URL .'lodestone/ranking/thefeast/result/1/');
define('LODESTONE_FEAST_SEASON_2', LODESTONE_URL .'lodestone/ranking/thefeast/result/2/');
define('LODESTONE_FEAST_SEASON_3', LODESTONE_URL .'lodestone/ranking/thefeast/result/3/');
define('LODESTONE_FEAST_SEASON_4', LODESTONE_URL .'lodestone/ranking/thefeast/'); // CURRENT
define('LODESTONE_DEEP_DUNGEON', LODESTONE_URL .'lodestone/ranking/deepdungeon/');
define('LODESTONE_WORLD_STATUS', LODESTONE_URL .'lodestone/worldstatus/');
define('LODESTONE_DEV_BLOG', LODESTONE_URL .'/pr/blog/atom.xml');
define('LODESTONE_FORUMS', 'http://forum.square-enix.com/ffxiv/');
define('LODESTONE_FORUMS_HOMEPAGE', LODESTONE_FORUMS .'forum.php');

// get html
$http = new Sync\Modules\HttpRequest();
$html = $http->get(LODESTONE_CHARACTERS_URL);
if (!$html) {
    die('Could not get html!');
}

// parse html
$parser = new Sync\Parser\Character();
$data = $parser->parse($html);
if (!$data) {
    die('Could not parse html');
}

print_r($data);