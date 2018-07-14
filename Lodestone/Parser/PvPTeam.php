<?php

namespace Lodestone\Parser;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Entities\PvPTeam\PvPTeam as PvPMembers,
    Entities\PvPTeam\PvPTeamMembers,
    Parser\Html\ParserHelper
};

/**
 * Class PvPTeam
 * @package src\Parser
 */
class PvPTeam extends ParserHelper
{
    /** @var PvPTeam() */
    public $results;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct($id, string $html)
    {
        $this->results = new PvPMembers($id);
        $this->html = $html;
        $this->initialize();

        // no members
        if (!$this->getDocument()->find('.parts__zero', 0)) {
            $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);
            
            // crest
            $crest = [];
            $imgs = $box->find('.entry__pvpteam__crest__image img');
            foreach($imgs as $img) {
                $crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
            }
            $this->results->setCrest($crest);
            
            $this->results->setName( trim($box->find('.entry__pvpteam__name--team')->plaintext) );
            $this->results->setDataCenter(trim($box->find('.entry__pvpteam__name--dc')->plaintext));
    
            // parse
            $this->pageCount();
            $this->parseCharacterList('.pvpteam__member>div.entry');
        }
    }
}
