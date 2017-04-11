<?php
//
// Parse a free company profile
//

namespace Sync\Parser;

class FreeCompany extends ParserHelper
{
    /**
     * Parse FC (todo : do it)
     * @param $html
     * @return array|bool
     */
	public function parse($html)
	{
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');

        // check exists
        if ($this->is404($html)) {
            return false;
        }

        $this->setInitialDocument($html);

		$started = microtime(true);
		$this->parseHeader();
		$this->parseProfile();
		$this->parseFocus();

        output('PARSE DURATION: %s ms', [ round(microtime(true) - $started, 3) ]);

        #show($this->data);die;

		return $this->data;
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
			$crest[] = $img->getAttribute('src');
		}
		$this->add('crest', $crest);

		// grand company
		$data = $box->find('.entry__freecompany__gc')->plaintext;
		$data = explode('<', trim($data));
		$data = trim($data[0]);
		$this->add('grand_company', $data);

		// name
		$data = trim($box->find('.entry__freecompany__name')->plaintext);
		$this->add('name', $data);

		// server
		$data = trim($box->find('.entry__freecompany__gc', 1)->plaintext);
		$this->add('server', $data);

		// id
		$data = $box->find('a', 0)->getAttribute('href');
		$data = trim(explode('/', $data)[3]);
		$this->add('id', $data);
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
		$this->add('tag', $data);

		// formed
        $timestamp = $this->getTimestamp($box->find('.freecompany__text', 2));
		$this->add('formed', $timestamp);

		// active members
		$data = $box->find('.freecompany__text', 3)->plaintext;
		$data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
		$this->add('members', $data);

		// rank
        $data = $box->find('.freecompany__text', 4)->plaintext;
		$data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
		$this->add('rank', $data);

		// ranking
		$weekly = $box->find('.character__ranking__data th', 0)->plaintext;
		$weekly = filter_var($weekly, FILTER_SANITIZE_NUMBER_INT);
        $monthly = $box->find('.character__ranking__data th', 1)->plaintext;
        $monthly = filter_var($monthly, FILTER_SANITIZE_NUMBER_INT);

		$this->add('ranking', [
			'weekly' => $weekly,
			'monthly' => $monthly,
		]);

		// slogan
		$data = $box->find('.freecompany__text__message', 0)->innertext;
		$data = str_ireplace("<br/>", "\n", $data);
		$this->add('slogan', $data);

		// estate
        $this->add('estate', [
            'name' => $box->find('.freecompany__estate__name')->plaintext,
            'plot' => $box->find('.freecompany__estate__text')->plaintext,
            'greeting' => $box->find('.freecompany__estate__greeting')->plaintext,
        ]);

        $reputation = [];
        foreach($box->find('.freecompany__reputation') as $rep) {
            $progress = $rep->find('.character__bar div', 0)->getAttribute('style');
            $reputation[] = [
                'name' => $rep->find('.freecompany__reputation__gcname')->plaintext,
                'rank' => $rep->find('.freecompany__reputation__rank')->plaintext,
                'progress' => filter_var($progress, FILTER_SANITIZE_NUMBER_INT),
            ];
        }
        $this->add('reputation', $reputation);
	}

    /**
     * Parse FC
     */
	private function parseFocus()
    {
        $box = $this->getDocumentFromClassname('.ldst__window', 1);

        // active
        $data = trim($box->find('.freecompany__text', 0)->plaintext);
        $this->add('active', $data);

        // recruitment
        $data = trim($box->find('.freecompany__text', 1)->plaintext);
        $this->add('recruitment', $data);

        // ---------------------------------------------------------------

        $focus = [];
        if ($hasNodes = $box->find('.freecompany__focus_icon', 0)) {
            foreach ($hasNodes->find('li') as $node) {
                $status = true;
                if ($node->getAttribute('class') == 'freecompany__focus_icon--off') {
                    $status = false;
                }

                $focus[] = [
                    'status' => $status,
                    'icon' => $node->find('img', 0)->src,
                    'name' => $node->find('p', 0)->plaintext,
                ];
            }
        }

        $this->add('focus', $focus);

        // ---------------------------------------------------------------

        $seeking = [];
        if ($focusNodes = $box->find('.freecompany__focus_icon', 1)) {
            foreach ($focusNodes->find('li') as $node) {
                $status = true;
                if ($node->getAttribute('class') == 'freecompany__focus_icon--off') {
                    $status = false;
                }

                $seeking[] = [
                    'status' => $status,
                    'icon' => $node->find('img', 0)->src,
                    'name' => $node->find('p', 0)->plaintext,
                ];
            }
        }

        $this->add('seeking', $seeking);
    }
}
