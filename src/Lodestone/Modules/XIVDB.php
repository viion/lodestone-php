<?php

namespace Lodestone\Modules;

/**
 * Class XIVDB
 * @package src\Modules
 */
class XIVDB
{
    const HOST = 'https://api.xivdb.com';
    const HOST_SECURE = 'https://secure.xivdb.com';
    const CACHE = __DIR__.'/xivdb.json';

    /** @var HttpRequest */
    private $Http;

    /** @var array */
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
            $list = [
                ['exp_table', '/data/exp_table'],
                ['classjobs', '/data/classjobs'],
                ['gc', '/data/gc'],
                ['baseparams', '/data/baseparams'],
                ['towns', '/data/towns'],
                ['guardians', '/data/guardians'],
                ['minions', '/minion?columns=id,name_en,icon'],
                ['mounts', '/mount?columns=id,name_en,icon'],
                ['items', '/item?columns=id,name_en,lodestone_id'],
            ];

            foreach($list as $dataset) {
                list($index, $query) = $dataset;
                $this->query($index, $query);
            }

            // array some data
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
        foreach($this->data['classjobs'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
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
        foreach($this->data['items'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj;
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMinionId($name)
    {
        foreach($this->data['minions'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMountId($name)
    {
        foreach($this->data['mounts'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getBaseParamId($name)
    {
        foreach($this->data['baseparam'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGrandCompanyId($name)
    {
        foreach($this->data['gc'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGuardianId($name)
    {
        foreach($this->data['guardians'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getTownId($name)
    {
        foreach($this->data['towns'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                return $obj['id'];
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function getMountIcon($id)
    {
        if (!isset($this->data['mounts'][$id])) {
            return false;
        }

        $icon = $this->data['mounts'][$id]['icon'];
        $icon = $this->iconize($icon);
        $icon = str_ireplace('004', '068', $icon) .'.png';
        return sprintf('%s/img/game/%s', self::HOST_SECURE, $icon);
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function getMinionIcon($id)
    {
        if (!isset($this->data['minions'][$id])) {
            return false;
        }

        $icon = $this->data['minions'][$id]['icon'];
        $icon = $this->iconize($icon);
        $icon = str_ireplace('004', '068', $icon) .'.png';
        return sprintf('%s/img/game/%s', self::HOST_SECURE, $icon);
    }

    /**
     * @param $number
     * @return string
     */
    public function iconize($number)
    {
        $number = intval($number);

        $path = [];

        if (strlen($number) >= 6) {
            $icon = str_pad($number, 5, "0", STR_PAD_LEFT);
            $path[] = $icon[0] . $icon[1] . $icon[2] .'000';
        } else {
            $icon = '0' . str_pad($number, 5, "0", STR_PAD_LEFT);
            $path[] = '0'. $icon[1] . $icon[2] .'000';
        }

        $path[] = $icon;
        $icon = implode('/', $path);
        return $icon;
    }

    /**
     * Arrange some data from the api
     */
    private function arrange()
    {
        $data = [];

        // build array of items against their lodestone id
        foreach($this->data['items'] as $i => $obj) {
            $id = $obj['lodestone_id'] ? $obj['lodestone_id'] : 'game_'. $obj['id'];
            $dataa['items'][$id] = $obj;
        }

        // build array of other content against their ids
        foreach(['classjobs', 'minions', 'mounts', 'gc', 'baseparams', 'towns', 'guardians'] as $index) {
            foreach($this->data[$index] as $i => $obj) {
                $data[$index][$obj['id']] = $obj;
            }
        }

        $this->data = $data;
    }
}
