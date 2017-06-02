<?php

namespace Lodestone\Modules;

/**
 * Class XIVDBDataTrait
 *
 * @package Lodestone\Modules
 */
trait XIVDBDataTrait
{
    /**
     * @param $index
     * @return mixed
     */
    private function getData($index)
    {
        $json = file_get_contents(self::CACHE_DIR .'/'. $index .'.json');
        return json_decode($json, true);
    }

    public function getExpGained($level, $exp)
    {
        $data = $this->getData('exp');

        // add up the total exp obtained
        // minus 2 because you don't start
        // at level 0 and you cant level
        // above level 60.
        array_splice($data, $level);
        $exp = $exp + array_sum($data);

        return $exp;
    }

    public function getTotalExp()
    {
        $data = $this->getData('exp');
        return array_sum($data);
    }

    public function getBaseParamId($name)
    {
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
            return $manual[strtolower($name)];
        }

        return $this->commonGetNameFromId('baseparams', $name);
    }

    public function getItemId($name)
    {
        $method = sprintf('%s(%s)', __METHOD__, $name);
        Benchmark::start($method,__LINE__);

        $hash = $this->getStorageHash($name);
        $file = $this->getStorageHash($name, 2);
        $json = $this->getData('items.'. $file);

        Benchmark::finish($method,__LINE__);

        return $json[$hash];
    }

    public function getClassJobId($name)
    {
        return $this->commonGetNameFromId('classjobs', $name);
    }

    public function getClassJobName($id)
    {
        $json = $this->getData('classjobs2');
        return $json[$id];
    }

    public function getGcId($name)
    {
        return $this->commonGetNameFromId('gc', $name);
    }

    public function getTownId($name)
    {
        return $this->commonGetNameFromId('towns', $name);
    }

    public function getGuardianId($name)
    {
        return $this->commonGetNameFromId('guardians', $name);
    }

    public function getMinionId($name)
    {
        return $this->commonGetNameFromId('minions', $name);
    }

    public function getMountId($name)
    {
        return $this->commonGetNameFromId('mounts', $name);
    }

    // - - - - - - - -
    // Common actions
    // - - - - - - - -

    /**
     * Build an array based on hashed names
     *
     * @param $data
     * @return array
     */
    private function commonGetNameFromId($index, $name)
    {
        $hash = $this->getStorageHash($name);
        $json = $this->getData($index);

        return $json[$hash];
    }
}