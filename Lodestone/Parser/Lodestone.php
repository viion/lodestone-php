<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Routes;
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
