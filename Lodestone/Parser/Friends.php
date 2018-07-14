<?php

namespace Lodestone\Parser;

use Lodestone\{
    Entities\Character\CharacterFriends,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser
 */
class Friends extends ParserHelper
{
    /**
     * Parser constructor.
     */
    function __construct(string $html)
    {
        //$type = 'Lodestone\Entities\Character\CharacterFriends';
        //$this->results = new $type();
        $this->results = new CharacterFriends();
        $this->html = $html;
        $this->initialize();

        // no friends :(
        if (!$this->getDocument()->find('.parts__zero', 0)) {
            // parse stuff
            $this->pageCount();
            $this->parseCharacterList();
        }
    }
}
