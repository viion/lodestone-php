<?php
//
// Parse a lodestone achievements
//

namespace Sync\Parser;

class Achievements extends ParserHelper
{
    /**
     * Parse lodestone!
     *
     * @param $html
     * @return array|bool
     */
	public function parse($html)
	{
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');

        // check if private
        if ($this->isPrivate($html)) {
            return 'private';
        }

        // check if doesnt exist
		if ($this->is404($html)) {
		    return false;
        }

        printtime(__FUNCTION__.'#'.__LINE__);
        $this->setInitialDocument($html);

        $started = microtime(true);
		$this->parseList();
        output('PARSE DURATION: %s ms', [ round(microtime(true) - $started, 3) ]);

        //show($this->data);die;

		return $this->data;
	}

	//
	// Parse main profile bits
	//
	private function parseList()
	{
        printtime(__FUNCTION__.'#'.__LINE__);
        $box = $this->getSpecial__Achievements();
        printtime(__FUNCTION__.'#'.__LINE__);

        $rows = $box->find('li');
        printtime(__FUNCTION__.'#'.__LINE__);

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
            printtime(__FUNCTION__.'#'.__LINE__);
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
