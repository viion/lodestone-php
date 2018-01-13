<?php

namespace Lodestone\Parser\Linkshell;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Entities\Linkshell\Linkshell,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
    Parser\Html\ParserHelper
};

/**
 * Class Linkshell
 * @package src\Parser
 */
class Parser extends ParserHelper
{
    /** @var Linkshell() */
    protected $linkshell;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct($id)
    {
        $this->linkshell = new Linkshell($id);
    }
    
    /**
     * @return Linkshell
     */
    public function parse()
    {
        $this->initialize();

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->linkshell;
        }
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        
        $box = $this->getDocumentFromClassname('.ldst__window .heading__linkshell', 0);
        $this->linkshell->setName( trim($box->find('.heading__linkshell__name')->plaintext) );

        // parse
        $this->pageCount();
        $this->parseList();

        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
        
        return $this->linkshell;
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
            ->linkshell
            ->setPageCurrent(filter_var($current, FILTER_SANITIZE_NUMBER_INT))
            ->setPageTotal(filter_var($total, FILTER_SANITIZE_NUMBER_INT))
            ->setNextPrevious();
    
        // member count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
        $this->linkshell->setTotal($count);
    }

    /**
     * Parse members, lazy suppressing of rank since not all members have one...
     */
    private function parseList()
    {
        if ($this->linkshell->getTotal() == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');
    
        // loop through the list of characters
        foreach($rows->find('div.entry') as $node) {
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
            }
            // add character to friends list
            $this->linkshell->addCharacter($character);
            
            // set linkshell server from character
            $this->linkshell->setServer( $character->getServer() );
        }
    }
}
