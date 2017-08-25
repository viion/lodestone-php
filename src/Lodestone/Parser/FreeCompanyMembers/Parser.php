<?php

namespace Lodestone\Parser\FreeCompanyMembers;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Entities\FreeCompany\FreeCompanyMembers,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package src\Parser
 */
class Parser extends ParserHelper
{
    /** @var FreeCompanyMembers */
    protected $members;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct()
    {
        $this->members = new FreeCompanyMembers();
    }
    
    /**
     * @return FreeCompanyMembers
     */
    public function parse()
    {
        $this->initialize();

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->members;
        }
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        
        $this->pageCount();
        $this->parseList();
    
        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
        
        return $this->members;
    }

    /**
     * Parse page count
     */
    private function pageCount()
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);
    
        $this
            ->members
            ->setPageCurrent(filter_var($current, FILTER_SANITIZE_NUMBER_INT))
            ->setPageTotal(filter_var($total, FILTER_SANITIZE_NUMBER_INT))
            ->setNextPrevious();
    
        // member count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
        $this->members->setTotal($count);
    }

    /**
     * Parse members
     */
    private function parseList()
    {
        if ($this->members->getTotal() == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');
    
        // loop through the list of characters
        foreach($rows->find('li.entry') as $node) {
            // create simple character
            $character = new CharacterSimple();
            $character
                ->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setServer( trim($node->find('.entry__world')->plaintext) )
                ->setAvatar( explode('?', $node->find('.entry__chara__face img', 0)->src)[0] )
                ->setRank( trim($node->find('.entry__freecompany__info span', 0)->plaintext) )
                ->setRankicon( trim($node->find('.entry__freecompany__info img', 0)->src) );
        
            // add character to friends list
            $this->members->addCharacter($character);
        }
    }
}
