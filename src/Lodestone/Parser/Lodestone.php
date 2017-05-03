<?php

namespace Lodestone\Parser;

use Lodestone\Modules\Routes;
use Lodestone\Modules\XIVDB;

/**
 * Parse character data
 * Class Search
 * @package src\Parser
 */
class Lodestone extends ParserHelper
{
    private $xivdb;

    /**
     * Lodestone constructor.
     */
    function __construct()
    {
        $this->xivdb = new XIVDB();
    }

    /**
     * @return array|bool
     */
    public function parseBanners()
    {
        $this->ensureHtml();

        $html = $this->html;
        $this->setInitialDocument($html);

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
     * @return array|bool
     */
    public function parseTopics()
    {
        $this->ensureHtml();

        $html = $this->html;
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

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
        $this->ensureHtml();

        $html = $this->html;
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

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
        $this->ensureHtml();

        $html = $this->html;
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

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
        $this->ensureHtml();

        $html = $this->html;
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

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
        $this->ensureHtml();

        $html = $this->html;
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

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
        $this->ensureHtml();

        $html = $this->html;
        $html = $this->trim($html, 'class="ldst__main"', 'class="ldst__side"');
        $this->setInitialDocument($html);

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

        $html = $this->html;
        $this->setInitialDocument($html);

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

        $html = $this->html;
        $this->setInitialDocument($html);

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
                    'id' => $this->xivdb->getRoleId($classjob),
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

        $html = $this->html;

        $trackerNumber = [
                             'ja' => 0,
                             'en' => 1,
                             'fr' => 2,
                             'de' => 3,
                         ][$lang];

        $this->setInitialDocument($html);

        $link = $this->getDocument()->find('.devtrack_btn', $trackerNumber)->href;
        return $link;
    }

    /**
     * @return array
     */
    public function parseDevPostLinks()
    {
        $this->ensureHtml();

        $html = $this->html;

        $this->setInitialDocument($html);
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

        $html = $this->html;
        $this->setInitialDocument($html);

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
        $timestamp = trim(str_ireplace('&nbsp;', ' ', htmlentities($timestamp)));
        if (stripos($timestamp, 'Yesterday') !== false || stripos($timestamp, 'Today') !== false) {
            list($date, $time, $ampm) = explode(' ', $timestamp);
            list($hour, $minute) = explode(':', $time);
            $now = new \DateTime("now", new \DateTimeZone('Japan'));

            // add 12 hours if it's pm
            $hour = ($ampm == 'PM') ? $hour + 12 : $hour;

            // minus a day if it was posted yesterday
            if (stripos($timestamp, 'Today') !== false) {
                $now->modify('-1 day');
            }

            $now->setTime($hour, $minute);
        } else {
            list($date, $time, $ampm) = explode(' ', $timestamp);
            list($month, $day, $year) = explode('-', $date);
            list($hour, $minute) = explode(':', $time);

            // add 12 hours if it's pm
            $hour = ($ampm == 'PM') ? $hour + 12 : $hour;

            $timestamp = mktime($hour, $minute, 0, $month, $day, $year);

            // should be readable
            $now = new \DateTime('@'. $timestamp, new \DateTimeZone('Japan'));
        }

        $now = $now->setTimezone(new \DateTimeZone('UTC'));

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
        $message = str_ireplace(['<body>', '</body>'], ['<article>', '</article>'], $message);

        $data['time'] = $now->format('Y-m-d H:i:s');
        $data['message'] = trim($message);

        return $data;
    }
}
