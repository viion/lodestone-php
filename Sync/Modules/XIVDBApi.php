<?php

namespace Sync\Modules;

$XIVDB_FILE = __DIR__.'/xivdb_data.php';

// Accessor
$XIVDBDATA = false;
if (file_exists($XIVDB_FILE)) {
    $XIVDBDATA = require $XIVDB_FILE;
}

/**
 * Class XIVDBApi
 * @package Sync\Modules
 */
class XIVDBApi
{
	private $Http;
	private $host = 'https://api.xivdb.com';
	private $data;

	function __construct()
	{
		$this->Http = new \Sync\Modules\HttpRequest();
		$this->init();
	}

	public function init()
	{
		global $XIVDBDATA;

		// check global var
		if ($XIVDBDATA) {
			$this->data = $XIVDBDATA;
			return;
		}

		// if still no XIVDB, get it again
		if (!$XIVDBDATA)
		{
			$this->query('exp_table', '/data/exp_table');
			$this->query('classjobs', '/data/classjobs');
			$this->query('grand_company', '/data/grand_company');
			$this->query('minions', '/minion?columns=id,name_en');
			$this->query('mounts', '/mount?columns=id,name_en');
			$this->query('items', '/item?columns=id,name_en,lodestone_id');

			$this->arrange();

			// simplify contents
			$data = json_encode($this->data);
			$data = base64_encode($data);

			// save file
			$data = sprintf('<?php return "%s"; ?>', $data);
			file_put_contents(__DIR__.'/xivdb_data.php', $data);
		}
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
}
