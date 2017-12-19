<?php

namespace App\Helpers\Localization;

use App\Helpers\Ip;
use App\Models\City;
use App\Models\Post;
use App\Models\Country as CountryModel;
use App\Models\Currency;
use App\Models\Language as LanguageModel;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\TimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use PulkitJalan\GeoIP\Facades\GeoIP;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Country
{
    public $defaultCountry = '';
    public $defaultUrl = '/';
    public $defaultPage = '/';

    public $user;
    public $countries;
    public $country;
    public $ipCountry;
    public $siteCountryInfo = '';

    public static $cacheExpiration = 60;
    public static $cookieExpiration = 60;

    // Maxmind Support URL
    private static $maxmindSupportUrl = 'https://www.maxmind.com';


    public function __construct()
    {
        $this->app = app();

        $this->configRepository = $this->app['config'];
        $this->view = $this->app['view'];
        $this->translator = $this->app['translator'];
        $this->router = $this->app['router'];
        $this->request = $this->app['request'];
        $this->language = new Language();

        // Default values
        $this->defaultCountryCode = config('settings.app_default_country');
        $this->defaultUrl = url(config('larapen.localization.default_uri'));
        $this->defaultPage = url(config('app.locale') . '/' . trans('routes.' . config('larapen.localization.countries_list_uri')));

        // Cache and Cookies Expires
        self::$cacheExpiration = config('settings.app_cache_expiration', self::$cacheExpiration);
        self::$cookieExpiration = config('settings.app_cookie_expiration');

        // Check if User is logged
        $this->user = $this->checkUser();

        // Init. Country Infos
        $this->country = collect([]);
        $this->ipCountry = collect([]);
    }

    /**
     * @return bool|mixed|\stdClass
     */
    public function find()
    {
        // Get user IP country
        $this->ipCountry = $this->getCountryFromIP();

        // Get current country
        $this->country = $this->getCountryFromQueryString();
        if ($this->country->isEmpty()) {
            $this->country = $this->getCountryFromPost();
            if ($this->country->isEmpty()) {
                $this->country = $this->getCountryFromURIPath();
                if ($this->country->isEmpty()) {
                    $this->country = $this->getCountryFromCity();
                    if ($this->country->isEmpty()) {
                        $this->country = $this->getCountryForBots();
                    }
                }
            }
        }

        if ($this->request->session()->has('country_code') && $this->country->isEmpty()) {
            $this->country = self::getCountryInfo(session('country_code'));
        } else {
            if ($this->country->isEmpty()) {
                $this->country = $this->getDefaultCountry($this->defaultCountryCode);
            }
            if ($this->country->isEmpty()) {
                if (!$this->ipCountry->isEmpty() && $this->ipCountry->has('code')) {
                    $this->country = $this->ipCountry;
                }
            }
        }

        return $this->country;
    }

    /**
     * @return bool
     */
    public function setCountryParameters()
    {
        // SKIP...
        // - Countries selection page
        // - All XML sitemap pages
        // - Robots.txt
        if (
            in_array(getSegment(1), [
                trans('routes.' . config('larapen.localization.countries_list_uri')),
                'robots',
                'robots.txt',
            ]) ||
            ends_with($this->request->url(), '.xml')
        )
        {
            return false;
        }


        // REDIRECT... If country not found
        if (!$this->isAvailableCountry($this->country->get('code'))) {
            // Redirect to country selection page
            headerLocation($this->defaultPage);
            exit();
        }

        // SHOW MESSAGE... (About Login) If user not logged
        if (!Auth::check() && !in_array(getSegment(1), [
                trans('routes.login'),
                trans('routes.register'),
                'register',
                'posts',
                trans('routes.t-page'),
                trans('routes.contact'),
                trans('routes.sitemap'),
                'verify',
            ]) &&
            !Input::filled('iam') &&
            getSegment(1) !== null &&
            !str_contains(Route::currentRouteAction(), 'Search\\') &&
            !str_contains(Route::currentRouteAction(), 'SitemapController') &&
            !str_contains(Route::currentRouteAction(), 'PasswordController')
        ) {
            $msg = 'Login for faster access to the best deals. Click here if you don\'t have an account.';
            $this->siteCountryInfo = t($msg, [
                'login_url'    => lurl(trans('routes.login')),
                'register_url' => lurl(trans('routes.register')),
            ], 'global', config('app.locale'));
        }

        // SHOW MESSAGE... (About Location)
        // - If we know the user IP country
        // - and if user visiting another country's website
        // - and if Geolocation is activated
        if (config('settings.activation_geolocation')) {
            if (!$this->ipCountry->isEmpty() && !$this->country->isEmpty()) {
                if ($this->ipCountry->get('code') != $this->country->get('code')) {
                    $url = url(self::getLangFromCountry($this->ipCountry->get('languages'))->get('code') . '/?d=' . $this->ipCountry->get('code'));
                    $msg = ':app_name is also available in your country: :country. Starting good opportunities here now!';
                    $this->siteCountryInfo = t($msg, ['app_name' => config('settings.app_name'), 'country' => $this->ipCountry->get('name'), 'url' => $url]);
                }
            }
        }

        // Share vars to views
        if (isset($this->siteCountryInfo) && $this->siteCountryInfo != '') {
            view()->share('siteCountryInfo', $this->siteCountryInfo);
        }

        return true;
    }

    public function getDefaultCountry($defaultCountryCode)
    {
        // Check default country
        if (trim($defaultCountryCode) != '') {
            if ($this->isAvailableCountry($defaultCountryCode)) {
                return self::getCountryInfo($defaultCountryCode);
            }
        }

        return collect([]);
    }

    /**
     * Get Country from logged User
     * @return bool|\stdClass
     */
    public function getCountryFromUser()
    {
        if (Auth::check()) {
            if (isset($this->user) && isset($this->user->country_code)) {
                if ($this->isAvailableCountry($this->user->country_code)) {
                    return self::getCountryInfo($this->user->country_code);
                }
            }
        }

        return collect([]);
    }

    /**
     * Get Country from logged User
     * @return bool|\stdClass
     */
    public function getCountryFromPost()
    {
        // Get ad ID from URL segment
        $postId = getPostIdFromURLSegment();

        // Return empty collection if ad ID not found
        if (is_null($postId)) {
            return collect([]);
        }

        // GET ADS INFO
        $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $postId)->first();
        if (empty($post)) {
            return collect([]);
        }

        $countryCode = $post->country_code;

        if ($this->isAvailableCountry($countryCode)) {
            return self::getCountryInfo($countryCode);
        }

        return collect([]);
    }

    /**
     * Get Country from Domain
     * @return bool|\stdClass
     */
    public function getCountryFromDomain()
    {
        $countryCode = getSubDomainName();
        if ($this->isAvailableCountry($countryCode)) {
            return self::getCountryInfo($countryCode);
        }

        return collect([]);
    }

    /**
     * Get Country from Query String
     * @return bool|\stdClass
     */
    public function getCountryFromQueryString()
    {
        $countryCode = '';
        if (Input::filled('site')) {
            $countryCode = Input::get('site');
        }
        if (Input::filled('d')) {
            $countryCode = Input::get('d');
        }

        if ($this->isAvailableCountry($countryCode)) {
            return self::getCountryInfo($countryCode);
        }

        return collect([]);
    }

    /**
     * Get Country from Query String
     * @return bool|\stdClass
     */
    public function getCountryFromURIPath()
    {
        $countryCode = getSegment(2);
        if ($this->isAvailableCountry($countryCode)) {
            return self::getCountryInfo($countryCode);
        }

        return collect([]);
    }

    /**
     * Get Country from City
     * @return bool|\Illuminate\Support\Collection|\stdClass
     */
    public function getCountryFromCity()
    {
        $countryCode = null;
        $cityId = null;

        if (Input::filled('l')) {
            $cityId = Input::get('l');
        }
        if (empty($cityId)) {
            $tmpValue = getSegment(3);
            if (is_numeric($tmpValue)) {
                $cityId = $tmpValue;
            }
        }

        if (!empty($cityId)) {
            $city = City::find($cityId);
            if (!empty($city)) {
                $countryCode = $city->country_code;
                if ($this->isAvailableCountry($countryCode)) {
                    return self::getCountryInfo($countryCode);
                }
            }
        }

        return collect([]);
    }

    /**
     * Get Country for Bots if not found
     * @return bool|\stdClass
     */
    public function getCountryForBots()
    {
        $crawler = new CrawlerDetect();
        if ($crawler->isCrawler()) {
            // Don't set the default country for homepage
            if (!str_contains(Route::currentRouteAction(), 'HomeController')) {
                $countryCode = config('settings.app_default_country');
                if ($this->isAvailableCountry($countryCode)) {
                    return self::getCountryInfo($countryCode);
                }
            }
        }

        return collect([]);
    }


    /**
     * @return bool|mixed|\stdClass
     */
    public function getCountryFromIP()
    {
        $country = self::getCountryFromCookie();
        if (!$country->isEmpty()) {
            if ($country->get('level') == 'user') { // @todo: Check if user has logged
                $country = self::getCountryInfo($country->get('code'));
            }

            return $country;
        } else {
            // GeoIP
            $countryCode = $this->getCountryCodeFromIP();
            if (!$countryCode || trim($countryCode) == '') {
                // Geolocalization has failed
                return collect([]);
            }

            return self::setCountryToCookie($countryCode);
        }
    }

    /**
     * @param $countryCode
     * @return bool|\Illuminate\Support\Collection|\stdClass
     */
    public static function setCountryToCookie($countryCode)
    {
        if (trim($countryCode) == '') {
            return collect([]);
        }

        if (isset($_COOKIE['ip_country_code'])) {
            unset($_COOKIE['ip_country_code']);
        }

        setcookie('ip_country_code', $countryCode, self::$cookieExpiration, '/', getDomain());

        return self::getCountryInfo($countryCode);
    }

    /**
     * @return bool|mixed
     */
    public static function getCountryFromCookie()
    {
        if (isset($_COOKIE['ip_country_code'])) {
            $countryCode = $_COOKIE['ip_country_code'];
            if (trim($countryCode) == '') {
                return collect([]);
            } // TMP
            return self::getCountryInfo($countryCode);
        } else {
            return collect([]);
        }
    }

    /**
     * @return bool|string
     */
    public function getCountryCodeFromIP()
    {
        // Localize the user's country
        try {
            $ipAddr = Ip::get();


            GeoIP::setIp($ipAddr);
            $countryCode = GeoIP::getCountryCode();


            if (!is_string($countryCode) || strlen($countryCode) != 2) {
                return false;
            }
        } catch (\Exception $e) {
            if (config('settings.activation_geolocation')) {
                if (Auth::check()) {
                    if (Auth::user()->is_admin == 1) {
                        // Get settings
                        $setting = Setting::where('key', 'activation_geolocation')->first();

                        // Notice message for admin users
                        $msg = "";
                        $msg .= "<h4><strong>Only Admin Users can see this message</strong></h4>";
                        $msg .= "<strong>Maxmind GeoLite2 City</strong> not found at: ";
                        $msg .= "<code>" . storage_path('database/maxmind/') . "</code><br>";
                        $msg .= "Please check the <a href='" . self::$maxmindSupportUrl . "' target='_blank'>Maxmind database installation</a> documentation.";
                        $msg .= "<br><br><a href='" . url(config('larapen.admin.route_prefix', 'admin') . "/setting/" . $setting->id . "/edit") . "' class='btn btn-xs btn-thin btn-default-lite' id='disableGeoOption'>Disable the Geolocalization</a>";
                        flash($msg)->warning();
                    }
                }
            }

            return false;
        }

        return strtolower($countryCode);
    }

    /**
     * @param $countryCode
     * @return \Illuminate\Support\Collection
     */
    public static function getCountryInfo($countryCode)
    {
        if (trim($countryCode) == '') {
            return collect([]);
        }
        $countryCode = strtoupper($countryCode);

        // Get the Country details
        $country = Cache::remember('country.' . $countryCode, self::$cacheExpiration, function () use ($countryCode) {
            $country = CountryModel::find($countryCode);
            return $country;
        });
        if (empty($country)) {
            return collect([]);
        }
        $country = $country->toArray();

        // Get the Country's Currency
        $currency = Cache::remember('currency.' . $country['currency_code'], self::$cacheExpiration, function () use($country) {
            $currency = Currency::find($country['currency_code']);
            return $currency;
        });

        // Get the Country's Language
        $lang = self::getLangFromCountry($country['languages']);

        // Get the Country's TimeZone
        $timeZone = Cache::remember('timeZone.where.countryCode' . $countryCode, self::$cacheExpiration, function () use($countryCode) {
            $timeZone = TimeZone::where('country_code', $countryCode)->first();
            return $timeZone;
        });

        $country['currency'] = (!empty($currency)) ? $currency : [];
        $country['lang'] = ($lang) ? $lang : [];
        $country['timezone'] = (!empty($timeZone)) ? $timeZone : [];
        $country = collect($country);

        return $country;
    }

    /**
     * Only used for search bots
     * @param $languages
     * @return mixed
     */
    public static function getLangFromCountry($languages)
    {
        // Get language code
        $langCode = $hrefLang = '';
        if (trim($languages) != '') {
            // Get the country's languages codes
            $countryLanguageCodes = explode(',', $languages);

            // Get all languages
            $availableLanguages = Cache::remember('languages.all', self::$cacheExpiration, function () {
                $availableLanguages = LanguageModel::all();
                return $availableLanguages;
            });

            if ($availableLanguages->count() > 0) {
                $found = false;
                foreach ($countryLanguageCodes as $isoLang) {
                    foreach ($availableLanguages as $language) {
                        if (starts_with(strtolower($isoLang), strtolower($language->abbr))) {
                            $langCode = $language->abbr;
                            $hrefLang = $isoLang;
                            $found = true;
                            break;
                        }
                    }
                    if ($found) {
                        break;
                    }
                }
            }
        }

        // Get language info
        if ($langCode != '') {
            $isAvailableLang = Cache::remember('language.' . $langCode, self::$cacheExpiration, function () use($langCode) {
                $isAvailableLang = LanguageModel::where('abbr', $langCode)->first();
                return $isAvailableLang;
            });

            if (!empty($isAvailableLang)) {
                $lang = collect($isAvailableLang)->merge(collect(['hreflang' => $hrefLang]));
            } else {
                $lang = self::getLangFromConfig();
            }
        } else {
            $lang = self::getLangFromConfig();
        }

        return $lang;
    }

    /**
     * @return mixed
     */
    public static function getLangFromConfig()
    {
        $langCode = config('applang.abbr');

        // Default language (from Admin panel OR Config)
        $lang = Cache::remember('language.' . $langCode, self::$cacheExpiration, function () use($langCode) {
            $lang = LanguageModel::where('abbr', $langCode)->first();
            return $lang;
        });

        $lang = collect($lang)->merge(collect(['hreflang' => config('applang.abbr')]));

        return $lang;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getCountries()
    {
        // Get Countries from DB
        try {
            $countries = Cache::remember('countries.with.continent.currency', self::$cacheExpiration, function () {
                $countries = CountryModel::with('continent')->with('currency')->orderBy('asciiname')->get()->keyBy('code');
                return $countries;
            });
        } catch (\Exception $e) {
            // return collect([]);
            return collect(['US' => []]); // To prevent HTTP 500 Error when site is not installed.
        }

        // Country filters
        $tab = [];
        if ($countries->count() > 0) {
            foreach($countries as $code => $country) {
                // Get only Countries with currency
                if (isset($country->currency) && !empty($country->currency)) {
                    $tab[$code] = collect($country)->forget('currency_code');
                } else {
                    // Just for debug
                    // dd(collect($item));
                }

                // Get only allowed Countries with active Continent
                if (!isset($country->continent) || $country->continent->active != 1) {
                    unset($tab[$code]);
                }
            }
        }
        $countries = collect($tab);

        return $countries;
    }

    /**
     * @param $countryCode
     * @return bool
     */
    public function isAvailableCountry($countryCode)
    {
        if (!is_string($countryCode) || strlen($countryCode) != 2) {
            return false;
        }

        $countries = self::getCountries();
        $availableCountryCodes = is_array($countries) ? collect(array_keys($countries)) : $countries->keys();
        $availableCountryCodes = $availableCountryCodes->map(function ($item, $key) {
            return strtolower($item);
        })->flip();
        if ($availableCountryCodes->has(strtolower($countryCode))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if User is logged
     *
     * @return bool
     */
    public function checkUser()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
            view()->share('user', $this->user);
            $this->userLevel = 'user';

            return $this->user;
        }

        return false;
    }
}
