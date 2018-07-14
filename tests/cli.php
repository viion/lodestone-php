<?php
//error_reporting(-1);
/**
 * ----------------------------------------------------
 * CLI tool to quickly test/debug specific API methods.
 * ----------------------------------------------------
 */

// composer auto loader
require __DIR__.'/../../../autoload.php';

define('BENCHMARK_ENABLED', true);
define('LOGGER_ENABLED', true);
define('LOGGER_ENABLE_PRINT_TIME', true);

// parse characters
// view Lodestone/Modules/Http/Routes for more urls.

$option = isset($argv[1]) ? trim($argv[1]) : false;
$arg1   = isset($argv[2]) ? trim($argv[2]) : false;
if (!$option) {
    die('Please provide an option: character, fc, ls');
}

// create api instance
$api = new \Lodestone\Api();

// Character = 730968
// following = 15609878
// name = 'Premium Virtue'
// fc = 9231253336202687179
// ls = 19984723346535274

$data = $arg1 ? $api->{$option}($arg1) : $api->{$option}();

print_r($data ? $data : "\nNo data, was the command correct? > ". $option);
print_r("\n");
