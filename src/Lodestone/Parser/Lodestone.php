<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Http\Routes;
use Lodestone\Parser\Html\ParserHelper;

/**
 * Parse character data
 * Class Search
 * @package src\Parser
 */
class Lodestone extends ParserHelper
{
    /**
     * @return array
     */
    public function parseBanners()
    {
        $this->initialize();

        $entries = $this->getDocument()->find('#slider_bnr_area li');
        $results = [];

        foreach($entries as $entry) {
            $results[] = [
                'url' => $entry->find('a',0)->href,
                'banner' => explode('?', $entry->find('img', 0)->src)[0],
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseTopics()
    {
        $this->initialize();

        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list--topics');
        $results = [];

        foreach($entries as $entry) {
            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $entry->find('.news__list--title')->plaintext,
                'url' => $entry->find('.news__list--title a', 0)->href,
                'banner' => $entry->find('.news__list--img img', 0)->getAttribute('src'),
                'html' => $entry->find('.news__list--banner p')->innerHtml(),
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseNotices()
    {
        $this->initialize();

        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];

        foreach($entries as $entry) {
            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $entry->find('.news__list--title')->plaintext,
                'url' => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseMaintenance()
    {
        $this->initialize();

        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];

        foreach($entries as $entry) {
            $tag = $entry->find('.news__list--tag')->plaintext;
            $title = $entry->find('.news__list--title')->plaintext;
            $title = str_ireplace($tag, null, $title);

            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $title,
                'url' => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
                'tag' => $tag,
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseUpdates()
    {
        $this->initialize();

        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];

        foreach($entries as $entry) {
            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $entry->find('.news__list--title')->plaintext,
                'url' => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseStatus()
    {
        $this->initialize();

        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];

        foreach($entries as $entry) {
            $tag = $entry->find('.news__list--tag')->plaintext;
            $title = $entry->find('.news__list--title')->plaintext;
            $title = str_ireplace($tag, null, $title);

            $results[] = [
                'time' => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'title' => $title,
                'url' => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
                'tag' => $tag,
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseWorldStatus()
    {
        $this->initialize();

        $entries = $this->getDocumentFromClassname('.parts__space--pb16')->find('div.item-list__worldstatus');
        $results = [];

        foreach($entries as $entry) {
            $results[] = [
                'title' => trim($entry->find('h3')->plaintext),
                'status' => trim($entry->find('p')->plaintext),
            ];
        }

        $this->add('entries', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseFeast()
    {
        $this->ensureHtml();

        $this->setDocument($this->html);

        $entries = $this->getDocument()->find('.wolvesden__ranking__table tr');
        $results = [];

        foreach($entries as $node) {
            $results[] = [
                'character' => [
                    'id' => explode('/', $node->getAttribute('data-href'))[3],
                    'name' => trim($node->find('.wolvesden__ranking__result__name h3', 0)->plaintext),
                    'server' =>trim( $node->find('.wolvesden__ranking__result__world', 0)->plaintext),
                    'avatar' => explode('?', $node->find('.wolvesden__ranking__result__face img', 0)->src)[0],
                ],
                'leaderboard' => [
                    'rank' => $node->find('.wolvesden__ranking__result__order', 0)->plaintext,
                    'rank_previous' => trim($node->find('.wolvesden__ranking__td__prev_order', 0)->plaintext),
                    'win_count' => trim($node->find('.wolvesden__ranking__result__win_count', 0)->plaintext),
                    'win_rate' => str_ireplace('%', null, trim($node->find('.wolvesden__ranking__result__winning_rate', 0)->plaintext)),
                    'matches' => trim($node->find('.wolvesden__ranking__result__match_count', 0)->plaintext),
                    'rating' => trim($node->find('.wolvesden__ranking__result__match_rate', 0)->plaintext),
                    'rank_image' => @trim($node->find('.wolvesden__ranking__td__rank img', 0)->src)
                ],
            ];
        }

        $this->add('results', $results);
        return $this->data;
    }

    /**
     * @return array
     */
    public function parseDeepDungeon()
    {
        $this->ensureHtml();

        $this->setDocument($this->html);

        $entries = $this->getDocument()->find('.deepdungeon__ranking__wrapper__inner li');
        $results = [];

        foreach($entries as $node) {
            if ($node->find('.deepdungeon__ranking__job', 0)) {
                $classjob = $node->find('.deepdungeon__ranking__job img', 0)->getAttribute('title');
            } else {
                $classjob = $this->getDocument()->find('.deepdungeon__ranking__select_job', 0)->find('a.selected', 0)->find('img', 0)->getAttribute('title');
            }

            $results[] = [
                'character' => [
                    'id' => explode('/', $node->getAttribute('data-href'))[3],
                    'name' => trim($node->find('.deepdungeon__ranking__result__name h3', 0)->plaintext),
                    'server' =>trim( $node->find('.deepdungeon__ranking__result__world', 0)->plaintext),
                    'avatar' => explode('?', $node->find('.deepdungeon__ranking__face__inner img', 0)->src)[0],
                ],
                'classjob' => [
                    'name' => $classjob,
                ],
                'leaderboard' => [
                    'rank' => $node->find('.deepdungeon__ranking__result__order', 0)->plaintext,
                    'score' => trim($node->find('.deepdungeon__ranking__data--score', 0)->plaintext),
                    'time' => $this->getTimestamp($node->find('.deepdungeon__ranking__data--time')),
                    'floor' => filter_var($node->find('.deepdungeon__ranking__data--reaching', 0)->plaintext, FILTER_SANITIZE_NUMBER_INT),
                ],
            ];
        }

        $this->add('results', $results);
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function parseDevBlog()
    {
        $html = $this->html;
        $xml = simplexml_load_string($html, null, LIBXML_NOCDATA);
        $json = json_decode(json_encode($xml), true);
        return $json;
    }

    /**
     * @param $lang
     * @return mixed
     */
    public function parseDevTrackingUrl($lang = 'en')
    {
        $this->ensureHtml();
        $this->setDocument($this->html);

        $trackerNumber = [
            'ja' => 0,
            'en' => 1,
            'fr' => 2,
            'de' => 3,
        ][$lang];

        return $this->getDocument()->find('.devtrack_btn', $trackerNumber)->href;
    }

    /**
     * @return array
     */
    public function parseDevPostLinks()
    {
        $this->ensureHtml();

        $this->setDocument($this->html);
        $posts = $this->getDocument()->find('.blockbody li');

        $links = [];
        foreach($posts as $node) {
            $links[] = Routes::LODESTONE_FORUMS . $node->find('.posttitle a', 0)->href;
        }

        return $links;
    }

    /**
     * @param $postId
     * @return array|bool
     */
    public function parseDevPost($postId)
    {
        $this->ensureHtml();
        $this->setDocument($this->html);

        $post = $this->getDocument();

        // get postcount
        $postcount = $post->find('#postpagestats_above', 0)->plaintext;
        $postcount = explode(' of ', $postcount)[1];
        $postcount = filter_var($postcount, FILTER_SANITIZE_NUMBER_INT);

        $data = [
            'title' => $post->find('.threadtitle a', 0)->plaintext,
            'url' => Routes::LODESTONE_FORUMS . $post->find('.threadtitle a', 0)->href . sprintf('?p=%s#post%s', $postId, $postId),
            'post_count' => $postcount,
        ];

        // get post
        $post = $post->find('#post_'. $postId);

        // todo : translate ...
        $timestamp = $post->find('.posthead .date', 0)->plaintext;

        // remove invisible characters
        $timestamp = preg_replace('/[[:^print:]]/', ' ', $timestamp);
        $timestamp = str_ireplace('-', '/', $timestamp);

        // fix time from Tokyo to Europe
        $date = new \DateTime($timestamp, new \DateTimeZone('Asia/Tokyo'));
        $date->setTimezone(new \DateTimeZone('UTC'));
        $timestamp = $date->format('U');

        // get colour
        $color = str_ireplace(['color: ', ';'], null, $post->find('.username span', 0)->getAttribute('style'));

        // fix some post stuff
        $message = trim($post->find('.postcontent', 0)->innerHtml());

        // get signature
        $signature = false;
        if ($post->find('.signaturecontainer', 0)) {
            $signature = trim($post->find('.signaturecontainer', 0)->plaintext);
        }

        // create data
        $data['user'] = [
            'username' => trim($post->find('.username span', 0)->plaintext),
            'color' => $color,
            'title' => trim($post->find('.usertitle', 0)->plaintext),
            'avatar' => Routes::LODESTONE_FORUMS . $post->find('.postuseravatar img', 0)->src,
            'signature' => $signature,
        ];

        // clean up the message
        $replace = [
            "\t" => null,
            "\n" => null,
            '&#13;' => null,
            'â€™' => "'",
            'images/' => Routes::LODESTONE_FORUMS .'images/',
            'members/' => Routes::LODESTONE_FORUMS .'members/',
            'showthread.php' => Routes::LODESTONE_FORUMS .'showthread.php',
        ];

        $message = str_ireplace(array_keys($replace), $replace, $message);
        $message = trim($message);

        $dom = new \DOMDocument;
        $dom->loadHTML($message);
        $message = $dom->saveXML();

        $remove = [
            '<?xml version="1.0" standalone="yes"?>',
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            '<html>', '</html>', '<head>', '</head>',
        ];

        $message = str_ireplace($remove, null, $message);
        $message = str_ireplace([
            '<body>', '</body>', '&#xE2;&#x80;&#x99;',
        ], [
            '<article>', '</article>', "'",
        ], $message);
        
        // dirty fix for iframes
        // https://github.com/viion/lodestone-php/issues/22
        $message = str_ireplace(['allowfullscreen=""/>'], ['allowfullscreen=""></iframe>'], $message);

        $data['time'] = $timestamp;
        $data['message'] = trim($message);

        return $data;
    }
}
