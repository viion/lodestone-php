<?php

namespace Lodestone\Modules\Logging;

/**
 * Class Benchmark
 *
 * @package Lodestone\Modules
 */
class Benchmark
{
    private static $started = false;
    private static $records = [];
    private static $recordsTimes = [];

    const PRECISION = 5;
    const MAX_TIME_INCREMENT = 0.002;

    /**
     * @param $function
     * @param $line
     */
    public static function start($method, $line)
    {
        // send to logger if enabled
        if (defined('LOGGER_ENABLE_PRINT_TIME')) {
            Logger::printtime($method, $line);
        }

        // don't do anything if not enabled
        if (!defined('BENCHMARK_ENABLED') || !BENCHMARK_ENABLED) {
            return;
        }

        // set global start time
        if (!self::$started) {
            self::$started = self::timestamp();
        }

        // create same ID for method.
        $id = sha1($method);

        // handle depending on if a record exists or not for this function
        if (!isset(self::$records[$id])) {
            // create record entry
            self::$records[$id] = [
                'method' => $method,
                'starting_line' => $line,
                'starting_time' => self::timestamp(),
                'starting_memory' => self::memory(),
                'finish_line' => false,
                'finish_time' => false,
                'finish_memory' => false,
                'duration' => false,
                'duration_lowest' => 0,
                'duration_highest' => 0,
                'average' => false,
                'entries' => 0,
            ];
        } else {
            self::$records[$id]['starting_time'] = self::timestamp();
        }


    }

    /**
     * @param $method
     * @param $line
     */
    public static function finish($method, $line)
    {
        // send to logger if enabled
        if (defined('LOGGER_ENABLE_PRINT_TIME')) {
            Logger::printtime($method, $line);
        }

        // don't do anything if not enabled
        if (!defined('BENCHMARK_ENABLED') || !BENCHMARK_ENABLED) {
            return;
        }

        // create same ID for method.
        $id = sha1($method);

        // set finish times
        self::$records[$id]['finish_line'] = $line;
        self::$records[$id]['finish_time'] = self::timestamp();

        $start = self::$records[$id]['starting_time'];
        $finish = self::$records[$id]['finish_time'];

        // memory
        $memory = self::memory();

        // add duration
        $duration = ($start == $finish) ? 0 : number_format(bcsub($finish, $start, 32), self::PRECISION);
        self::$records[$id]['duration'] = $duration;

        // add duration to history
        self::$recordsTimes[$id][] = $duration;

        // is this the lowest time?
        if (!self::$records[$id]['duration_lowest'] || $duration < self::$records[$id]['duration_lowest']) {
            self::$records[$id]['duration_lowest'] = $duration;
        }

        // is this the highest time?
        if (!self::$records[$id]['duration_highest'] || $duration > self::$records[$id]['duration_highest']) {
            self::$records[$id]['duration_highest'] = $duration;
        }

        // is this the lowest memory?
        if (!self::$records[$id]['starting_memory'] || $memory < self::$records[$id]['starting_memory']) {
            self::$records[$id]['starting_memory'] = $memory;
        }

        // is this the highest memory?
        if (!self::$records[$id]['finish_memory'] || $memory > self::$records[$id]['finish_memory']) {
            self::$records[$id]['finish_memory'] = $memory;
        }

        // work out average
        self::$records[$id]['average']
            = array_sum(self::$recordsTimes[$id]) / count(self::$recordsTimes[$id]);

        // set entries count
        self::$records[$id]['entries'] = count(self::$recordsTimes[$id]);
    }

    /**
     * @return mixed
     */
    public static function timestamp()
    {
        return microtime(true);
    }

    /**
     * @return float
     */
    public static function milliseconds()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * Run a report
     * @return array
     */
    public static function report($dump = false)
    {
        if ($dump) {
            print_r(self::$records);
            die;
        }

        $duration = round(self::timestamp() - self::$started, 5);

        // headers
        echo sprintf(" Completed in: \t %s ms\n", $duration);
        echo sprintf(" Memory Usage: \t %s\n", self::memory());
        echo sprintf(" Records: \t %s\n\n", count(self::$records));

        // print results
        foreach(self::$records as $id => $record) {
            $record = (object)$record;

            // round some values
            $record->duration = number_format($record->duration, self::PRECISION);
            $record->duration_lowest = number_format($record->duration_lowest, self::PRECISION);
            $record->duration_highest = number_format($record->duration_highest, self::PRECISION);
            $record->average = number_format($record->average, self::PRECISION);

            // flag?
            $flag = $record->average > self::MAX_TIME_INCREMENT ? ' !! ' : '    ';

            $line = "%s[%s] %s   line: %s to %s\n%saverage: %s ms     low: %s - high: %s     Mem: %s - %s\n\n";

            $line = sprintf(
                $line,
                $flag,
                $record->entries,
                $record->method,
                $record->starting_line,
                $record->finish_line,
                $flag,
                $record->average,
                $record->duration_lowest,
                $record->duration_highest,
                $record->starting_memory,
                $record->finish_memory
            );

            echo $line;
        }
    }

    /**
     * Get memory
     * @return string
     */
    public static function memory()
    {
        $size = memory_get_usage();
        return number_format(($size / 1024)) .' kb';
    }
}