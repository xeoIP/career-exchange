<?php

/**
 * Get full table name by adding the DB prefix
 *
 * @param $name
 * @return string
 */
function table($name)
{
    return \DB::getTablePrefix() . $name;
}

/**
 * Quote a value with astrophe to inject to an SQL statement
 *
 * @param $value
 * @return mixed
 */
function quote($value)
{
    return \DB::getPdo()->quote($value);
}

/**
 * Check if user is located in the demo's admin panel
 *
 * @return bool
 */
function isDemoAdmin()
{
    return starts_with(getDomain(), config('larapen.core.demo_domain')) &&
        \Request::segment(1) == config('larapen.admin.route_prefix', 'admin');
}

/**
 * Alias of 'isDemoAdmin()'
 * @return bool
 */
function isAdminDemo()
{
    return isDemoAdmin();
}

/**
 * Hide the Email addresses
 *
 * @param $value
 * @return string
 */
function hideEmail($value)
{
    $tmp = explode('@', $value);
    if (isset($tmp[0]) && isset($tmp[1])) {
        $emailUsername = $tmp[0];
        $emailDomain = $tmp[1];

        $hideStr = str_pad('', strlen($emailUsername) - 2, "x");
        $hideUsername = substr($emailUsername, 0, 1) . $hideStr . substr($emailUsername, -1);
        $value = $hideUsername . '@' . $emailDomain;
    }

    return $value;
}

/**
 * Default translator (e.g. en/global.php)
 *
 * @param $string
 * @param array $params
 * @param string $file
 * @param null $locale
 * @return string|\Symfony\Component\Translation\TranslatorInterface
 */
function t($string, $params = [], $file = 'global', $locale = null)
{
    if (is_null($locale)) {
        $locale = config('app.locale');
    }

    return trans($file . '.' . $string, $params, $locale);
}

/**
 * Get default max file upload size (from PHP.ini)
 *
 * @return mixed
 */
function maxUploadSize()
{
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));

    return min($max_upload, $max_post);
}

/**
 * Get max file upload size
 *
 * @return int|mixed
 */
function maxApplyFileUploadSize()
{
    $size = maxUploadSize();
    if ($size >= 5) {
        return 5;
    }

    return $size;
}

/**
 * Escape JSON string
 *
 * Escape this:
 * \b  Backspace (ascii code 08)
 * \f  Form feed (ascii code 0C)
 * \n  New line
 * \r  Carriage return
 * \t  Tab
 * \"  Double quote
 * \\  Backslash caracter
 *
 * @param $value
 * @return mixed
 */
function escapeJsonString($value)
{
    // list from www.json.org: (\b backspace, \f formfeed)
    $escapers = ["\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c"];
    $replacements = ["\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b"];
    $value = str_replace($escapers, $replacements, trim($value));

    return $value;
}

/**
 * @param string $default_ip
 * @return string
 */
function getIp($default_ip = '')
{
    return \App\Helpers\Ip::get($default_ip);
}

/**
 * @return string
 */
function getScheme()
{
	if (isset($_SERVER['HTTPS']) and in_array($_SERVER['HTTPS'], ['on', 1]))
	{
		$protocol = 'https://';
	}
	else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
	{
		$protocol = 'https://';
	}
	else if (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true)
	{
		$protocol = 'https://';
	}
	else
	{
		$protocol = 'http://';
	}

    return $protocol;
}


/**
 * Get host (domain with sub-domains)
 *
 * @return string
 */
function getHost()
{
    $host = (trim(\Request::server('HTTP_HOST')) != '') ? \Request::server('HTTP_HOST') : $_SERVER['HTTP_HOST'];

    if ($host == '') {
        $parsed_url = parse_url(url()->current());
        if (!isset($parsed_url['host'])) {
            $host = $parsed_url['host'];
        }
    }

    return $host;
}

/**
 * Get the sub-domain of the parent domain
 *
 * @return string
 */
function getDomain()
{
	$host = getHost();
	$tmp = explode('.', $host);

	if (count($tmp) > 2) {
		array_forget($tmp, '0');
		$domain = implode('.', $tmp);
	} else {
		$domain = @implode('.', $tmp);
	}

	return $domain;
}

/**
 * Get sub-domain name
 *
 * @return string
 */
function getSubDomainName()
{
    $host = getHost();
    $name = (substr_count($host, '.') > 1) ? trim(current(explode('.', $host))) : '';

    return $name;
}

/**
 * Generate a querystring url for the application.
 *
 * Assumes that you want a URL with a querystring rather than route params
 * (which is what the default url() helper does)
 *
 * @param null $path
 * @param array $inputArray
 * @param null $secure
 * @param bool $localized
 * @return mixed|string
 */
function qsurl($path = null, $inputArray = array(), $secure = null, $localized = true)
{
    if ($localized) {
        $url = lurl($path);
    } else {
        $url = app('url')->to($path, $secure);
    }

    if (!empty($inputArray)) {
        $url = $url . '?' . httpBuildQuery($inputArray);
    }

    return $url;
}

/**
 * @param $array
 * @return mixed|string
 */
function httpBuildQuery($array) {
    $queryString = http_build_query($array);
    $queryString = str_replace(['%5B', '%5D'], ['[', ']'], $queryString);

    return $queryString;
}

/**
 * Localized URL
 *
 * @param null $path
 * @param null $locale
 * @return mixed
 */
function lurl($path = null, $locale = null)
{
    if (empty($locale)) {
        $locale = config('app.locale');
    }

    if (\Request::segment(1) == config('larapen.admin.route_prefix', 'admin')) {
        return url($locale . '/' . $path);
    }

    return \Larapen\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($locale, $path);
}

/**
 * File Size Format
 *
 * @param $bytes
 * @return string
 */
function fileSizeFormat($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * Get Post ID from URL segment
 *
 * @return mixed|null
 */
function getPostIdFromURLSegment()
{
    // Get segment
    $segment = getSegment(2);

    // Get the ad ID
    $segment = strip_tags($segment);
    $tmp = explode('-', $segment);
    $last = explode('.', end($tmp));
    $id = current($last);

    if (is_numeric($id)) {
        return $id;
    } else {
        return null;
    }
}

/**
 * @param $index
 * @param null $default
 * @return mixed
 */
function getSegment($index, $default = null)
{
    $index = (int) $index;

    // Default checking
    $segment = \Request::segment($index, $default);

    // Checking with Default Language parameters
    if ( !ends_with(\Request::url(), '.xml') ) {
        if (!(config('laravellocalization.hideDefaultLocaleInURL') == true && config('app.locale') == config('applang.abbr'))) {
            $segment = \Request::segment(($index + 1), $default);
        }
    }

    return $segment;
}

/**
 * Get file extension
 *
 * @param $filename
 * @return mixed
 */
function getExtension($filename)
{
    $tmp = explode('?', $filename);
    $tmp = explode('.', current($tmp));
    $ext = end($tmp);

    return $ext;
}

/**
 * String strip
 *
 * @param $string
 * @return string
 */
function str_strip($string)
{
    $string = trim(preg_replace('/\s\s+/u', ' ', $string));

    return $string;
}

/**
 * String cleaner
 *
 * @param $string
 * @return mixed|string
 */
function str_clean($string)
{
    $string = strip_tags($string, '<br><br/>');
    $string = str_replace(array('<br>', '<br/>', '<br />'), "\n", $string);
    $string = preg_replace("/[\r\n]+/", "\n", $string);
    /*
    Remove 4(+)-byte characters from a UTF-8 string
    It seems like MySQL does not support characters with more than 3 bytes in its default UTF-8 charset.
    NOTE: you should not just strip, but replace with replacement character U+FFFD to avoid unicode attacks, mostly XSS:
    http://unicode.org/reports/tr36/#Deletion_of_Noncharacters
    */
    $string = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $string);
    $string = mb_ucfirst(trim($string));

    return $string;
}

/**
 * Fixed: MySQL don't accept the comma format number
 *
 * @param $float
 * @param int $decimals
 * @return mixed
 *
 * @todo: Learn why PHP 5.6.6 changes dot to comma in float vars
 */
function fixFloatVar($float, $decimals = 10)
{
    //$float = number_format($float, $decimals, '.', ''); // Best way !
    //$float = rtrim($float, "0");

    if (strpos($float, ',') !== false) {
        $float = str_replace(',', '.', $float);
    }

    return $float;
}

/**
 * Extract emails from string, and get the first
 *
 * @param $string
 * @return string
 */
function extractEmailAddress($string)
{
    $tmp = [];
    preg_match_all('|([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b)|i', $string, $tmp);
    $emails = (isset($tmp[1])) ? $tmp[1] : [];
    $email = head($emails);
    if ($email == '') {
        $tmp = [];
        preg_match("|[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})|i", $string, $tmp);
        $email = (isset($tmp[0])) ? trim($tmp[0]) : '';
        if ($email == '') {
            $tmp = [];
            preg_match("|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b|i", $string, $tmp);
            $email = (isset($tmp[0])) ? trim($tmp[0]) : '';
        }
    }

    return strtolower($email);
}

/**
 * Check if language code is available
 *
 * @param $abbr
 * @return bool
 */
function isAvailableLang($abbr)
{
    $cacheExpiration = (int)config('settings.app_cache_expiration', 60);
    $lang = \Illuminate\Support\Facades\Cache::remember('language.' . $abbr, $cacheExpiration, function () use ($abbr) {
        $lang = \App\Models\Language::where('abbr', $abbr)->first();
        return $lang;
    });

    if (!empty($lang)) {
        return true;
    } else {
        return false;
    }
}

function getHostByUrl($url)
{
	// in case scheme relative URI is passed, e.g., //www.google.com/
	$url = trim($url, '/');

	// If scheme not included, prepend it
	if (!preg_match('#^http(s)?://#', $url)) {
		$url = 'http://' . $url;
	}

	$urlParts = parse_url($url);

	// remove www
	$domain = preg_replace('/^www\./', '', $urlParts['host']);

	return $domain;
}

/**
 * Add rel=”nofollow” to links
 *
 * @param $html
 * @param null $skip
 * @return mixed
 */
function nofollow($html, $skip = null)
{
    return preg_replace_callback(
        "#(<a[^>]+?)>#is", function ($mach) use ($skip) {
        return (
            !($skip && strpos($mach[1], $skip) !== false) &&
            strpos($mach[1], 'rel=') === false
        ) ? $mach[1] . ' rel="nofollow">' : $mach[0];
    },
        $html
    );
}

/**
 * Auto-link URL in string
 *
 * @param $str
 * @param array $attributes
 * @return mixed|string
 */
function auto_link($str, $attributes = array())
{
    // Transform URL to HTML link
    $attrs = '';
    foreach ($attributes as $attribute => $value) {
        $attrs .= " {$attribute}=\"{$value}\"";
    }

    $str = ' ' . $str;
    $str = preg_replace('`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i', '$1<a rel="nofollow" href="$2"' . $attrs . ' target="_blank">$2</a>', $str);
    $str = substr($str, 1);

    // Add rel=”nofollow” to links
    $parse = parse_url('http://' . $_SERVER['HTTP_HOST']);
    $str = nofollow($str, $parse['host']);

    return $str;
}

/**
 * Check tld is a valid tld
 *
 * @param $url
 * @return bool|int
 */
function check_tld($url)
{
    $parsed_url = parse_url($url);
    if ($parsed_url === false) {
        return false;
    }

    $tlds = config('tlds');
    $patten = implode('|', array_keys($tlds));

    return preg_match('/\.(' . $patten . ')$/i', $parsed_url['host']);
}

/**
 * Function to convert hex value to rgb array
 * @param $colour
 * @return array|bool
 *
 * @todo: improve this function
 */
function hex2rgb($colour)
{
    if ($colour[0] == '#') {
        $colour = substr($colour, 1);
    }
    if (strlen($colour) == 6) {
        list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    } elseif (strlen($colour) == 3) {
        list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    } else {
        return false;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);

    return array('r' => $r, 'g' => $g, 'b' => $b);
}

/**
 * Convert hexdec color string to rgb(a) string
 *
 * @param $color
 * @param bool $opacity
 * @return string
 *
 * @todo: improve this function
 */
function hex2rgba($color, $opacity = false)
{
    $default = 'rgb(0,0,0)';

    //Return default if no color provided
    if (empty($color)) {
        return $default;
    }

    //Sanitize $color if "#" is provided
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }

    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif (strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    } else {
        return $default;
    }

    //Convert hexadec to rgb
    $rgb = array_map('hexdec', $hex);

    //Check if opacity is set(rgba or rgb)
    if ($opacity) {
        if (abs($opacity) > 1) {
            $opacity = 1.0;
        }
        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }

    // Return rgb(a) color string
    return $output;
}

/**
 * ucfirst() function for multibyte character encodings
 *
 * @param $string
 * @param string $encoding
 * @return string
 */
function mb_ucfirst($string, $encoding = 'utf-8')
{
    $strlen = mb_strlen($string, $encoding);
    $first_char = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);

    return mb_strtoupper($first_char, $encoding) . $then;
}

/**
 * UTF-8 aware parse_url() replacement
 *
 * @param $url
 * @return mixed
 */
function mb_parse_url($url)
{
    $enc_url = preg_replace_callback('%[^:/@?&=#]+%usD', function ($matches) {
        return urlencode($matches[0]);
    }, $url);

    $parts = parse_url($enc_url);

    if ($parts === false) {
        throw new \InvalidArgumentException('Malformed URL: ' . $url);
    }

    foreach ($parts as $name => $value) {
        $parts[$name] = urldecode($value);
    }

    return $parts;
}

/**
 * Friendly UTF-8 URL for all languages
 *
 * @param $string
 * @param string $separator
 * @return mixed|string
 */
function slugify($string, $separator = '-')
{
    // Remove accents
    $string = remove_accents($string);

    // Slug
    $string = mb_strtolower($string);
    $string = @trim($string);
    $replace = "/(\\s|\\" . $separator . ")+/mu";
    $subst = $separator;
    $string = preg_replace($replace, $subst, $string);

    // Remove unwanted punctuation, convert some to '-'
    $punc_table = array(
        // remove
        "'" => '',
        '"' => '',
        '`' => '',
        '=' => '',
        '+' => '',
        '*' => '',
        '&' => '',
        '^' => '',
        '' => '',
        '%' => '',
        '$' => '',
        '#' => '',
        '@' => '',
        '!' => '',
        '<' => '',
        '>' => '',
        '?' => '',
        // convert to minus
        '[' => '-',
        ']' => '-',
        '{' => '-',
        '}' => '-',
        '(' => '-',
        ')' => '-',
        ' ' => '-',
        ',' => '-',
        ';' => '-',
        ':' => '-',
        '/' => '-',
        '|' => '-',
        '\\' => '-',
    );
    $string = str_replace(array_keys($punc_table), array_values($punc_table), $string);

    // Clean up multiple '-' characters
    $string = preg_replace('/-{2,}/', '-', $string);

    // Remove trailing '-' character if string not just '-'
    if ($string != '-') {
        $string = rtrim($string, '-');
    }

    //$string = rawurlencode($string);

    return $string;
}

/**
 * @return mixed|string
 */
function get_locale()
{
    $lang = get_lang();
    $locale = (isset($lang) and !$lang->isEmpty()) ? $lang->get('locale') : 'en_US';

    return $locale;
}

/**
 * @return \Illuminate\Support\Collection|static
 */
function get_lang()
{
    $obj = new App\Helpers\Localization\Language();
    $lang = $obj->find();

    return $lang;
}

/**
 * Get file/folder permissions.
 *
 * @param $path
 * @return string
 */
function getPerms($path)
{
	return substr(sprintf('%o', fileperms($path)), -4);
}

/**
 * Get all countries from PHP array (umpirsky)
 *
 * @return array|null
 */
function getCountries()
{
    $countries = new App\Helpers\Localization\Helpers\Country();
	$countries = $countries->all();

	if (empty($countries)) return null;

	$arr = [];
	foreach ($countries as $code => $value) {
	    if (!file_exists(storage_path('database/geonames/countries/'.strtolower($code).'.sql'))) {
	        continue;
        }
		$row = ['value' => $code, 'text' => $value];
		$arr[] = $row;
	}

	return $arr;
}

/**
 * Pluralization
 *
 * @param $number
 * @return int
 */
function getPlural($number)
{
    if (config('lang.russian_pluralization')) {
        // Russian pluralization rules
        $typeOfPlural = (($number % 10 == 1) && ($number % 100 != 11))
            ? 0
            : ((($number % 10 >= 2)
                && ($number % 10 <= 4)
                && (($number % 100 < 10)
                    || ($number % 100 >= 20)))
                ? 1
                : 2
            );
    } else {
        // No rule for other languages
        $typeOfPlural = $number;
    }

    return $typeOfPlural;
}

/**
 * Get URL of Page by page's type
 * @param $type
 * @param null $locale
 * @return mixed|string
 */
function getUrlPageByType($type, $locale = null)
{
    if (is_null($locale)) {
        $locale = config('app.locale');
    }

    $cacheExpiration = (int)config('settings.app_cache_expiration', 60);
    $cacheId = 'page.' . $locale . '.type.' . $type;
    $page = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($type, $locale) {
        $page = \App\Models\Page::type($type)->transIn($locale)->first();
        return $page;
    });

    if (!empty($page)) {
        $url = lurl(trans('routes.v-page', ['slug' => $page->slug]));
    } else {
        $url = '#';
    }

    return $url;
}

/**
 * @param string $uploadType
 * @param bool $jsFormat
 * @return array|mixed|string
 */
function getUploadFileTypes($uploadType = 'file', $jsFormat = false)
{
    if ($uploadType == 'image') {
        $types = config('settings.upload_image_types', 'jpg,jpeg,gif,png');
    } else {
        $types = config('settings.upload_file_types', 'pdf,doc,docx,word,rtf,rtx,ppt,pptx,odt,odp,wps,jpeg,jpg,bmp,png');
    }

    $separators = ['|', '-', ';', '.', '/', '_', ' '];
    $types = str_replace($separators, ',', $types);

    if ($jsFormat) {
        $types = explode(',', $types);
        $types = array_filter($types, function($value) {
            return $value !== '';
        });
        $types = json_encode($types);
    }

    return $types;
}

/**
 * @param string $uploadType
 * @return array|mixed|string
 */
function showValidFileTypes($uploadType = 'file')
{
    $formats = getUploadFileTypes($uploadType);
    $formats = str_replace(',', ', ', $formats);

    return $formats;
}

/**
 * @param $string
 * @return bool
 */
function isValidJson($string)
{
    try {
        json_decode($string);
    } catch (\Exception $e) {
        return false;
    }

    return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Check if exec() function is available
 *
 * @return boolean
 */
function exec_enabled()
{
    try {
        // make a small test
        exec("ls");
        return function_exists('exec') && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))));
    } catch (\Exception $ex) {
        return false;
    }
}

/**
 * Run artisan config cache
 *
 * @return mixed
 */
function artisanConfigCache()
{
	// Artisan config:cache generate the following two files
	// Since config:cache runs in the background
	// to determine if it is done, we just check if the files modified time have been changed
	$files = ['bootstrap/cache/config.php', 'bootstrap/cache/services.php'];

	// get the last modified time of the files
	$last = 0;
	foreach ($files as $file) {
		$path = base_path($file);
		if (file_exists($path)) {
			if (filemtime($path) > $last) {
				$last = filemtime($path);
			}
		}
	}

	// Prepare to run
	$timeout = 5;
	$start = time();

	// Actually call the Artisan command
	$exitCode = \Artisan::call('config:cache');

	// Check if Artisan call is done
	while (true) {
		// Just finish if timeout
		if (time() - $start >= $timeout) {
			echo "Timeout\n";
			break;
		}

		// If any file is still missing, keep waiting
		// If any file is not updated, keep waiting
		// @todo: services.php file keeps unchanged after artisan config:cache
		foreach($files as $file) {
			$path = base_path($file);
			if (!file_exists($path)) {
				sleep(1);
				continue;
			} else {
				if (filemtime($path) == $last) {
					sleep(1);
					continue;
				}
			}
		}

		// Just wait another extra 3 seconds before finishing
		sleep(3);
		break;
	}

	return $exitCode;
}

/**
 * @param $pathFromDb
 * @param string $type
 * @return mixed
 */
function resize($pathFromDb, $type = 'big')
{
    // Check default picture
    if (str_contains($pathFromDb, config('larapen.core.picture.default'))) {
        return \Storage::url($pathFromDb) . getPictureVersion();
    }

    // Get size dimensions
    $size = config('larapen.core.picture.resize.' . $type, '816x460');

    $filename = last(explode('/', $pathFromDb));
    $filepath = str_replace($filename, '', $pathFromDb);

    // Thumb file name
    $thumbFilename = 'thumb-' . $size . '-' . $filename;

    // Check if thumb image exists
    if (\Storage::exists($filepath . $thumbFilename)) {
        return \Storage::url($filepath . $thumbFilename) . getPictureVersion();
    } else {
        // Create thumb image if it not exists
        try {
            // Get file extention
            $extension = (is_png(\Storage::get($pathFromDb))) ? 'png' : 'jpg';

            // Sizes
            list($width, $height) = explode('x', $size);

            // Make the image
            if (in_array($type, ['logo'])) {
                // Resize logo pictures
                $image = \Image::make(\Storage::get($pathFromDb))->resize($width, $height, function ($constraint) {
                    $constraint->upsize();
                })->encode($extension, config('larapen.core.picture.quality', 100));
            } else if (in_array($type, ['big'])) {
                // Resize big pictures
                $image = \Image::make(\Storage::get($pathFromDb))->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($extension, config('larapen.core.picture.quality', 100));
            } else {
                // Fit small pictures
                $image = \Image::make(\Storage::get($pathFromDb))->fit($width, $height)->encode($extension, config('larapen.core.picture.quality', 100));
            }
        } catch (\Exception $e) {
            return \Storage::url($pathFromDb) . getPictureVersion();
        }

        // Store the image on disk.
        \Storage::put($filepath . '/' . $thumbFilename, $image->stream());

        // Get the image URL
        return \Storage::url($filepath . $thumbFilename) . getPictureVersion();
    }
}

/**
 * Get pictures version
 * @return string
 */
function getPictureVersion()
{
    $pictureVersion = config('larapen.core.picture.versioned') ? '?v=' . config('larapen.core.picture.version') : '';
    return $pictureVersion;
}

/**
 * @param $pathFromDb
 * @return string
 */
function filePath($pathFromDb)
{
	$path = \Storage::getDriver()->getAdapter()->getPathPrefix();

	return $path . $pathFromDb;
}

/**
 * Get image extension from base64 string
 *
 * @param $bufferImg
 * @param bool $recursive
 * @return bool
 */
function is_png($bufferImg, $recursive = true)
{
    $f = finfo_open();
    $result = finfo_buffer($f, $bufferImg, FILEINFO_MIME_TYPE);

    if (!str_contains($result, 'image') && $recursive) {
        // Plain Text
        return str_contains($bufferImg, 'image/png');
    }

    return $result == 'image/png';
}

/**
 * Get the login field
 *
 * @param $value
 * @return string
 */
function getLoginField($value)
{
	$field = 'username';
	if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
		$field = 'email';
	} else if (preg_match('/^((\+|00)\d{1,3})?[\s\d]+$/', $value)) {
		$field = 'phone';
	}

	return $field;
}

/**
 * Get Phone's National Format
 *
 * @param $phone
 * @param null $countryCode
 * @param int $format
 * @return \libphonenumber\PhoneNumberUtil|mixed|string
 */
function phoneFormat($phone, $countryCode = null, $format = \libphonenumber\PhoneNumberFormat::NATIONAL)
{
	// Set the phone format
	try {
		$phone = phone($phone, $countryCode, $format);
	} catch (\Exception $e) {
		// Keep the default value
	}

	// Keep only numeric characters
	$phone = preg_replace('/[^0-9]/', '', $phone);

	return $phone;
}

/**
 * Get Phone's International Format
 *
 * @param $phone
 * @param null $countryCode
 * @param int $format
 * @return \libphonenumber\PhoneNumberUtil|mixed|string
 */
function phoneFormatInt($phone, $countryCode = null, $format = \libphonenumber\PhoneNumberFormat::INTERNATIONAL)
{
	return phoneFormat($phone, $countryCode, $format);
}

/**
 * @param $countryCode
 * @return string
 */
function getPhoneIcon($countryCode)
{
	if (file_exists(public_path() . '/images/flags/16/'.strtolower($countryCode).'.png')) {
		$phoneIcon = '<img src="' . url('images/flags/16/'.strtolower($countryCode).'.png') . getPictureVersion() . '" style="padding: 2px;">';
	} else {
		$phoneIcon = '<i class="icon-phone-1"></i>';
	}

	return $phoneIcon;
}

/**
 * @param $field
 * @return bool
 */
function isEnabledField($field)
{
    // Front Register Form
    if ($field == 'phone') {
        return !config('larapen.core.disable.phone');
    } else if ($field == 'email') {
        return !config('larapen.core.disable.email') or
            (config('larapen.core.disable.email') and config('larapen.core.disable.phone'));
    } else if ($field == 'username') {
        return !config('larapen.core.disable.username');
    } else {
        return true;
    }
}

function getLoginLabel()
{
    if (isEnabledField('email') && isEnabledField('phone')) {
        $loginLabel = t('Email or Phone');
    } else {
        if (isEnabledField('phone')) {
            $loginLabel = t('Phone');
        } else {
            $loginLabel = t('Email');
        }
    }

    return $loginLabel;
}

function getTokenLabel()
{
    if (isEnabledField('email') && isEnabledField('phone')) {
        $loginLabel = t('Code received by SMS or Email');
    } else {
        if (isEnabledField('phone')) {
            $loginLabel = t('Code received by SMS');
        } else {
            $loginLabel = t('Code received by Email');
        }
    }

    return $loginLabel;
}

function getTokenMessage()
{
    if (isEnabledField('email') && isEnabledField('phone')) {
        $loginLabel = t('Enter the code you received by SMS or Email in the field below');
    } else {
        if (isEnabledField('phone')) {
            $loginLabel = t('Enter the code you received by SMS in the field below');
        } else {
            $loginLabel = t('Enter the code you received by Email in the field below');
        }
    }

    return $loginLabel;
}

/**
 * @param $tag
 * @param $page
 * @return null|string
 */
function getMetaTag($tag, $page)
{
    // Get the current Language
    $languageCode = config('lang.abbr');

    // Get the Page's MetaTag
    try {
        $cacheExpiration = (int)config('settings.app_cache_expiration', 60);
        $cacheId = 'metaTag.' . $languageCode . '.' . $page;
        $metaTag = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($languageCode, $page) {
            $metaTag = \App\Models\MetaTag::transIn($languageCode)->where('page', $page)->first();
            return $metaTag;
        });
    } catch (\Exception $e) {
        return null;
    }

    if (!empty($metaTag)) {
        if (isset($metaTag->$tag)) {
            $tagValue = $metaTag->$tag;
            $tagValue = str_replace(['{app_name}', '{country}'], [config('settings.app_name'), config('country.name')], $tagValue);
            return $tagValue;
        }
    }

    if (in_array($tag, ['title', 'description'])) {
        return config('settings.app_name') . ' - ' . config('settings.app_slogan');
    }

    return null;
}

/**
 * Redirect (Prevent Browser cache)
 *
 * @param $url
 * @param int $code
 */
function headerLocation($url, $code = 301)
{
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Location: " . $url, true, $code);
    exit();
}

/**
 * @return int|string
 */
function vTime()
{
    $timeStamp = time();
    if (\App::environment(['staging', 'production'])) {
        $timeStamp = '';
    }

    return $timeStamp;
}

/**
 * Split a name into the first name and last name
 *
 * @param $input
 * @return array
 */
function splitName($input)
{
    $output = ['firstName' => '', 'lastName' => ''];
    $space = mb_strpos($input, ' ');
    if ($space !== false) {
        $output['firstName'] = mb_substr($input, 0, $space);
        $output['lastName'] = mb_substr($input, $space, strlen($input));
    } else {
        $output['lastName'] = $input;
    }

    return $output;
}

/**
 * Zero leading for numeric values
 *
 * @param $number
 * @param int $padLength
 * @return string
 */
function zeroLead($number, $padLength = 2)
{
    if (is_numeric($number)) {
        $number = str_pad($number, $padLength, '0', STR_PAD_LEFT);
    }

    return $number;
}

/**
 * @param $number
 * @param null $countryCode
 * @return int|string
 */
function lengthPrecision($number, $countryCode = null)
{
    if (empty($countryCode)) {
        $countryCode = config('country.code');
    }

    // Get mile use countries
    $mileUseCountries = (array)config('larapen.core.mile_use_countries');

    if (is_numeric($number)) {
        // Anglo-Saxon units of length
        if (in_array($countryCode, $mileUseCountries)) {
            // Convert Km to Miles
            $number = $number * 0.621;
        }
    }

    return $number;
}

/**
 * @param null $countryCode
 * @return string
 */
function unitOfLength($countryCode = null)
{
    if (empty($countryCode)) {
        $countryCode = config('country.code');
    }

    // Get mile use countries
    $mileUseCountries = (array)config('larapen.core.mile_use_countries');

    $unit = t('km');
    if (in_array($countryCode, $mileUseCountries)) {
        $unit = t('mi');
    }

    return $unit;
}

/**
 * Get the installed version value
 *
 * @return string
 */
function getInstalledVersion()
{
	$installedVersion = null;
	$envFilePath = base_path('.env');
	if (\Illuminate\Support\Facades\File::exists($envFilePath)) {
		$configString = \Illuminate\Support\Facades\File::get($envFilePath);
		$tmp = [];
		preg_match('/APP_VERSION=(.*)[^\n]*/', $configString, $tmp);
		if (isset($tmp[1]) && trim($tmp[1]) != '') {
			$installedVersion = trim($tmp[1]);
		}
	}

	// Forget the subversion number
	if (!empty($installedVersion)) {
		$tmp = explode('.', $installedVersion);
		if (count($tmp) > 1) {
			if (count($tmp) >= 3) {
				$tmp = array_only($tmp, [0, 1]);
			}
			$installedVersion = implode('.', $tmp);
		}
	}

	return $installedVersion;
}

/**
 * @param $value
 * @return mixed
 */
function strToInt($value)
{
	$value = preg_replace('/[^0-9]/', '', $value);
	$value = (int)$value;

	return $value;
}
