<?php

namespace Lodestone\Modules;

/**
 * This is very simple, doesn't follow PSR-3
 * todo: follow http://www.php-fig.org/psr/psr-3/
 * Class Logger
 */
class Logger
{
    public static $startTime = false;
    public static $lastTime = 0;

    /**
     * @param $class
     * @param $line
     * @param $message
     */
    public static function write($class, $line, $message)
    {
        if (!defined('LOGGER_ENABLED')) {
            return;
        }

        $ms = substr(microtime(true), -4);
        echo sprintf("[%s-%s][%s][%s] %s\n", date("Y-m-d H:i:s"), $ms, $class, $line, $message);
    }

    /**
     * @param $msg
     */
    public static function printtime($msg)
    {
        if (!defined('LOGGER_ENABLE_PRINT_TIME')) {
            return;
        }

        if (!self::$startTime) {
            self::$startTime = microtime(true);
        }

        $finish = microtime(true);
        $difference = $finish - self::$lastTime;
        $difference = str_pad(round($difference < 0.0001 ? 0 : $difference, 6), 10, '0');
        self::$lastTime = $finish;
        $duration = $finish - self::$startTime;
        $duration = str_pad(round($duration < 0.0001 ? 0 : $duration, 6), 10, '0');
        echo sprintf("%s \t---\t Time Overall: %s \t---\t Diff from last: %s \n", $msg, $duration, $difference);

    }
}