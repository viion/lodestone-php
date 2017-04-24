<?php

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

// enable logging
define('LOGGER_ENABLED', true);
$logger = new \Lodestone\Modules\Logger();
$logger->write('TESTS',__LINE__,'Running tests ...');

// api
$api = new \Lodestone\Api;

// =======================================================================
// Start tests
// =======================================================================

$tests = [
    'searchCharacter' => false,
    'searchFreeCompany' => false,
    'searchLinkshell' => false,
    'getCharacter' => false,
    'getCharacterFriends' => false,
    'getCharacterFollowing' => false,
    'getCharacterAchievements' => false,
    'getFreeCompany' => false,
    'getFreeCompanyMembers' => false,
    'getLinkshellMembers' => false,
    'getLodestoneBanners' => false,
    'getLodestoneNews' => false,
    'getLodestoneTopics' => false,
    'getLodestoneNotices' => false,
    'getLodestoneMaintenance' => false,
    'getLodestoneUpdates' => false,
    'getLodestoneStatus' => false,
    'getWorldStatus' => false,
    'getDevBlog' => false,
    'getDevPosts' => false,
    'getFeast' => false,
    'getDeepDungeon' => false,
];

$tests = (Object)$tests;

// clear xivdb
$api->xivdb->clearCache();
$api->xivdb->init();

// Test 1: searchCharacter
$data = $api->searchCharacter('Premium Virtue', 'Phoenix');
if ($data['results']) {
    $tests->searchCharacter = true;
}

// Test 2: searchFreeCompany
$data = $api->searchFreeCompany('Equilibrium', 'Phoenix');
if ($data['results']) {
    $tests->searchFreeCompany = true;
}

// Test 3: searchLinkshell
$data = $api->searchLinkshell('Monster Hunt', 'Moogle');
if ($data['results']) {
    $tests->searchLinkshell = true;
}

// Test 4: getCharacter
// Test 5: getCharacterFriends
// Test 6: getCharacterFollowing
// Test 7: getCharacterAchievements
// Test 8: getFreeCompany
// Test 9: getFreeCompanyMembers
// Test 10: getLinkshellMembers
// Test 11: getLodestoneBanners
// Test 12: getLodestoneNews
// Test 13: getLodestoneTopics
// Test 14: getLodestoneNotices
// Test 15: getLodestoneMaintenance
// Test 16: getLodestoneUpdates
// Test 17: getLodestoneStatus
// Test 18: getWorldStatus
// Test 19: getDevBlog
$data = $api->getDevPosts();
if ($data) {
    $tests->getDevPosts = true;
}

// Test 20: getDevPosts
// Test 21: getFeast
// Test 22: getDeepDungeon

// show results
foreach((array)$tests as $test => $status) {
    $logger->write('TESTS',__LINE__,'Test: '. $test .' '. ($status ? 'PASS' : 'FAIL'));
}