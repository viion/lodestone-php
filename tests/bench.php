<?php
/**
 * ----------------------------------------------------
 * Lodestone Parser Benchmark Tool
 * ----------------------------------------------------
 */

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

define('BENCHMARK_ENABLED', true);
define('LOGGER_ENABLED', true);

// settings
$max = isset($argv[1]) ? trim($argv[1]) : 10;
$report = isset($argv[2]) ? true : false;
$file = __DIR__.'/bench.json';

// remove any existing bench file
@unlink($file);

// access api
$api = new \Lodestone\Api;

// run bench
for ($i = 1; $i <= $max; $i++) {
    // run
    $data = $api->getCharacter(730968);
}

print_r("\n -----------------\n BENCHMARK RESULTS\n -----------------\n\n");
\Lodestone\Modules\Logging\Benchmark::report($report);