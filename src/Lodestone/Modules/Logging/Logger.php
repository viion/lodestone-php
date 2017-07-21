<?php

namespace Lodestone\Modules\Logging;

/**
 * This is very simple, doesn't follow PSR-3
 * todo: follow http://www.php-fig.org/psr/psr-3/
 * Class Logger
 */
class Logger
{
    public static $startTime = false;
    public static $lastTime = 0;
    public static $duration = 0;
    public static $log = [];

    const MAX_TIME_INCREMENT = 0.002;
    const MAX_MEMORY_USAGE = 20;
    const LOG_FILE = __DIR__.'/log.txt';

    /**
     * @param $class
     * @param $line
     * @param $message
     */
    public static function write($class, $line, $message)
    {
        $ms = substr(microtime(true), -4);
        $line = sprintf("[%s-%s][%s][%s] %s\n", date("Y-m-d H:i:s"), $ms, $class, $line, $message);
        self::$log[] = $line;

        // only output if enabled
        if (defined('LOGGER_ENABLED') && LOGGER_ENABLED) {
            echo $line;
        }
    }

    /**
     * @param $msg
     */
    public static function printtime($function, $line)
    {
        if (!defined('LOGGER_ENABLE_PRINT_TIME') || !LOGGER_ENABLE_PRINT_TIME) {
            return;
        }

        if (!self::$startTime) {
            self::$startTime = microtime(true);
        }

        $finish = microtime(true);
        $difference = $finish - self::$lastTime;
        $difference = Logger::padTime($difference);
        self::$lastTime = $finish;

        // unlikely something took 1000 seconds...
        // so hacky :D
        if ($difference > 1000) {
            $difference = '0000000000';
        }

        // duration
        $duration = $finish - self::$startTime;
        $duration = Logger::padTime($duration);
        self::$duration = $duration;

        // memory
        $memory = memory_get_usage();
        $memoryString = Logger::padString($memory, 15);

        // spacing
        $line = Logger::padString($line, 5);
        $flag = $difference > self::MAX_TIME_INCREMENT ? '!' : ' ';
        $flag = $memory > (1024 * 1024 * self::MAX_MEMORY_USAGE) ? '!' : $flag; // over 5 mb?

        $string = "Duration: %s   + %s  %s    Mem: %s  Line %s in  %s\n";
        echo sprintf($string, $duration, $difference, $flag, $memoryString, $line, $function);
    }

    /**
     * Pads a string for a given length by a given fill string
     *
     * @param string $string
     * @param int $length
     * @param string $padString
     * @return string $paddedString
     */
    private static function padString($string, $length, $padString = ' ')
    {
        return str_pad($string, $length, $padString);
    }

    /**
     * @param int $time
     * @return string
     */
    private static function padTime($time)
    {
        return Logger::padString(round($time < 0.0001 ? 0 : $time, 6), 10, '0');
    }

    /**
     * Write to log file
     *
     * @param $text
     */
    public static function save($text)
    {
        file_put_contents(self::LOG_FILE, $text."\n", FILE_APPEND);
    }
}