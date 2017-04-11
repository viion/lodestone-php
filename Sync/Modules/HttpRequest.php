<?php

namespace Sync\Modules;

/**
 * Class HttpRequest
 * @package Sync\Modules
 */
class HttpRequest
{
    /**
     * @param $url
     * @return bool|string
     */
	public function get($url)
	{
	    $url = str_ireplace(' ', '+', $url);

		$handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_POST, false);
        curl_setopt($handle, CURLOPT_BINARYTRANSFER, false);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_TIMEOUT, 10);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_MAXREDIRS, 3);
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-type: text/html; charset=utf-8', 'Accept-Language: en']);
        curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.0.0 Safari/537.36');
        curl_setopt($handle, CURLOPT_ENCODING, '');

        $response = curl_exec($handle);
        $hlength = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $data = substr($response, $hlength);

        curl_close($handle);
        unset($handle);

        // not found or no response
        if ($httpCode == 404 || $httpCode == 204) {
            return false;
        }

        return $data;
	}
}
