<?php

namespace Lodestone\Parser;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Entities\Linkshell\Linkshell as LSMembers,
    Parser\Html\ParserHelper
};

/**
 * Class Linkshell
 * @package src\Parser
 */
class Linkshell extends ParserHelper
{
    /** @var Linkshell() */
    public $results;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct($id, string $html)
    {
        $this->results = new LSMembers($id);
        $this->html = $html;
        $this->initialize();

        // no members
        if (!$this->getDocument()->find('.parts__zero', 0)) {
            $box = $this->getDocumentFromClassname('.ldst__window .heading__linkshell', 0);
            $this->results->setName( trim($box->find('.heading__linkshell__name')->plaintext) );
    
            // parse
            $this->pageCount();
            $this->parseCharacterList();
        }
    }
}
