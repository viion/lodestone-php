<?php

namespace Lodestone\Search;

use Lodestone\Entities\{
    Linkshell\LinkshellSimple
};
use Lodestone\Parser\Html\ParserHelper;

/**
 * Class Search
 *
 * @package Lodestone\Parser\Linkshell
 */
class Linkshell extends ParserHelper
{
    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(string $html)
    {
        $this->results = new SearchLinkshell();
        $this->html = $html;
        $this->initialize();
        
        if (!$this->getDocument()->find('.parts__zero', 0)) {
            $this->pageCount();
            $this->parseList();
        }
    }
    
    /**
     * Parse members
     */
    private function parseList()
    {
        if ($this->results->getTotal() == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');
        
        // loop through the list of characters
        foreach($rows->find('.entry') as $node) {
            // create simple linkshell
            $obj = new LinkshellSimple();
            $obj->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setServer( trim($node->find('.entry__world')->plaintext) );
            
            $this->results->addLinkshell($obj);
        }
    }
}