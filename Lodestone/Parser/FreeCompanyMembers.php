<?php

namespace Lodestone\Parser;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Entities\FreeCompany\FreeCompanyMembers as FCMembers,
    Parser\Html\ParserHelper
};

/**
 * Class Parser
 *
 * @package src\Parser
 */
class FreeCompanyMembers extends ParserHelper
{
    /** @var FreeCompanyMembers */
    public $results;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct($id, string $html)
    {
        $this->results = new FCMembers();
        $this->html = $html;
        $this->initialize();

        // no members
        if (!$this->getDocument()->find('.parts__zero', 0)) {
            $this->pageCount();
            $this->parseCharacterList('li.entry');
        }
    }
}
