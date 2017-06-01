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
    private static $data;

    /** @var bool */
    private static $initialized = false;

    /**
     * XIVDB constructor.
     */
    function initialize()
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;

        Logger::write(__CLASS__, __LINE__, 'Starting XIVDB Module');
        Benchmark::start(__METHOD__,__LINE__);

        // create http request object
        $this->Http = new HttpRequest;

        // if no cache file, get the data
        if (!file_exists(self::CACHE)) {
            $list = [
                ['exp_table', '/data/exp_table'],
                ['classjobs', '/data/classjobs'],
                ['gc', '/data/gc'],
                //['gc_ranks', '/data/gc_ranks'],
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
            $data = json_encode(self::$data);
            file_put_contents(self::CACHE, $data);
        }

        // decode data
        self::$data = file_get_contents(self::CACHE);
        self::$data = json_decode(self::$data, true);
        Logger::write(__CLASS__, __LINE__, 'XIVDB Ready');
        Benchmark::finish(__METHOD__,__LINE__);
    }

    /**
     * Get some XIVDB data (this would of already been pre-populated)
     * @param $type
     * @return mixed
     */
    public function get($type)
    {
        self::initialize();
        return self::$data[$type];
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        self::initialize();
        return self::$data;
    }

    /**
     * @param $name
     * @param $route
     */
    private function query($name, $route)
    {
        $data = $this->Http->get(self::HOST . $route);
        self::$data[$name] = json_decode($data, true);
        Logger::write(__CLASS__, __LINE__, 'Obtained: '. $name);
    }

    /**
     * Clear cache file
     */
    public function clearCache()
    {
        self::initialize();
        unlink(self::CACHE);
        Logger::write(__CLASS__, __LINE__, 'XIVDB Cache Cleared');
    }

    /**
     * Clear cache file
     * todo : do it!
     */
    public function checkCache()
    {
        self::initialize();
        // get latest patch
    }

    /**
     * @param $name
     * @return bool
     */
    public function getRoleId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['classjobs'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function searchForItem($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['items'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj;
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getItemId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['items'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMinionId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['minions'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMountId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['mounts'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getBaseParamId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        // special, Lodestone only returns "Fire" "Water" etc
        // however the attribute is named "Fire Resistance", to
        // reduce multi-language, I will manually convert these
        $manual = [
            'fire' => 37,
            'ice' => 38,
            'wind' => 39,
            'earth' => 40,
            'thunder' => 41,
            'water' => 42.
        ];

        if (isset($manual[strtolower($name)])) {
            Benchmark::finish(__METHOD__,__LINE__);
            return $manual[strtolower($name)];
        }

        foreach(self::$data['baseparams'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGrandCompanyId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['gc'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGrandCompanyRankId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['gc_ranks'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGuardianId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['guardians'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getTownId($name)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        foreach(self::$data['towns'] as $obj) {
            if (strtolower($obj['name_en']) == strtolower($name)) {
                Benchmark::finish(__METHOD__,__LINE__);
                return $obj['id'];
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return false;
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function getMountIcon($id)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        if (!isset(self::$data['mounts'][$id])) {
            Benchmark::finish(__METHOD__,__LINE__);
            return false;
        }

        $icon = self::$data['mounts'][$id]['icon'];
        $icon = $this->iconize($icon);
        $icon = str_ireplace('004', '068', $icon) .'.png';

        Benchmark::finish(__METHOD__,__LINE__);
        return sprintf('%s/img/game/%s', self::HOST_SECURE, $icon);
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function getMinionIcon($id)
    {
        Benchmark::start(__METHOD__,__LINE__);

        self::initialize();
        if (!isset(self::$data['minions'][$id])) {
            Benchmark::finish(__METHOD__,__LINE__);
            return false;
        }

        $icon = self::$data['minions'][$id]['icon'];
        $icon = $this->iconize($icon);
        $icon = str_ireplace('004', '068', $icon) .'.png';

        Benchmark::finish(__METHOD__,__LINE__);
        return sprintf('%s/img/game/%s', self::HOST_SECURE, $icon);
    }

    /**
     * @param $number
     * @return string
     */
    public function iconize($number)
    {
        self::initialize();
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
        self::initialize();

        $data = [];

        // build array of items against their lodestone id
        foreach(self::$data['items'] as $i => $obj) {
            $id = $obj['lodestone_id'] ? $obj['lodestone_id'] : 'game_'. $obj['id'];
            $data['items'][$id] = $obj;
        }

        // build array of other content against their ids
        foreach(['classjobs', 'minions', 'mounts', 'gc', 'baseparams', 'towns', 'guardians'] as $index) {
            foreach(self::$data[$index] as $i => $obj) {
                $data[$index][$obj['id']] = $obj;
            }
        }

        self::$data = $data;
    }
}
