<?php

namespace Lodestone\Parser\CharacterFollowing;

use Lodestone\{
    Entities\Character\CharacterFollowing,
    Entities\Character\CharacterSimple,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
    Parser\Html\ParserHelper
};


/**
 * Class Parser
 *
 * @package Lodestone\Parser\CharacterFollowing
 */
class Parser extends ParserHelper
{
    /** @var CharacterFollowing */
    protected $following;
    
    /**
     * Parser constructor.
     */
    function __construct()
    {
        $this->following = new CharacterFollowing();
    }
    
    /**
     * @return CharacterFollowing
     */
    public function parse()
    {
        $this->initialize();

        // no followings
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->following;
        }
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        
        $this->pageCount();
        $this->parseFollowing();
    
        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
    
        return $this->following;
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
            ->following
            ->setPageCurrent(filter_var($current, FILTER_SANITIZE_NUMBER_INT))
            ->setPageTotal(filter_var($total, FILTER_SANITIZE_NUMBER_INT))
            ->setNextPrevious();

        // member count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
        $this->following->setTotal($count);
    }

    /**
     * Parse members
     */
    private function parseFollowing()
    {
        if ($this->following->getTotal() == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');

        // loo through the list of characters
        foreach($rows->find('div.entry') as $node) {
            // create simple character
            $character = new CharacterSimple();
            $character
                ->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setServer( trim($node->find('.entry__world')->plaintext) )
                ->setAvatar( explode('?', $node->find('.entry__chara__face img', 0)->src)[0] );
    
            // add character to friends list
            $this->following->addCharacter($character);
        }
    }
}
