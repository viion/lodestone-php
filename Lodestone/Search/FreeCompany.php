<?php

namespace Lodestone\Search;

use Lodestone\Entities\{
    FreeCompany\FreeCompanySimple
};
use Lodestone\Parser\Html\ParserHelper;

/**
 * Class Search
 *
 * @package Lodestone\PSearchFreeCompany
 */
class FreeCompany extends ParserHelper
{
    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(string $html)
    {
        $this->results = new SearchFreeCompany();
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
            // get all emblum imgs
            $crest = [];
            foreach($node->find('.entry__freecompany__crest__image img') as $img) {
                $crest[] = explode('?', $img->src)[0];
            }
            
            // create simple free company
            $obj = new FreeCompanySimple();
            $obj->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setServer( trim($node->find('.entry__world', 1)->plaintext) )
                ->setAvatar( $crest );
            
            $this->results->addFreeCompany($obj);
        }
    }
}
