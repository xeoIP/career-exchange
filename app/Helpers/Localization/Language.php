<?php

namespace App\Helpers\Localization;

use App\Helpers\Ip;
use App\Models\Language as LanguageModel;
use App\Models\Country as CountryModel;
use App\Helpers\Localization\Helpers\Country as CountryHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use PulkitJalan\GeoIP\Facades\GeoIP;

class Language
{
    protected $country;

    public static $cacheExpiration = 60;

    public function __construct()
    {
        $this->app = app();

        $this->configRepository = $this->app['config'];
        $this->view = $this->app['view'];
        $this->translator = $this->app['translator'];
        $this->router = $this->app['router'];
        $this->request = $this->app['request'];

        // Set Default Locale
        $this->defaultLocale = $this->configRepository->get('app.locale');

        // Cache Expiration Time
        self::$cacheExpiration = config('settings.app_cache_expiration', self::$cacheExpiration);
    }

    /**
     * Find language
     *
     * @return Collection|Language
     */
    public function find()
    {
        // Detect Language
        $lang = $this->fromUrl();

        return $lang;
    }

    /**
     * Get language from URL
     *
     * @return Language|Collection
     */
    public function fromUrl()
    {
        $langCode = $hrefLang = Request::segment(1);
        if ($langCode != '') {
            // Get the Language details
            $isAvailableLang = Cache::remember('language.' . $langCode, self::$cacheExpiration, function () use($langCode) {
                $isAvailableLang = LanguageModel::where('abbr', $langCode)->first();
                return $isAvailableLang;
            });

            $isAvailableLang = collect($isAvailableLang);

            if (!$isAvailableLang->isEmpty()) {
                $lang = $isAvailableLang->merge(collect(['hreflang' => $hrefLang]));
            } else {
                $lang = $this->fromBrowser();
            }
        } else {
            $lang = $this->fromBrowser();
        }

        return $lang;
    }

    /**
     * Get language from Browser
     *
     * @return Language|Collection
     */
    public function fromBrowser()
    {
        if (!config('larapen.core.detect_browser_language')) {
            return $this->fromConfig();
        }

        // Get browser language
        $acceptLanguage = Request::server('HTTP_ACCEPT_LANGUAGE');
        $acceptLanguageTab = explode(',', $acceptLanguage);
        $langTab = [];
        if (!empty($acceptLanguageTab)) {
            foreach ($acceptLanguageTab as $key => $value) {
                $tmp = explode(';', $value);
                if (empty($tmp)) continue;

                if (isset($tmp[0]) and isset($tmp[1])) {
                    $q = str_replace('q=', '', $tmp[1]);
                    $langTab[ $value ] = ['code' => $tmp[0], 'q' => (double) $q];
                } else {
                    $langTab[ $value ] = ['code' => $tmp[0], 'q' => 1];
                }
            }
        }

        // Get country info \w country language
        $country = self::getCountryFromIP();

        // Search the default language (Intersection Browser & Country language OR First Browser language)
        $langCode = $hrefLang = '';
        if (!empty($langTab)) {
            foreach ($langTab as $key => $value) {
                if (!$country->isEmpty() and $country->has('lang')) {
                    if (!$country->get('lang')->isEmpty() and $country->get('lang')->has('abbr')) {
                        if (str_contains($value['code'], $country->get('lang')->get('abbr'))) {
                            $langCode = substr($value['code'], 0, 2);
                            $hrefLang = $langCode;
                            break;
                        }
                    }
                } else {
                    if ($langCode == '') {
                        $langCode = substr($value['code'], 0, 2);
                        $hrefLang = $langCode;
                    }
                }
            }
        }

        // Check language
        if ($langCode != '') {
            // Get the Language details
            $isAvailableLang = Cache::remember('language.' . $langCode, self::$cacheExpiration, function () use($langCode) {
                $isAvailableLang = LanguageModel::where('abbr', $langCode)->first();
                return $isAvailableLang;
            });

            $isAvailableLang = collect($isAvailableLang);

            if (!$isAvailableLang->isEmpty()) {
                $lang = $isAvailableLang->merge(collect(['hreflang' => $hrefLang]));
            } else {
                $lang = $this->fromConfig();
            }
        } else {
            $lang = $this->fromConfig();
        }

        return $lang;
    }

    /**
     * Get language from Database or Config file
     *
     * @return mixed
     */
    public function fromConfig()
    {
        // Get the default Language (from DB)
        $langCode = config('applang.abbr');

        // Get the Language details
        try {
            // Get the Language details
            $lang = Cache::remember('language.' . $langCode, self::$cacheExpiration, function () use($langCode) {
                $lang = LanguageModel::where('abbr', $langCode)->first();
                return $lang;
            });
            $lang = collect($lang)->merge(collect(['hreflang' => config('applang.abbr')]));
        } catch (\Exception $e) {
            $lang = collect(['abbr' => config('app.locale'), 'hreflang' => config('app.locale')]);
        }

        // Check if language code exists
        if (!$lang->has('abbr')) {
            $lang = collect(['abbr' => config('app.locale'), 'hreflang' => config('app.locale')]);
        }

        return $lang;
    }

    /**
     * @return Collection
     */
    public static function supportedLanguage()
    {
        $languages = Cache::remember('languages.active', self::$cacheExpiration, function () {
            $languages = LanguageModel::where('active', 1)->get();
            return $languages;
        });

        return collect($languages);
    }

    /**
     * @param $countries
     * @param string $locale
     * @param string $source
     * @return Collection|static
     */
    public function countries($countries, $locale = 'en', $source = 'cldr')
    {
        // Security
        if (!$countries instanceof Collection) {
            return collect([]);
        }

        //$locale = 'en'; // debug
        $countryLang = new CountryHelper();
        $tab = [];
        foreach ($countries as $code => $country) {
            $tab[$code] = $country;
            if ($name = $countryLang->get($code, $locale, $source)) {
                $tab[$code]['name'] = $name;
            }
        }

        //return collect($tab);
        return collect($tab)->sortBy('name');
    }

    /**
     * @param $country
     * @param string $locale
     * @param string $source
     * @return Collection|static
     */
    public function country($country, $locale = 'en', $source = 'cldr')
    {
        // Security
        if (!$country instanceof Collection) {
            return collect([]);
        }

        //$locale = 'en'; // debug
        $countryLang = new CountryHelper();
        if ($name = $countryLang->get($country->get('code'), $locale, $source)) {
            return $country->merge(['name' => $name]);
        } else {
            return $country;
        }
    }

    /**
     * @param $countryCode
     * @return bool|\stdClass
     */
    public function getCountryInfo($countryCode)
    {
        if (trim($countryCode) == '') {
            return collect([]);
        }
        $countryCode = strtoupper($countryCode);

        $country = Cache::remember('country.' . $countryCode . '.array', self::$cacheExpiration, function () use ($countryCode) {
            $country = CountryModel::find($countryCode)->toArray();
            return $country;
        });

        if (count($country) == 0) {
            return collect([]);
        }

        $country = collect($country);

        return $country;
    }



    /**
     * @return bool|mixed|\stdClass
     */
    public static function getCountryFromIP()
    {
        $country = Country::getCountryFromCookie();
        if (!$country->isEmpty()) {
            return $country;
        } else {
            // GeoIP
            $countryCode = self::getCountryCodeFromIP();
            if (!$countryCode or trim($countryCode) == '') {
                // Geolocalization has failed
                return collect([]);
            }

            return Country::setCountryToCookie($countryCode);
        }
    }

    /**
     * @return bool|string
     */
    public static function getCountryCodeFromIP()
    {
        // Localize the user's country
        try {
            $ipAddr = Ip::get();

            GeoIP::setIp($ipAddr);
            $countryCode = GeoIP::getCountryCode();

            if (!is_string($countryCode) or strlen($countryCode) != 2) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return strtolower($countryCode);
    }
}
