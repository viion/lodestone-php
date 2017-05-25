<?php

namespace Lodestone\Modules;

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
                'memory' => self::memory(),
                'method' => $method,
                'starting_line' => $line,
                'starting_time' => self::timestamp(),
                'finish_line' => false,
                'finish_time' => false,
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

        // add duration
        $duration = self::$records[$id]['finish_time'] - self::$records[$id]['starting_time'];
        self::$records[$id]['duration'] = $duration;

        // add duration to history
        self::$recordsTimes[$id][] = $duration;

        // is this the lowest?
        if (!self::$records[$id]['duration_lowest'] || $duration < self::$records[$id]['duration_lowest']) {
            self::$records[$id]['duration_lowest'] = $duration;
        }

        // is this the highest?
        if (!self::$records[$id]['duration_highest'] || $duration > self::$records[$id]['duration_highest']) {
            self::$records[$id]['duration_highest'] = $duration;
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
        echo sprintf(" CPU Usage: \t %s\n", round(self::cpu(), 5));
        echo sprintf(" Records: \t %s\n\n", count(self::$records));

        // print results
        foreach(self::$records as $id => $record) {
            $record = (object)$record;

            // round some values
            $precision = 8;
            $record->duration = round($record->duration, $precision);
            $record->duration_lowest = round($record->duration_lowest, $precision);
            $record->duration_highest = round($record->duration_highest, $precision);
            $record->average = round($record->average, $precision);

            // flag?
            $flag = $record->average > 0.001 ? ' !! ' : '    ';

            $line = "%s%s    Line: %s to %s\n%s%s entries   average: %s ms   low: %s - high: %s\n\n";

            $line = sprintf(
                $line,
                $flag,
                $record->method,
                $record->starting_line,
                $record->finish_line,
                $flag,
                $record->entries,
                $record->average,
                $record->duration_lowest,
                $record->duration_highest
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