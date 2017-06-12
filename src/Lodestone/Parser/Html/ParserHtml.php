<?php

namespace Lodestone\Parser\Html;

use Lodestone\Dom\Document;

/**
 * Class ParserHtml
 * Wrapper for HTML extraction.
 *
 * @package Lodestone\Parser\Html
 */
trait ParserHtml
{
    /**
     * Provides an array of html, each line is bit
     * of html code.
     *
     * @param $html
     * @return array|mixed
     */
    protected function getArrayFromHtml($html)
    {
        $html = str_ireplace(">", ">\n", $html);
        $html = explode("\n", $html);
        return $html;
    }

    /**
     * Gets a section of html from a start/finish point, this is considerably faster
     * than using $this->getDocumentFromClassname()
     *
     * Returns an array of html
     *
     * @param $start
     * @param $finish
     * @param $html
     * @return array
     */
    protected function getArrayFromRange($start, $finish, $html = null)
    {
        $started = false;
        $results = [];

        // handle html
        $html = $html ?? $this->htmlOriginal;
        $html = is_array($html) ? $html : explode("\n", $html);

        // loop through html to find stuff
        foreach($html as $i => $line) {
            // if text found, started is true
            if (stripos($line, trim($start)) > -1) {
                $started = true;
            }

            // if started, append html into results
            if ($started) {
                $results[] = trim($line);
            }

            // Break if:
            //  $finish is numeric and results = value
            //  $finish is string and line matches that string
            $break = is_numeric($finish)
                ? (count($results) > $finish)
                : (stripos($line, trim($finish)) > -1);

            if ($started && $break) {
                break;
            }
        }

        return array_values(array_filter($results));
    }

    /**
     * Returns an array struct at the specified dom
     * todo : (depreciated) This is not being used
     *
     * @param $classname
     * @return array
     */
    /*
    protected function getDomArray($classname)
    {
        $box = $this->getDocumentFromClassname($classname, 0);
        if (!$box) {
            die('Class name does not exist: '. $classname);
        }

        $html = html_entity_decode($box->outertext());
        $html = str_ireplace(['<', '>'], null, $html);
        $array = explode("\n", $html);

        // trim all values
        array_walk($array, function(&$val) {
            $val = trim($val);
        });

        return $array;
    }
    */

    /**
     * Find a line
     * todo : (depreciated) This is not being used
     *
     * @param $domArray
     * @param $find
     * @return bool
     */
    /*
    protected function findDomLine($domArray, $find)
    {
        foreach($domArray as $i => &$line) {
            if (stripos($line, $find) > -1) {
                return $line;
            }
        }

        return false;
    }
    */
}