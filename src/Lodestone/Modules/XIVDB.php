<?php

namespace Lodestone\Modules;

/**
 * Class XIVDB
 * @package src\Modules
 */
class XIVDB
{
	private $Http;
	private $host = 'https://api.xivdb.com';
	private $cache = __DIR__.'/xivdb.json';
	private $data;

	function __construct()
	{
		$this->Http = new HttpRequest;
		$this->init();
	}

	public function init()
	{
	    // if no cache file, get the data
	    if (!file_exists($this->cache)) {
			$this->query('exp_table', '/data/exp_table');
			$this->query('classjobs', '/data/classjobs');
			$this->query('grand_company', '/data/grand_company');
			$this->query('minions', '/minion?columns=id,name_en');
			$this->query('mounts', '/mount?columns=id,name_en');
			$this->query('items', '/item?columns=id,name_en,lodestone_id');

			$this->arrange();

			// simplify contents
			$data = json_encode($this->data);
			file_put_contents($this->cache, $data);
		}

		// decode data
        $this->data = file_get_contents($this->cache);
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
		$data = $this->Http->get($this->host . $route);
		$this->data[$name] = json_decode($data, true);
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
        //show($this->data['items']);
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

    /**
     * Clear cache file
     */
	public function clearCache()
    {
        unlink($this->cache);
        Logger::write(__CLASS__, __LINE__, 'XIVDB Cache Cleared');
    }
}
