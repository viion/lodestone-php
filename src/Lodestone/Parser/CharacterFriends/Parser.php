<?php

namespace Lodestone\Parser\CharacterFriends;

use Lodestone\{
    Entities\Character\CharacterFriends,
    Entities\Character\CharacterSimple,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser
 */
class Parser extends ParserHelper
{
    /** @var CharacterFriends */
    protected $friends;
    
    /**
     * Parser constructor.
     */
    function __construct()
    {
        $this->friends = new CharacterFriends();
    }
    
    /**
     * @return CharacterFriends
     */
    public function parse()
    {
        $this->initialize();

        // no friends :(
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->friends;
        }
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);

        // parse stuff
        $this->pageCount();
        $this->parseFriends();
    
        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
        
        return $this->friends;
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
            ->friends
            ->setPageCurrent(filter_var($current, FILTER_SANITIZE_NUMBER_INT))
            ->setPageTotal(filter_var($total, FILTER_SANITIZE_NUMBER_INT))
            ->setNextPrevious();

        // friend count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
        $this->friends->setTotal($count);
    }

    /**
     * Parse friends
     */
    private function parseFriends()
    {
        if ($this->friends->getTotal() == 0) {
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
            
            // add character to friends list
            $this->friends->addCharacter($character);
        }
    }
}
