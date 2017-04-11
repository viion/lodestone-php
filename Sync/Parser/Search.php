<?php

namespace Sync\Parser;

/**
 * Parse character data
 * Class Search
 * @package Sync\Parser
 */
class Search extends ParserHelper
{
    /**
     * @param $html
     * @return array|bool
     */
	public function parseCharacterSearch($html)
	{
		$html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
		$this->setInitialDocument($html);

        $box = $this->getDocumentFromClassname('.ldst__window');

        // get total
		$total = filter_var($box->find('.parts__total')->plaintext, FILTER_SANITIZE_NUMBER_INT);
        $this->add('total', $total);

        $results = [];
        foreach($box->find('.entry') as $node) {
            $character = [
                'id' => explode('/', $node->find('a', 0)->getAttribute('href'))[3],
                'avatar' => explode('?', $node->find('.entry__chara__face img', 0)->src)[0],
                'name' => trim($node->find('.entry__name')->plaintext),
                'server' => trim($node->find('.entry__world')->plaintext),
            ];

            $results[] = $character;
        }

        $this->add('count', count($results));
        $this->add('results', $results);

		return $this->data;
	}

    /**
     * @param $html
     * @return array|bool
     */
    public function parseFreeCompanySearch($html)
    {
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

        $box = $this->getDocumentFromClassname('.ldst__window');

        // get total
        $total = filter_var($box->find('.parts__total')->plaintext, FILTER_SANITIZE_NUMBER_INT);
        $this->add('total', $total);

        $results = [];
        foreach($box->find('.entry') as $node) {
            $entry = [
                'id' => explode('/', $node->find('a', 0)->getAttribute('href'))[3],
                'name' => trim($node->find('.entry__name')->plaintext),
                'server' => trim($node->find('.entry__world')->plaintext),
                'crest' => [],
            ];

            // get all emblum imgs
            foreach($node->find('.entry__freecompany__crest__image img') as $img) {
                $entry['crest'][] = explode('?', $img->src)[0];
            }

            $results[] = $entry;
        }

        $this->add('count', count($results));
        $this->add('results', $results);

        return $this->data;
    }

    /**
     * @param $html
     * @return array|bool
     */
    public function parseLinkshellSearch($html)
    {
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

        $box = $this->getDocumentFromClassname('.ldst__window');

        // get total
        $total = filter_var($box->find('.parts__total')->plaintext, FILTER_SANITIZE_NUMBER_INT);
        $this->add('total', $total);

        $results = [];
        foreach($box->find('.entry') as $node) {
            $entry = [
                'id' => explode('/', $node->find('a', 0)->getAttribute('href'))[3],
                'name' => trim($node->find('.entry__name')->plaintext),
                'server' => trim($node->find('.entry__world')->plaintext),
            ];

            // get all emblum imgs
            foreach($node->find('.entry__freecompany__crest__image img') as $img) {
                $entry['crest'][] = explode('?', $img->src)[0];
            }

            $results[] = $entry;
        }

        $this->add('count', count($results));
        $this->add('results', $results);

        return $this->data;
    }
}
