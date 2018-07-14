<?php

namespace Lodestone\Search;

use Lodestone\Entities\{
    PvPTeam\PvPTeamSimple
};
use Lodestone\Parser\Html\ParserHelper;

/**
 * Class Search
 *
 * @package Lodestone\Parser\PvPTeam
 */
class PvPTeam extends ParserHelper
{
    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(string $html)
    {
        $this->results = new SearchPvPTeam();
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
            // create simple PvPTeam
            $obj = new PvPTeamSimple();
            $obj->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setDataCenter( trim($node->find('.entry__world')->plaintext) );
            
            $this->results->addPvPTeam($obj);
        }
    }
}
