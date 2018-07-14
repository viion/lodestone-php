<?php

namespace Lodestone\Parser;

use Lodestone\{
    Entities\Character\CharacterFollowing,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser\CharacterFollowing
 */
class Following extends ParserHelper
{
    /**
     * Parser constructor.
     */
    function __construct(string $html)
    {
        $this->results = new CharacterFollowing();
        $this->html = $html;
        $this->initialize();

        // no followings
        if (!$this->getDocument()->find('.parts__zero', 0)) {
            $this->pageCount();
            $this->parseCharacterList();
        }
    }
}
