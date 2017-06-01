<?php

namespace Lodestone\Modules;
use Lodestone\Validator\ValidationException;

/**
 * Class XIVDB
 * @package src\Modules
 */
class XIVDB
{
    const HOST = 'https://api.xivdb.com';
    const HOST_SECURE = 'https://secure.xivdb.com';
    const CACHE = __DIR__.'/xivdb.json';

    const API_CALLS = [
        'exp_table' => '/data/exp_table',
        'classjobs' => '/data/classjobs',
        'gc' => '/data/gc',
        //'gc_ranks' => '/data/gc_ranks',
        'baseparams' => '/data/baseparams',
        'towns' => '/data/towns',
        'guardians' => '/data/guardians',
        'minions' => '/minion?columns=id,name,icon',
        'mounts' => '/mount?columns=id,name,icon',
        'items' => '/item?columns=id,name',
    ];

    /** @var HttpRequest */
    private $Http;

    /** @var array */
    private static $data;

    /** @var array */
    private static $icons;

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
            foreach(self::API_CALLS as $index => $query) {
                $this->query($index, $query);
            }

            // array some data
            $this->handleIcons();
            $this->handleCollection();

            // simplify contents
            $data = json_encode([
                'data' => self::$data,
                'icons' => self::$icons,
            ]);

            file_put_contents(self::CACHE, $data);
        }

        // decode data
        $xivdb = file_get_contents(self::CACHE);
        $xivdb = json_decode($xivdb, true);

        self::$data = $xivdb['data'];
        self::$icons = $xivdb['icons'];

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
     * Handle icons
     */
    private function handleIcons()
    {
        $icons = [];

        foreach(['minions','mounts'] as $index) {
            foreach(self::$data[$index] as $collectable) {
                $id = $collectable['id'];
                $icon = $collectable['icon'];

                $icon = $this->iconize($icon);
                $icon = str_ireplace('004', '068', $icon) .'.png';
                $icon = sprintf('%s/img/game/%s', self::HOST_SECURE, $icon);

                $icons[$index][$id] = $icon;
            }
        }

        self::$icons = $icons;
    }

    /**
     * Arrange some data from the api
     */
    private function handleCollection()
    {
        self::initialize();

        $data = [];

        // store everything by a hash of its name
        foreach(array_keys(self::API_CALLS) as $index) {
            foreach (self::$data[$index] as $i => $obj) {
                $value = isset($obj['name'])
                    ? $obj['name']
                    : (isset($obj['name_en'])
                        ? $obj['name_en']
                        : $obj['id']);

                if (!$index || !$value) {
                    continue;
                }

                $hash = $this->getStorageHash($value);
                $data[$index][$hash] = $obj['id'];
            }
        }

        self::$data = $data;
    }

    /**
     * Generate hash
     *
     * @param $value
     * @return bool|string
     */
    private function getStorageHash($value)
    {
        // assuming no collisions for 8 characters,
        // we don't have much data
        return substr(md5(strtolower($value)), 0, 8);
    }

    /**
     * Convert icon id to real path
     *
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

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Get data from xivdb
     *
     * @param $type
     * @param $name
     * @return mixed
     */
    public function getDataEntry($type, $name)
    {
        Benchmark::start(__METHOD__,__LINE__);
        self::initialize();

        $hash = $this->getStorageHash($name);
        $value = isset(self::$data[$type][$hash]) ? self::$data[$type][$hash] : false;

        if (!$value) {
            throw new ValidationException(
                sprintf('Could not find XIVDB ID for: Type: %s Hash: %s Name: %s',
                    $type, $hash, $name
                )
            );
        }

        return $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getRoleId($name)
    {
        return $this->getDataEntry('classjobs', $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function getItemId($name)
    {
        return $this->getDataEntry('items', $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMinionId($name)
    {
        return $this->getDataEntry('minions', $name);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMinionIcon($id)
    {
        return self::$icons['minions'][$id];
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMountId($name)
    {
        return $this->getDataEntry('mounts', $name);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMountIcon($id)
    {
        return self::$icons['mounts'][$id];
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGrandCompanyId($name)
    {
        return $this->getDataEntry('gc', $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGrandCompanyRankId($name)
    {
        return $this->getDataEntry('gc_ranks', $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function getGuardianId($name)
    {
        return $this->getDataEntry('guardians', $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function getTownId($name)
    {
        return $this->getDataEntry('towns', $name);
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

        return $this->getDataEntry('baseparams', $name);
    }
}
