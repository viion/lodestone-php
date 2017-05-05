<?php

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

define('LOGGER_ENABLED', true);

// parse characters
// view Lodestone/Modules/Routes for more urls.

$option = isset($argv[1]) ? trim($argv[1]) : false;
$id = isset($argv[2]) ? trim($argv[2]) : false;
$hash = isset($argv[3]) ? trim($argv[3]) : false;
if (!$option) {
    die('Please provide an option: character, fc, ls');
}

// create api instance
$api = new \Lodestone\Api;

// switch on options
$hash = false;
switch($option) {
    case 'character':
        $data = $api->getCharacter($id ? $id : 730968, $hash);
        $hash = $api->getCharacterHash($data);
        break;

    case 'fc':
        $data = $api->getFreeCompany($id ? $id : '9231253336202687179');
        break;

    case 'ls':
        $data = $api->getLinkshellMembers($id ? $id : '19984723346535274');
        break;
}



// Array of character data
print_r($data);
print_r($hash);
print_r("\n");