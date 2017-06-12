<?php

namespace Lodestone\Parser\Html;

use Lodestone\Dom\Document;

/**
 * Class ParserHtml
 * Wrapper for Html Document extraction.
 *
 * @package Lodestone\Parser\Html
 */
trait ParserDocument
{
    /**
     * Get a new document
     *
     * @param $html
     * @return Document
     */
    protected function getDocumentFromHtml($html)
    {
        $html = new Document($html);
        return $html;
    }

    /**
     * Get document from class name
     * NOTE: This can be slow depending on the HTML size.
     *
     * @param $classname
     * @param int $i - offset, if multiple css classes
     * @return bool|Document
     */
    protected function getDocumentFromClassname($classname, $i = 0)
    {
        $html = $this->dom->find($classname, $i);
        if (!$html) {
            return false;
        }

        $html = $html->outesrtext;
        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Gets a section of html from a start/finish point, this is considerably faster
     * than using $this->getDocumentFromClassname()
     *
     * Returns a DOM Document object
     *
     * @param $start
     * @param $finish
     * @param $html
     * @return Document
     */
    protected function getDocumentFromRange($start, $finish, $html = null)
    {
        $html = $this->getArrayFromRange($start, $finish, $html);
        $html = implode("\n", $html);

        // provide dom
        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Get a section of html for a specific range
     *
     * @param $start
     * @param $finish
     * @return Document
     */
    protected function getDocumentFromRangeCustom($start, $finish, $debug = false)
    {
        $html = explode("\n", $this->htmlOriginal);
        if ($debug) {
            $html = explode("\n", $this->htmlOriginal);
            print_r($html);
            die;
        }

        $html = array_splice($html, $start, ($finish - $start));
        $html = implode("\n", $html);
        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }
}