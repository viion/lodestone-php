<?php

namespace Lodestone\Modules;

/**
 * Class XIVDB
 * @package src\Modules
 */
class XIVDB
{
    const HOST = 'https://api.xivdb.com';
    const CACHE = __DIR__.'/xivdb.json';

    private $Http;
    private $data;

    /**
     * XIVDB constructor.
     */
    function __construct()
    {
        $this->Http = new HttpRequest;
        $this->init();
    }

    /**
     * initialize
     */
    public function init()
    {
        // if no cache file, get the data
        if (!file_exists(self::CACHE)) {
            $this->query('exp_table', '/data/exp_table');
            $this->query('classjobs', '/data/classjobs');
            $this->query('grand_company', '/data/grand_company');
            $this->query('minions', '/minion?columns=id,name_en');
            $this->query('mounts', '/mount?columns=id,name_en');
            $this->query('items', '/item?columns=id,name_en,lodestone_id');

            $this->arrange();

            // simplify contents
            $data = json_encode($this->data);
            file_put_contents(self::CACHE, $data);
        }

        // decode data
        $this->data = file_get_contents(self::CACHE);
        $this->data = json_decode($this->data, true);
        Logger::write(__CLASS__, __LINE__, 'XIVDB Ready');
    }

    /**
     * Get some XIVDB data (this would of already been pre-populated)
     * @param $type
     * @return mixed
     */
    public function get($type)
    {
        return $this->data[$type];
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $name
     * @param $route
     */
    private function query($name, $route)
    {
        $data = $this->Http->get(self::HOST . $route);
        $this->data[$name] = json_decode($data, true);
    }

    /**
     * Clear cache file
     */
    public function clearCache()
    {
        unlink(self::CACHE);
        Logger::write(__CLASS__, __LINE__, 'XIVDB Cache Cleared');
    }

    /**
     * Clear cache file
     * todo : do it!
     */
    public function checkCache()
    {
        // get latest patch
    }

    /**
     * @param $name
     * @return bool
     */
    public function getRoleId($name)
    {
        $classjobs = $this->data['classjobs'];

        foreach($classjobs as $cj) {
            if (strtolower($cj['name_en']) == strtolower($name)) {
                return $cj['id'];
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function searchForItem($name)
    {
        foreach($this->data['items'] as $item) {
            if (strtolower($item['name_en']) == strtolower($name)) {
                return $item;
            }
        }

        return false;
    }

    /**
     * Arrange some data from the api
     */
    private function arrange()
    {
        // build array of items against their lodestone id
        foreach($this->data['items'] as $i => $obj) {
            unset($this->data['items'][$i]);

            $id = $obj['lodestone_id'] ? $obj['lodestone_id'] : 'game_'. $obj['id'];
            $this->data['items'][$id] = $obj;
        }

        // build array of items against their lodestone id
        foreach($this->data['classjobs'] as $i => $obj) {
            unset($this->data['classjobs'][$i]);
            $this->data['classjobs'][$obj['id']] = $obj;
        }
    }
}
