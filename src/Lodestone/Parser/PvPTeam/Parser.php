<?php

namespace Lodestone\Parser\PvPTeam;

use Lodestone\{
    Entities\Character\CharacterSimple,
    Entities\PvPTeam\PvPTeam,
    Entities\PvPTeam\PvPTeamMembers,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger,
    Parser\Html\ParserHelper
};

/**
 * Class PvPTeam
 * @package src\Parser
 */
class Parser extends ParserHelper
{
    /** @var PvPTeam() */
    protected $PvPTeam;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct($id)
    {
        $this->PvPTeam = new PvPTeam($id);
    }
    
    /**
     * @return PvPTeam
     */
    public function parse()
    {
        $this->initialize();

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->PvPTeam;
        }
    
        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);
        
        $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);
        
        // crest
        $crest = [];
        $imgs = $box->find('.entry__pvpteam__crest__image img');
        foreach($imgs as $img) {
            $crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
        }
        $this->PvPTeam->setCrest($crest);
        
        $this->PvPTeam->setName( trim($box->find('.entry__pvpteam__name--team')->plaintext) );
        $this->PvPTeam->setServer(trim($box->find('.entry__pvpteam__name--dc')->plaintext));

        // parse
        $this->parseList();

        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));
        
        return $this->PvPTeam;
    }

    /**
     * Parse members, lazy suppressing of rank since not all members have one...
     */
    private function parseList()
    { 
        $rows = $this->getDocumentFromClassname('.pvpteam__member');
    
        // loop through the list of characters
        foreach($rows->find('div.entry') as $node) {
            // create simple character
            $character = new PvPTeamMembers();
            $character
                ->setId( explode('/', $node->find('a', 0)->getAttribute('href'))[3] )
                ->setName( trim($node->find('.entry__name')->plaintext) )
                ->setServer( trim($node->find('.entry__world')->plaintext) )
                ->setAvatar( explode('?', $node->find('.entry__chara__face img', 0)->src)[0] );
            if ($rank = $node->find('.entry__freecompany__info')->find('span')[0]->plaintext) {
                if (!is_numeric($rank)) {
                    $character
                        ->setRank($rank)
                        ->setRankicon($this->getImageSource($node->find('.entry__freecompany__info')->find('img')[0]));
                }
            }
            $feasts = $node->find('.entry__freecompany__info')->find('span');
            $feasts = end($feasts)->plaintext;
            $character->setFeasts($feasts);
            $this->PvPTeam->addCharacter($character);
        }
    }
}
