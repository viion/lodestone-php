<?php
//error_reporting(-1);
/**
 * ----------------------------------------------------
 * CLI tool to quickly test/debug specific API methods.
 * ----------------------------------------------------
 */

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

define('BENCHMARK_ENABLED', true);
define('LOGGER_ENABLED', true);
define('LOGGER_ENABLE_PRINT_TIME', true);

// parse characters
// view Lodestone/Modules/Http/Routes for more urls.

$option = isset($argv[1]) ? trim($argv[1]) : false;
$id = isset($argv[2]) ? trim($argv[2]) : false;
if (!$option) {
    die('Please provide an option: character, fc, ls');
}

// create api instance
$api = new \Lodestone\Api();

// switch on options
$hash = false;
switch($option) {
    case 'character':
        $data = $api->getCharacter($id ? $id : 730968);
        break;

    case 'character_friends':
        $data = $api->getCharacterFriends($id ? $id : 730968);
        break;
    
    case 'character_following':
        $data = $api->getCharacterFollowing($id ? $id : 15609878);
        break;
        
    case 'character_search':
        $data = $api->searchCharacter($id ? $id : 'Premium Virtue');
        break;
    
    case 'achievements':
        $data = $api->getCharacterAchievements($id ? $id : 730968);
        break;

    case 'news':
        $data = $api->getLodestoneNews();
        break;

    case 'fc':
        $data = $api->getFreeCompany($id ? $id : '9231253336202687179');
        break;
    
    case 'fc_members':
        $data = $api->getFreeCompanyMembers($id ? $id : '9231253336202687179');
        break;
    
    case 'fc_search':
        $data = $api->searchFreeCompany($id ? $id : 'Test');
        break;

    case 'ls':
        $data = $api->getLinkshellMembers($id ? $id : '19984723346535274');
        break;
    
    case 'ls_search':
        $data = $api->searchLinkshell($id ? $id : 'Test');
        break;

    case 'devposts':
        $data = $api->getDevPosts();
        break;

    case 'lodestone_topics':
        $data = $api->getLodestoneTopics();
        break;

    case 'issue_58':
        $id = '9229001536389032942';
        $freeCompany = $api->getFreeCompanyMembers($id, 1);
        foreach($freeCompany['members'] as $member) {
            print_r($member['id'] . "\n");
        }
        die;

    case 'issue_63':
        $fc_id = '9229001536389032942';
        $freeCompany = $api->getFreeCompanyMembers($fc_id, 1);
        foreach($freeCompany['members'] as $members) {
            $id = $members['id'];
            $character = $api->getCharacter($id);
            print_r("Hello: ". $character->getName() . "\n");
        }
        die;
}

if (!$data) {
    print_r("\nNo data, was the command correct? > ". $option);
    print_r("\n");
    die;
}

// Array of character data
print_r($data);
print_r(sprintf("Duration: %s - End\n\n", \Lodestone\Modules\Logging\Logger::$duration));;
print_r("\n");
