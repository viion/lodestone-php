<?php

namespace Lodestone\Parser\Html;

use Lodestone\{
    Dom\Document,
    Validator\BaseValidator,
    Entities\Character\CharacterSimple
};

/**
 * Class ParserHelper
 *
 * @package Lodestone\Parser
 */
class ParserHelper
{
    use ParserSpecial;
    use ParserDocument;
    use ParserHtml;

    public $results;
    
    /** @var Document */
    public $dom;

    /** @var string */
    public $html;

    /** @var array (depreciated) */
    public $data = [];

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
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
     * Default setup that most parsers will use.
     */
    protected function initialize()
    {
        // setup html
        $html = $this->trim($this->html, 'class="ldst__main"', 'class="ldst__side"');
        $feasthtml = $this->trim($this->html, 'class="wolvesden__ranking"', 'class="wolvesden__reward_season_wrapper"');
        $ddhtml = $this->trim($this->html, 'class="deepdungeon__ranking__wrapper__inner"', 'class="deepdungeon__ranking__guide_link"');
        $this->html = ($feasthtml ? $feasthtml : ($ddhtml ? $ddhtml : $html));
        
        // ensure that there is something left after trimming
        $this->ensureHtml();
        $this->setDocument($this->html);
    }

    /**
     * Ensures the HTML exists and is not empty.
     *
     * @throws \Exception
     */
    protected function ensureHtml()
    {
        BaseValidator::getInstance()
            ->check($this->html, "HTML")
            ->isNotEmpty();
    }

    /**
     * Set html document
     *
     * @param $html
     */
    protected function setDocument($html)
    {
        $this->dom = $this->getDocumentFromHtml($html);
    }

    /**
     * Get the current html document
     *
     * @return mixed
     */
    protected function getDocument()
    {
        return $this->dom;
    }

    /**
     * Provides a timestamp based on the html
     * that Lodestone uses for time display.
     *
     * @param $html
     * @return false|null|string
     */
    protected function getTimestamp($html)
    {
        $timestamp = $html->plaintext;
        $timestamp = trim(explode('(', $timestamp)[2]);
        $timestamp = trim(explode(',', $timestamp)[0]);
        return $timestamp ? $timestamp : null;
    }

    // Get
    protected function getImageSource($html)
    {
        // split on img incase html is prior to the img tag
        $html = explode('<img', $html)[1];
        $html = explode('"', $html)[1];
        $html = explode('?', $html)[0];
        return $html;
    }

     /**
     * Trim a bunch of html between two points
     *
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
     * Parse page count
     */
    protected function pageCount()
    {
        $data = $this->getDocument();
        if (!$data->find('.btn__pager__current', 0)) {
            $altcount = count($data->find('.pvpteam__member>div.entry'));
            if ($altcount > 0) {
                $this->results->setTotal($altcount);
            }
            return;
        }
        
        // page count
        $data = $data->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $this
            ->results
            ->setPageCurrent(filter_var($current, FILTER_SANITIZE_NUMBER_INT))
            ->setPageTotal(filter_var($total, FILTER_SANITIZE_NUMBER_INT))
            ->setNextPrevious();

        // elements count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
        
        $this->results->setTotal($count);
    }
    
    /**
     * Parse character list
     */
    protected function parseCharacterList(string $listelement = 'div.entry')
    {
        if ($this->results->getTotal() == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');

        // loop through the list of characters
        foreach($rows->find($listelement) as $node) {
            // create simple character
            $character = new CharacterSimple();
            $character
                ->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setServer( trim($node->find('.entry__world')->plaintext) )
                ->setAvatar( explode('?', $node->find('.entry__chara__face img', 0)->src)[0] );
            if ($rank = $node->find('.entry__chara_info__linkshell')->plaintext) {
                $character
                    ->setRank($rank)
                    ->setRankicon($this->getImageSource($node->find('.entry__chara_info__linkshell>img')));
                $this->results->setServer( $character->getServer() );
            }
            if ($rank = @$node->find('.entry__freecompany__info')->find('span')[0]->plaintext) {
                if (!is_numeric($rank)) {
                    $character
                        ->setRank($rank)
                        ->setRankicon($this->getImageSource($node->find('.entry__freecompany__info')->find('img')[0]));
                }
            }
            if ($listelement == '.pvpteam__member>div.entry') {
                $feasts = $node->find('.entry__freecompany__info')->find('span');
                $feasts = end($feasts)->plaintext;
                $character->setFeasts($feasts);
            } else {
                unset($character->feasts);
            }
            
            // add character to list
            $this->results->addCharacter($character);
            
        }
        if ($listelement == '.pvpteam__member>div.entry') {
            unset($this->results->pageCurrent, $this->results->pagePrevious, $this->results->pageNext, $this->results->pageTotal);
        }
    }
}
