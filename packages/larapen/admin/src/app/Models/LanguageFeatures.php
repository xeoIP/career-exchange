<?php

namespace Larapen\Admin\app\Models;

use Illuminate\Support\Facades\Cache;

trait LanguageFeatures
{
    public static function getActiveLanguagesArray()
    {
        $cacheExpiration = config('settings.app_cache_expiration', 60);
        $activeLanguages = Cache::remember('languages.active.array', $cacheExpiration, function () {
            $activeLanguages = self::where('active', 1)->get()->toArray();
            return $activeLanguages;
        });
        
        $localizableLanguagesArray = [];

        if (count($activeLanguages) > 0) {
            foreach ($activeLanguages as $key => $lang) {
                $localizableLanguagesArray[$lang['abbr']] = $lang;
            }

            return $localizableLanguagesArray;
        }

        return config('laravellocalization.supportedLocales');
    }

    public static function findByAbbr($abbr = false)
    {
        return self::where('abbr', $abbr)->first();
    }
}
