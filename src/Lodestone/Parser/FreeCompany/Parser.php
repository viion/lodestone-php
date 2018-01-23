<?php

namespace Lodestone\Parser\FreeCompany;

use Lodestone\{
    Entities\FreeCompany\FreeCompany,
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
    /** @var FreeCompany */
    protected $freecompany;
    
    /**
     * Parser constructor.
     *
     * @param string $id
     */
    function __construct($id)
    {
        $this->freecompany = new FreeCompany($id);
    }
    
    /**
     * @return FreeCompany
     */
    public function parse()
    {
        $this->initialize();

        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);

        // parse stuff
        $this->parseHeader();
        $this->parseProfile();
        $this->parseFocus();
    
        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));

        return $this->freecompany;
    }

    /**
     * Parse header bits
     */
    private function parseHeader()
    {
        $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);

        // crest
        $crest = [];
        $imgs = $box->find('.entry__freecompany__crest__image img');
        foreach($imgs as $img) {
            $crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
        }
        $this->freecompany->setCrest($crest);

        // grand company
        $data = $box->find('.entry__freecompany__gc')->plaintext;
        $data = explode('<', trim($data));
        $data = trim($data[0]);
        $this->freecompany->setGrandcompany($data);

        // name
        $data = trim($box->find('.entry__freecompany__name')->plaintext);
        $this->freecompany->setName($data);

        // server
        $data = trim($box->find('.entry__freecompany__gc', 1)->plaintext);
        $this->freecompany->setServer($data);
    }

    /**
     * Parse profile bits
     */
    private function parseProfile()
    {
        $box = $this->getDocumentFromClassname('.ldst__window', 0);

        // tag
        $data = $box->find('.freecompany__text__tag', 1)->plaintext;
        $data = trim(str_ireplace(['«', '»'], null, $data));
        $this->freecompany->setTag($data);

        // formed
        $timestamp = $this->getTimestamp($box->find('.freecompany__text', 2));
        $this->freecompany->setFormed($timestamp);

        // active members
        $data = $box->find('.freecompany__text', 3)->plaintext;
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        $this->freecompany->setActiveMemberCount($data);
        $this->add('members', $data);

        // rank
        $data = $box->find('.freecompany__text', 4)->plaintext;
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        $this->freecompany->setRank($data);

        // ranking
        $weekly = $box->find('.character__ranking__data th', 0)->plaintext;
        $weekly = filter_var($weekly, FILTER_SANITIZE_NUMBER_INT);
        $monthly = $box->find('.character__ranking__data th', 1)->plaintext;
        $monthly = filter_var($monthly, FILTER_SANITIZE_NUMBER_INT);
        
        $this->freecompany->setRanking((Object)[
            'weekly' => $weekly,
            'monthly' => $monthly,
        ]);

        // slogan
        $data = $box->find('.freecompany__text__message', 0)->innertext;
        $data = trim(str_ireplace("<br/>", "\n", $data));
        $this->freecompany->setSlogan($data);

        // estate
        $this->freecompany->setEstate((Object)[
            'name' => $box->find('.freecompany__estate__name')->plaintext,
            'plot' => $box->find('.freecompany__estate__text')->plaintext,
            'greeting' => $box->find('.freecompany__estate__greeting')->plaintext,
        ]);

        // reputation
        foreach($box->find('.freecompany__reputation') as $rep) {
            $progress = $rep->find('.character__bar div', 0)->getAttribute('style');
            $this->freecompany->addReputation((Object)[
                'name' => $rep->find('.freecompany__reputation__gcname')->plaintext,
                'rank' => $rep->find('.freecompany__reputation__rank')->plaintext,
                'progress' => filter_var($progress, FILTER_SANITIZE_NUMBER_INT),
            ]);
        }
    }

    /**
     * Parse FC
     */
    private function parseFocus()
    {
        $box = $this->getDocumentFromClassname('.ldst__window', 1);

        // active
        $data = trim($box->find('.freecompany__text', 0)->plaintext);
        $this->freecompany->setActive($data);

        // recruitment
        $data = trim($box->find('.freecompany__text', 1)->plaintext);
        $this->freecompany->setRecruitment($data);
        $this->add('recruitment', $data);

        // ---------------------------------------------------------------

        // seeking
        if ($seekNodes = $box->find('.freecompany__focus_icon--role', 0)) {
            foreach ($seekNodes->find('li') as $node) {
                $status = true;
                if ($node->getAttribute('class') == 'freecompany__focus_icon--off') {
                    $status = false;
                }

                $this->freecompany->addSeeking((Object)[
                    'status' => $status,
                    'icon' => $node->find('img', 0)->src,
                    'name' => $node->find('p', 0)->plaintext,
                ]);
            }
        }
                
        // ---------------------------------------------------------------

        // focus
        if ($focusNodes = $box->find('.freecompany__focus_icon:not(.freecompany__focus_icon--role)', 0)) {
            foreach ($focusNodes->find('li') as $node) {
                $status = true;
                if ($node->getAttribute('class') == 'freecompany__focus_icon--off') {
                    $status = false;
                }

                $this->freecompany->addFocus((Object)[
                    'status' => $status,
                    'icon' => $node->find('img', 0)->src,
                    'name' => $node->find('p', 0)->plaintext,
                ]);
            }
        }
    }
}
