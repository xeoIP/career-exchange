<?php

namespace App\Helpers;

class Curl
{
	public static $userAgent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";
	public static $httpHeader = [
		'Accept-Charset: utf-8',
		'Accept-Language: en-us,en;q=0.7,bn-bd;q=0.3'
	];

	/**
	 * @param $url
	 * @param string $cookie_file
	 * @param string $post_data
	 * @param string $referer_url
	 * @return mixed|string
	 */
	public static function fetch($url, $cookie_file = null, $post_data = null, $referer_url = null)
	{
        // Use PHP 'file_get_contents' function if cURL is not enable
        if (!function_exists('curl_init') || !function_exists('curl_exec')) {
            return self::fileGetContents($url);
        }

        // Use cURL
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);

		if ($post_data != '') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // Set the post data
			curl_setopt($ch, CURLOPT_POST, 1); // This is a POST query
		}

		curl_setopt($ch, CURLOPT_HEADER, 0);

		if (strpos(strtolower($url), 'https://') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // To disable SSL Cert checks
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$httpHeader);
		curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);

		if (!empty($referer_url)) {
			curl_setopt($ch, CURLOPT_REFERER, $referer_url);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // We want the content after the query
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Follow Location redirects

		if (!empty($cookie_file)) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); // Read cookie information
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); // Write cookie information
		}

		$result = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if ($result) {
			return $result;
		} else {
			return $error;
		}
	}

    /**
     * @param $url
     * @return string
     */
    public static function fileGetContents($url)
    {
        try {
            $result = file_get_contents($url);
        } catch (\Exception $e) {
            $result = $e->getMessage();
            if ($result == '') {
                $result = 'Unknown Error.';
            }
        }
        return $result;
    }

	/**
	 * @param $url
	 * @param $save_to
	 * @param string $cookie_file
	 */
	public static function grabImage($url, $save_to, $cookie_file = null)
	{
		$url = str_replace(['&amp;'], ['&'], $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if (strpos(strtolower($url), 'https://') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (!empty($cookie_file)) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); // Read cookie information
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); // Write cookie information
		}
		$raw = curl_exec($ch);
		curl_close($ch);

		if (file_exists($save_to)) {
			unlink($save_to);
		}
		if (file_put_contents($save_to, $raw) === false) {
			echo $url . ' don\'t save in ' . $save_to . ".\n";
			exit();
		}
	}

	/**
	 * @param $url
	 * @param null $cookies
	 * @return mixed
	 */
	public static function getContent($url, $cookies= null)
	{
		$ch = curl_init();
		if (strpos(strtolower($url), 'https://') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // To disable SSL Cert checks
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
		if (!empty($cookies)) {
			curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		$out = curl_exec($ch);
		curl_close($ch);

		return $out;
	}

	/**
	 * @param $url
	 * @param $params
	 * @param null $cookies
	 * @return mixed
	 */
	public static function getContentByForm($url, $params, $cookies = null)
	{
		$ch = curl_init();
		if (strpos(strtolower($url), 'https://') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // To disable SSL Cert checks
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
		if (!empty($cookies)) {
			curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$out = curl_exec($ch);
		curl_close($ch);

		return $out;
	}

	/**
	 * @param $url
	 * @return string
	 */
	public static function getCookies($url)
	{
		$ch = curl_init();
		if (strpos(strtolower($url), 'https://') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // To disable SSL Cert checks
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);

		preg_match_all('|Set-Cookie: (.*);|U', $result, $tmp);
		if (isset($tmp[1]) and !empty($tmp[1])) {
			$cookies = implode(';', $tmp[1]);
		} else {
			$cookies = null;
		}

		return $cookies;
	}
}
