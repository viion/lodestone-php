<?php

namespace Lodestone\Modules;

/**
 * Class XIVDBApiTrait
 *
 * @package Lodestone\Modules
 */
trait XIVDBApiTrait
{
    /**
     * @param $route
     * @return mixed
     */
    public function api($route)
    {
        $json = $this->http->get(self::HOST . $route);
        return json_decode($json, true);
    }

    /**
     * @param $index
     * @param $data
     */
    public function apiStorage($index, $data)
    {
        self::$data[$index] = $data;
    }

    /**
     * is the api ready?
     * @return bool
     */
    public function isApiReady()
    {
        $status = count(self::$data);

        return $status;
    }

    /**
     * items (quite involved)
     */
    protected function apiItems()
    {
        $data = $this->api('/lodestone/php/items');
        $arr = [];

        foreach($data as $row) {
            $row = (Object)$row;

            $hash = $this->getStorageHash($row->name);
            $file = $this->getStorageHash($row->name, 2);

            $arr[$file][$hash] = $row->id;
        }

        foreach($arr as $file => $items) {
            $this->apiStorage('items_'. $file, $items);
        }
    }

    /**
     * exp table
     */
    protected function apiExpTable()
    {
        $data = $this->api('/data/exp_table');
        $arr = [];

        foreach($data as $row) {
            $row = (Object)$row;
            $arr[$row->level] = $row->exp;

            if ($row->level == self::MAX_LEVEL) {
                // remove on expansion
                $arr[60] = 0;

                // you dont start at 0 so reset exp
                $arr[0] = 0;
                break;
            }
        }

        $this->apiStorage('exp', $arr);
    }

    /**
     * class jobs
     */
    protected function apiClassJobs()
    {
        $data = $this->api('/data/classjobs');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('classjobs', $arr);

        $arr = $this->commonRefactorIdName($data);
        $this->apiStorage('classjobs2', $arr);
    }

    /**
     * grand company
     */
    protected function apiGc()
    {
        $data = $this->api('/data/gc');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('gc', $arr);
    }

    /**
     * base params
     */
    protected function apiBaseParams()
    {
        $data = $this->api('/data/baseparams');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('baseparams', $arr);
    }

    /**
     * towns
     */
    protected function apiTowns()
    {
        $data = $this->api('/data/towns');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('towns', $arr);
    }

    /**
     * guardians
     */
    protected function apiGuardians()
    {
        $data = $this->api('/data/guardians');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('guardians', $arr);
    }

    /**
     * minions
     */
    protected function apiMinions()
    {
        $data = $this->api('/minion?columns=id,name_en');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('minions', $arr);
    }

    /**
     * mounts
     */
    protected function apiMounts()
    {
        $data = $this->api('/mount?columns=id,name_en');

        $arr = $this->commonRefactorNameId($data);
        $this->apiStorage('mounts', $arr);
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
    private function commonRefactorNameId($data)
    {
        $arr = [];
        foreach($data as $row) {
            $row = (Object)$row;
            $hash = $this->getStorageHash($row->name_en);
            $arr[$hash] = $row->id;
        }

        return $arr;
    }

    private function commonRefactorIdName($data)
    {
        $arr = [];
        foreach($data as $row) {
            $row = (Object)$row;
            $arr[$row->id] = $row->name_en;
        }

        return $arr;
    }
}