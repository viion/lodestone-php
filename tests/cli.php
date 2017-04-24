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

$api = new \Lodestone\Api;

// load parser from command arg
switch($option) {
    case 'character':
        $data = $api->getCharacter($id ? $id : 730968, $hash);
        break;

    case 'fc':
        $url = sprintf(\Lodestone\Modules\Routes::LODESTONE_FREECOMPANY_URL, $id ? $id : '9231253336202687179');
        $parser = new Lodestone\Parser\FreeCompany();
        break;

    case 'ls':
        $url = sprintf(\Lodestone\Modules\Routes::LODESTONE_LINKSHELL_MEMBERS_URL, $id ? $id : '19984723346535274');
        $parser = new Lodestone\Parser\Linkshell();
        break;
}

// Array of character data
print_r($data);
print_r("\n");