<?php

namespace Lodestone\Parser;

/**
 * Bunch of custom helpers
 *
 * These will require small maintenance whenever
 * lodestone is updated as they trim html
 * based on fixed positions. This is done for
 * very explicit performance gains.
 *
 * Class ParserHelperSpecial
 * @package src\Parser
 */
trait ParserHelperSpecial
{
    /**
     * @return mixed
     */
    protected function getSpecial__AttributesPart1()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'icon-c--title icon-c__attributes');
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'character__profile__detail');
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml(html_entity_decode($html));

        unset($html);
        return $dom;
    }

    /**
     * @return mixed
     */
    protected function getSpecial__AttributesPart2()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__resists');
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'character__job clearfix');
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * @return mixed
     */
    protected function getSpecial__AttributesPart3()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__param__text__hp');
        $start = $start - 100;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'character__param__element');
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * @return mixed
     */
    protected function getSpecial__AttributesPart4()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__param__element');
        $start = $start - 30;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'icon-c__water');
        $finish = $finish + 30;
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * @return mixed
     */
    protected function getSpecial__ClassJobs()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__job');
        $start = $start - 50;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'Fisher');
        $finish = $finish + 200;
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * @return bool
     */
    protected function getSpecial__Collectables()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__mounts');
        $start = $start - 30;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'ldst__side');
        $finish = $finish - 30;
        $html = substr($html, 0, $finish);

        if (!$html) {
            return false;
        }

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * @return mixed
     */
    protected function getSpecial__EquipGear()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__profile__detail');
        $start = $start - 30;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'heading__icon parts__space--reset');
        $finish = $finish - 30;
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * @return mixed
     */
    protected function getSpecial__Achievements()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'ldst__achievement');
        $start = $start + 372;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, '/ul');
        $finish = $finish + 30;
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }
}