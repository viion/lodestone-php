<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Logger;

/**
 * Class Linkshell
 * @package src\Parser
 */
class Linkshell extends ParserHelper
{
    /**
     * @return array|bool
     */
    public function parse()
    {
        if ($this->html == 404 || $this->html == false) {
        	return $this->html;
        }
        $html = $this->html;

        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');

        $this->setInitialDocument($html);

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return false;
        }
        
        $box = $this->getDocumentFromClassname('.ldst__window .heading__linkshell', 0);
        $data = trim($box->find('.heading__linkshell__name')->plaintext);
        $this->add('name', $data);
        $started = microtime(true);
        $this->pageCount();
        $this->parseList();
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', round(microtime(true) - $started, 3)));

        return $this->data;
    }

    /**
     * Parse page count
     */
    private function pageCount()
    {
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $current = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $total = filter_var($total, FILTER_SANITIZE_NUMBER_INT);

        $this->add('page_total', $total);
        $this->add('page_current', $current);

        // member count
        $data = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        $this->add('character_total', $data);
    }

    /**
     * Parse members, lazy suppressing of rank since not all members have one...
     */
    private function parseList()
    {
        $rows = $this->getDocumentFromClassname('.ldst__window');

        $characters = [];
        foreach($rows->find('div.entry') as $node) {
            $characters[] = [
                'id' => explode('/', $node->find('a', 0)->getAttribute('href'))[3],
                'name' => trim($node->find('.entry__name')->plaintext),
                'server' => trim($node->find('.entry__world')->plaintext),
                'rank' => @trim($node->find('.entry__chara_info__linkshell span', 0)->plaintext),
                'avatar' => explode('?', $node->find('.entry__chara__face img', 0)->src)[0],
            ];
        }

        $this->add('members', $characters);
    }
}
