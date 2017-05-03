<?php

namespace Lodestone\Modules;

/**
 * Class HttpRequest
 * @package src\Modules
 */
class HttpRequest
{
    /**
     * curl options
     */
    const CURL_OPTIONS = [
        CURLOPT_POST => false,
        CURLOPT_BINARYTRANSFER => false,
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_HTTPHEADER => ['Content-type: text/html; charset=utf-8', 'Accept-Language: en'],
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.0.0 Safari/537.36',
        CURLOPT_ENCODING => '',
    ];

    /**
     * @param $url
     * @return bool|string
     */
    public function get($url)
    {
        $url = str_ireplace(' ', '+', $url);
        Logger::write(__CLASS__, __LINE__, 'GET: '. $url);

        // build handle
        $handle = curl_init();
        curl_setopt_array($handle, self::CURL_OPTIONS);
        curl_setopt($handle, CURLOPT_URL, $url);

        // handle response
        $response = curl_exec($handle);
        $hlength = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $data = substr($response, $hlength);

        curl_close($handle);
        unset($handle);

        Logger::write(__CLASS__, __LINE__, 'RESPONSE: '. $httpCode);

        return $data;
    }
}