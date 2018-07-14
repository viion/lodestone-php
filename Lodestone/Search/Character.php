<?php

namespace Lodestone\Search;

use Lodestone\Entities\{
    Character\CharacterSimple
};
use Lodestone\Parser\Html\ParserHelper;

/**
 * Class Search
 *
 * @package Lodestone\Parser\Character
 */
class Character extends ParserHelper
{
    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(string $html)
    {
        $this->results = new SearchCharacter();
        $this->html = $html;
        $this->initialize();

        if (!$this->getDocument()->find('.parts__zero', 0)) {
            $this->pageCount();
            $this->parseCharacterList();
        }
    }
}