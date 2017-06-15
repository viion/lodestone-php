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
// view Lodestone/Modules/Routes for more urls.

$option = isset($argv[1]) ? trim($argv[1]) : false;
$id = isset($argv[2]) ? trim($argv[2]) : false;
if (!$option) {
    die('Please provide an option: character, fc, ls');
}

// create api instance
$api = new \Lodestone\Api;

// switch on options
$hash = false;
switch($option) {
    case 'character':
        $data = $api->getCharacter($id ? $id : 730968);
        break;

    case 'character_friends':
        $data = $api->getCharacterFriends($id ? $id : 730968);
        break;

    case 'fc':
        $data = $api->getFreeCompany($id ? $id : '9231253336202687179');
        break;

    case 'ls':
        $data = $api->getLinkshellMembers($id ? $id : '19984723346535274');
        break;

    case 'devposts':
        $data = $api->getDevPosts();
        break;
}

if (!$data) {
    print_r("\nNo data, was the command correct? > ". $option);
    print_r("\n");
    die;
}

// Array of character data
print_r($data);
print_r(sprintf("Duration: %s - End\n\n", \Lodestone\Modules\Logger::$duration));;
print_r("\n");
