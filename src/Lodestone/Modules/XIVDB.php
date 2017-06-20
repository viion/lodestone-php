<?php

namespace Lodestone\Modules;

/**
 * Class XIVDB
 *
 * @package Lodestone\Modules
 */
class XIVDB
{
    use XIVDBApiTrait;
    use XIVDBDataTrait;

    const MAX_LEVEL = 60;
    const HOST = 'https://api.xivdb.com';
    const HOST_SECURE = 'https://secure.xivdb.com';
    const CACHE = __DIR__.'/data.php';

    /** @var HttpRequest */
    private $http;

    protected static $data = [];

    /**
     * XIVDB constructor.
     */
    function __construct()
    {
        // initialize http request
        $this->http = new HttpRequest();

        if (!$this->isApiReady()) {
            $this->apiItems();
            $this->apiExpTable();
            $this->apiClassJobs();
            $this->apiGc();
            $this->apiBaseParams();
            $this->apiTowns();
            $this->apiGuardians();
            $this->apiMinions();
            $this->apiMounts();

            $this->save();
        }
    }

    /**
     * Set data
     *
     * @param $data
     */
    public static function setData($data)
    {
        self::$data = $data;
    }

    /**
     * Delete cache status file, this
     * will cause api to redownload
     * all data and overwrite.
     */
    public function clearCache()
    {
        if ($this->isApiReady()) {
            unlink(self::CACHE);
        }
    }

    /**
     * Generate hash
     *
     * @param $value
     * @param int $length
     * @return bool|string
     */
    private function getStorageHash($value, $length = 8)
    {
        // assuming no collisions for 8 characters,
        // we don't have much data
        return substr(md5(strtolower(htmlentities(html_entity_decode($value, ENT_QUOTES | ENT_HTML401), ENT_QUOTES | ENT_HTML401))), 0, $length);
    }

    /**
     * Save XIVDB data to a PHP file
     */
    private function save()
    {
        self::$data['_CACHED_'] = time();

        $string = [];
        $string[] = "<?php";
        $string[] = "//";
        $string[] = "// THIS FILE IS AUTO GENERATED";
        $string[] = "// DO NOT EDIT, DELETE FILE TO UPDATE";
        $string[] = "//";
        $string[] = "return " . var_export(self::$data, true) . ";";
        $string = implode("\n", $string);

        file_put_contents(self::CACHE, $string);
    }
}

// php cached datas
if (file_exists(XIVDB::CACHE)) {
    XIVDB::setData(require_once XIVDB::CACHE);
}
