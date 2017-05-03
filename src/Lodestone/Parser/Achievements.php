<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Logger;

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

        // check if doesn't exist
        if ($this->is404($html)) {
            return false;
        }

        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');

        $this->setInitialDocument($html);

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
        Logger::printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getSpecial__Achievements();
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

        $rows = $box->find('li');
        Logger::printtime(__FUNCTION__.'#'.__LINE__);

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
            Logger::printtime(__FUNCTION__.'#'.__LINE__);
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
