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
        return self::$data[$index];
    }

    /**
     * Get total EXP Gained
     *
     * @param $level
     * @param $exp
     * @return bool
     */
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

    /**
     * Get total possible EXP
     *
     * @return float|int
     */
    public function getTotalExp()
    {
        $data = $this->getData('exp');
        return array_sum($data);
    }

    /**
     * Get ID for base parameter stat
     *
     * @param $name
     * @return array|mixed
     */
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

    /**
     * Get ID for an item
     *
     * @param $name
     * @return mixed
     */
    public function getItemId($name)
    {
        $method = sprintf('%s(%s)', __METHOD__, $name);
        Benchmark::start($method,__LINE__);

        $hash = $this->getStorageHash($name);
        $file = $this->getStorageHash($name, 2);
        $json = $this->getData('items_'. $file);

        Benchmark::finish($method,__LINE__);

        return (isset($json[$hash]) ? $json[$hash] : 0);
    }

    /**
     * Get ID for a job class
     *
     * @param $name
     * @return array
     */
    public function getClassJobId($name)
    {
        return $this->commonGetNameFromId('classjobs', $name);
    }

    /**
     * Get name of a class job from an id
     *
     * @param $id
     * @return mixed
     */
    public function getClassJobName($id)
    {
        $json = $this->getData('classjobs2');
        return (isset($json[$id]) ? $json[$id] : 0);
    }

    /**
     * Get ID for a Grand Company
     *
     * @param $name
     * @return array
     */
    public function getGcId($name)
    {
        return $this->commonGetNameFromId('gc', $name);
    }

    /**
     * Get ID for a town
     *
     * @param $name
     * @return array
     */
    public function getTownId($name)
    {
        return $this->commonGetNameFromId('towns', $name);
    }

    /**
     * Get ID for a Guardian
     *
     * @param $name
     * @return array
     */
    public function getGuardianId($name)
    {
        return $this->commonGetNameFromId('guardians', $name);
    }

    /**
     * Get ID for a minion
     *
     * @param $name
     * @return array
     */
    public function getMinionId($name)
    {
        return $this->commonGetNameFromId('minions', $name);
    }

    /**
     * Get ID for a mount
     *
     * @param $name
     * @return array
     */
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

        return (isset($json[$hash]) ? $json[$hash] : 0);
    }
}
