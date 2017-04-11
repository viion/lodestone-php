<?php

namespace Sync\Parser;

use FastSimpleHTMLDom\Document;

/**
 * Class ParserHelper
 * @package Sync\Parser
 */
class ParserHelper
{
    use ParserHelperSpecial;
	public $dom;
	public $data = [];

    /**
     * Get a new document
     * @param $html
     * @return Document
     */
	protected function getDocumentFromHtml($html)
	{
		$dom = new Document($html);
		unset($html);

		return $dom;
	}

    /**
     * Set initial document
     * @param $html
     */
	protected function setInitialDocument($html)
	{
		$this->dom = $this->getDocumentFromHtml($html);
	}

    /**
     * Get the current document
     * @return mixed
     */
	protected function getDocument()
    {
        return $this->dom;
    }

    /**
     * Get document from class name
     * @param $classname
     * @param int $i
     * @return bool|Document
     */
	protected function getDocumentFromClassname($classname, $i = 0)
	{
	    $html = $this->dom->find($classname, $i);
	    if (!$html) {
	        return false;
        }

		$html = $html->outertext;
		$dom = $this->getDocumentFromHtml($html);
		unset($html);
		return $dom;
	}

    /**
     * Gets a section of html from a start/finish point, this is considerably faster
     * than using $this->getDocumentFromClassname()
     * @param $start
     * @param $finish
     * @return Document
     */
	protected function getDocumentFromRange($start, $finish)
    {
        $started = false;
        $html = [];
        foreach(explode("\n", $this->dom->innerHtml()) as $i => $line) {
            if (stripos($line, $start) > -1) {
                $started = true;
            }

            if (stripos($line, $finish) > -1) {
                break;
            }

            if ($started) {
                $html[] = $line;
            }
        }

        $html = implode("\n", $html);
        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Get a section of html for a specific range
     * @param $start
     * @param $finish
     * @return Document
     */
    protected function getDocumentFromRangeCustom($start, $finish, $debug = false)
    {
        $html = explode("\n", $this->dom->innerHtml());
        if ($debug) {
            $html = explode("\n", htmlentities($this->dom->innerHtml()));
            show($html);
            die;
        }

        $html = array_splice($html, $start, ($finish - $start));
        $html = implode("\n", $html);
        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Returns an array struct at the specified dom
     *
     * @param $classname
     * @return array
     */
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

    /**
     * Find a line
     * @param $domArray
     * @param $find
     * @return bool
     */
    protected function findDomLine($domArray, $find)
    {
        foreach($domArray as $i => $line) {
            if (stripos($line, $find) > -1) {
                return $line;
            }
        }

        return false;
    }

    /**
     * Add some data to the array
     * @param $name
     * @param $value
     */
	protected function add($name, $value)
	{
		$this->data[$name] = $value;
	}

    /**
     * Get data from the array
     * @param $name
     * @return mixed
     */
	protected function get($name)
	{
		return $this->data[$name];
	}

    /**
     * Trim a bunch of html
     * @param $html
     * @param $startHtml
     * @param $finishHtml
     * @return array|string
     */
	protected function trim($html, $startHtml, $finishHtml)
	{
		// trim the dom
		$html = explode("\n", $html);
		$startIndex = 0;
		$finishIndex = 0;

		// truncate down to just the character
		foreach($html as $i => $line) {
			// start of code
			if (stripos($line, $startHtml) !== false) {
				$startIndex = $i;
				continue;
			}

			if (stripos($line, $finishHtml) !== false) {
				$finishIndex = ($i - $startIndex);
				break;
			}
		}

		$html = array_slice($html, $startIndex, $finishIndex);

		// remove blank lines
		foreach($html as $i => $line) {
			if (!trim($line)) {
				unset($html[$i]);
			}
		}

		$html = implode("\n", $html);

		return $html;
	}

    /**
     * States if a lodestone page is 404 not found.
     * @return bool
     */
	protected function is404($html)
    {
        return (stripos($html, 'The page you are searching for has either been removed, or the designated URL address is incorrect.') > -1);
    }

    /**
     * @param $html
     * @return false|null|string
     */
    protected function getTimestamp($html)
    {
        $timestamp = $html->plaintext;
        $timestamp = trim(explode('(', $timestamp)[2]);
        $timestamp = trim(explode(',', $timestamp)[0]);
        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }
}
