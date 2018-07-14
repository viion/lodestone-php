<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Http\Routes;
use Lodestone\Parser\Html\ParserHelper;

/**
 * Parse character data
 * Class Search
 * @package src\Parser
 */
class Lodestone extends ParserHelper
{
    function __construct(string $html)
    {
        $this->html = $html;
        $this->initialize();
    }
    
    /**
     * @return array
     */
    public function parseBanners()
    {
        $entries = $this->getDocument()->find('#slider_bnr_area li');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'url' => $entry->find('a',0)->href,
                'banner' => explode('?', $entry->find('img', 0)->src)[0],
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseTopics()
    {
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list--topics');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $entry->find('.news__list--title')->plaintext,
                'url' => $entry->find('.news__list--title a', 0)->href,
                'banner' => $entry->find('.news__list--img img', 0)->getAttribute('src'),
                'html' => $entry->find('.news__list--banner p')->innerHtml(),
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseNotices(string $language)
    {
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];
        
        foreach($entries as $entry) {
            $tag = @$entry->find('.news__list--tag')->plaintext;
            $title = $entry->find('.news__list--title')->plaintext;
            $title = str_ireplace($tag, null, $title);
            
            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $title,
                'url' => $language.rtrim(Routes::LODESTONE_URL_BASE, '/') . $entry->find('.news__list--link', 0)->href,
                'tag' => $tag,
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseWorldStatus()
    {
        $entries = $this->getDocumentFromClassname('.parts__space--pb16')->find('div.item-list__worldstatus');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'title' => trim($entry->find('h3')->plaintext),
                'status' => trim($entry->find('p')->plaintext),
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseFeast()
    {        
        $entries = $this->getDocument()->find('.wolvesden__ranking__table tr');
        $results = [];
        
        foreach($entries as $node) {
            $results[] = [
                'id' => explode('/', $node->getAttribute('data-href'))[3],
                'name' => trim($node->find('.wolvesden__ranking__result__name h3', 0)->plaintext),
                'server' =>trim( $node->find('.wolvesden__ranking__result__world', 0)->plaintext),
                'avatar' => explode('?', $node->find('.wolvesden__ranking__result__face img', 0)->src)[0],
                'rank' => $node->find('.wolvesden__ranking__result__order', 0)->plaintext,
                'rank_previous' => trim($node->find('.wolvesden__ranking__td__prev_order', 0)->plaintext),
                'win_count' => trim(@$node->find('.wolvesden__ranking__result__win_count', 0)->plaintext),
                'win_rate' => str_ireplace('%', null, trim(@$node->find('.wolvesden__ranking__result__winning_rate', 0)->plaintext)),
                'matches' => trim(@$node->find('.wolvesden__ranking__result__match_count', 0)->plaintext),
                'rating' => trim($node->find('.wolvesden__ranking__result__match_rate', 0)->plaintext),
                'rank_image' => @trim($node->find('.wolvesden__ranking__td__rank img', 0)->src),
            ];
        }
        
        $this->add('results', $results);
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseDeepDungeon()
    {
        $entries = $this->getDocument()->find('.deepdungeon__ranking__wrapper__inner li');
        $results = [];
        foreach($entries as $node) {            
            $results[] = [
                'id' => explode('/', $node->getAttribute('data-href'))[3],
                'name' => trim($node->find('.deepdungeon__ranking__result__name h3', 0)->plaintext),
                'server' =>trim( $node->find('.deepdungeon__ranking__result__world', 0)->plaintext),
                'avatar' => explode('?', $node->find('.deepdungeon__ranking__face__inner img', 0)->src)[0],
                'job' => trim($node->find('.deepdungeon__ranking__icon img', 0)->getAttribute('title')),
                'rank' => $node->find('.deepdungeon__ranking__result__order', 0)->plaintext,
                'score' => trim($node->find('.deepdungeon__ranking__data--score', 0)->plaintext),
                'time' => $this->getTimestamp($node->find('.deepdungeon__ranking__data--time')),
                'floor' => filter_var($node->find('.deepdungeon__ranking__data--reaching', 0)->plaintext, FILTER_SANITIZE_NUMBER_INT),
            ];
        }
        return $results;
    }
}
