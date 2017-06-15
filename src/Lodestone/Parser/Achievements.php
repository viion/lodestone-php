<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Logger;
use Lodestone\Parser\Html\ParserHelper;

/**
 * Class Achievements
 * @package Lodestone\Parser
 */
class Achievements extends ParserHelper
{
    /**
     * @return array|bool
     */
    public function parse()
    {
        $this->ensureHtml();
        $html = $this->html;

        // check if private
        if ($this->isPrivate($html)) {
            return 'private';
        }

        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->ensureHtml();

        $this->setDocument($html);

        $started = microtime(true);
        $this->parseList();
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', round(microtime(true) - $started, 3)));

        return $this->data;
    }

    //
    // Parse main profile bits
    //
    private function parseList()
    {
        $box = $this->getSpecial__Achievements();

        $rows = $box->find('li');

        $list = [];
        $listPossible = [];
        $pointsPossible = 0;
        $pointsObtained = 0;

        foreach($rows as $node) {
            $id = explode('/', $node->find('.entry__achievement', 0)->getAttribute('href'))[6];
            $points = intval($node->find('.entry__achievement__number', 0)->plaintext);

            // timestamp
            $timestamp = $node->find('.entry__activity__time', 0)->plaintext;
            $timestamp = trim(explode('(', $timestamp)[2]);
            $timestamp = trim(explode(',', $timestamp)[0]);
            $timestamp = $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;

            if ($timestamp) {
                $pointsObtained += $points;
            }

            $pointsPossible += $points;
            $listPossible[] = $id;

            $list[$id] = [
                'id' => $id,
                'points' => $points,
                'timestamp' => $timestamp,
            ];

        }

        $this->add('list', $list);
        $this->add('list_possible', $listPossible);
        $this->add('points', [
            'possible' => $pointsPossible,
            'obtained' => $pointsObtained,
        ]);
    }

    //
    // Checks if achievements are private
    //
    private function isPrivate($html)
    {
        return (stripos($html, 'You do not have permission to view this page.') > -1);
    }
}
