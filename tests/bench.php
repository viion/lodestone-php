<?php
/**
 * ----------------------------------------------------
 * Lodestone Parser Benchmark Tool
 * ----------------------------------------------------
 */

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

define('LOGGER_ENABLED', true);
define('LOGGER_ENABLE_PRINT_TIME', true);

// settings
$max = isset($argv[1]) ? trim($argv[1]) : 10;
$file = __DIR__.'/bench.json';

// remove any existing bench file
@unlink($file);

// access api
$api = new \Lodestone\Api;

// run bench
for ($i = 1; $i <= $max; $i++) {
    // run
    $data = $api->getCharacter(730968);

    // save times
    $benchmarkReport = \Lodestone\Modules\Benchmark::report();
    file_put_contents($file, json_encode($benchmarkReport) . "\n", FILE_APPEND);
}

// analyze
$reports = file_get_contents($file);
$reports = explode("\n", $reports);
$reports = array_values(array_filter($reports));

// process results
$results = [
    'lines' => [],
    'highest' => [],
    'memory' => [],
];
foreach($reports as $report) {
    $report = json_decode($report, true);

    foreach($report as $class => $functions) {
        foreach($functions as $function => $details) {
            //add time
            $results['lines'][$class .'___'. $function][] = $details['process_time'];

            // add memory
            $results['memory'][$class .'___'. $function][] = $highest['system_memory'];

            // add highest line
            $highest = $details['highest_line'];
            $results['highest'][$class .'___'. $function.'___'. $highest['line']][] = $highest['time_difference'];
        }
    }
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Benchmark Results
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

print_r("\n\n-----------------\nBENCHMARK RESULTS\n-----------------\n\n");

// view average line costs
print_r("Average Line Costs\n");
$completeAvg = [];
foreach($results['lines'] as $method => $counts) {
    $entries = count($counts);
    $average = array_sum($counts) / count($counts);
    $average = round($average, 3);
    $completeAvg[] = $average;

    list($class, $function) = explode('___', $method);

    print_r(sprintf("%s entries for: %s -> %s | Average time: %s microseconds\n", $entries, $class, $function, $average));
}
print_r(sprintf('Complete: %s microseconds', round(array_sum($completeAvg) / count($results['lines']), 3)));

// view highest line costs
print_r("\n\nAverage Highest Line Costs\n");
$completeHighest = [];
foreach($results['highest'] as $method => $counts) {
    $entries = count($counts);
    $average = array_sum($counts) / count($counts);
    $average = round($average, 3);

    $completeHighest[] = $average;
    list($class, $function, $line) = explode('___', $method);

    print_r(sprintf("%s entries for: %s -> %s (Line: %s) | Average time: %s microseconds\n", $entries, $class, $function, $line, $average));
}
print_r(sprintf('Complete: %s microseconds', round(array_sum($completeHighest), 3)));

// view highest line costs
print_r("\n\nAverage Memory Line Costs\n");
$completeMemory = [];
foreach($results['memory'] as $method => $counts) {
    $entries = count($counts);
    $average = array_sum($counts) / count($counts);
    $completeMemory[] = $average;
    list($class, $function) = explode('___', $method);


    print_r(sprintf("%s entries for: %s -> %s | Average memory: %s mb\n", $entries, $class, $function, $average));
}
print_r(sprintf('Complete: %s mb', array_sum($completeMemory) / count($results['memory'])));