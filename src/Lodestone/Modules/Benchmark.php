<?php

namespace Lodestone\Modules;

/**
 * Class Benchmark
 *
 * @package Lodestone\Modules
 */
class Benchmark
{
    private static $timeStarted = 0;
    private static $timeFinished = 0;
    private static $timeLastRecord = 0;
    private static $timeDuration = 0;
    private static $records = [];

    /**
     * Start benchmark
     */
    public static function start()
    {
        self::$timeStarted = self::timestamp();
    }

    /**
     * Finish benchmark
     */
    public static function finish()
    {
        self::$timeFinished = self::timestamp();
    }

    /**
     * @param $function
     * @param $line
     */
    public static function record($class, $function, $line)
    {
        $now = self::timestamp();

        // if no last recorded time, set it to now
        if (!self::$timeLastRecord) {
            self::$timeLastRecord = self::$timeStarted;
        }

        // work out time diff
        $diff = $now - self::$timeLastRecord;
        $diff = substr($diff, 0, 4);

        self::$timeDuration += $diff;

        // record duration
        self::$records[$class][$function][$line] = [
            'class' => $class,
            'function' => $function,
            'line' => $line,
            'time_previous' => self::$timeLastRecord,
            'time_finished' => $now,
            'time_difference' => $diff,
            //'system_cpu' => self::cpu(),
            'system_memory' => self::memory(),
        ];

        // set last recorded time
        self::$timeLastRecord = $now;

        // send to logger if enabled
        if (defined('LOGGER_ENABLE_PRINT_TIME')) {
            Logger::printtime($class, $function, $line);
        }
    }

    /**
     * Gets a micro second timestamp
     * in integer value to avoid precision
     * errors. No line of code should take longer
     * than 9999 seconds ...
     *
     * @return bool|mixed|string
     */
    public static function timestamp()
    {
        list($microseconds, $seconds) = explode(' ', microtime());

        // nothing should be higher than 9999 seconds
        $seconds = substr($seconds, -4);

        // remove "0." from microseconds
        $microseconds = explode('.', $microseconds)[1];
        $microseconds = substr($microseconds, 0, strlen($microseconds) - 2);

        // combine
        $timestamp = intval($seconds . $microseconds);

        return $timestamp;
    }

    /**
     * Run a report
     * @return array
     */
    public static function report()
    {
        $report = [];

        // loop through recorded functions
        foreach(self::$records as $class => $functions) {
            // loop through recorded calls
            foreach ($functions as $function => $records) {
                // initial details
                $report[$class][$function] = [
                    'class' => $class,
                    'method' => $function,
                    'records_count' => count($records),
                    'records_microseconds' => [],
                    'system_memory' => null,
                    'process_time' => 0,
                    'highest_line' => false,
                ];

                // process times of each line
                foreach ($records as $line => $record) {
                    $report[$class][$function]['records_microseconds']['Line:' . $line] = $record['time_difference'];
                    $report[$class][$function]['records_memory']['Line:' . $line] = $record['system_memory'];

                    // record highest entry
                    if (!$report[$class][$function]['highest_line']
                        || $report[$class][$function]['highest_line']['time_difference'] < $record['time_difference']
                    ) {
                        $report[$class][$function]['highest_line'] = $record;
                    }

                    $report[$class][$function]['process_time'] += $record['time_difference'];
                    $report[$class][$function]['system_memory'] = $record['system_memory'];
                }
            }
        }

        return $report;
    }

    /**
     * Get memory
     * @return string
     */
    public static function memory()
    {
        $size = memory_get_usage(true);
        $unit = ['b','kb','mb','gb','tb','pb'];
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2) .' '. $unit[$i];
    }

    /**
     * Get CPU
     * @return mixed
     */
    public static function cpu()
    {
        return sys_getloadavg()[0];
    }
}