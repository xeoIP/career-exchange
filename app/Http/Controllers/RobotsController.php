<?php

namespace App\Http\Controllers;

use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

class RobotsController extends FrontController
{
    public function index()
    {
        error_reporting(0);
        $robotsTxt = @file_get_contents('robots.txt');
        
        // Get countries list
        $countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
    
        // Generate a Sitemap Index by Country
        if (!$countries->isEmpty()) {
            foreach ($countries as $country) {
                $country = CountryLocalization::getCountryInfo($country->get('code'));
    
                if ($country->isEmpty()) {
                    continue;
                }
        
                // Get the Country's Language Code
                $countryLanguageCode = ($country->has('lang') && $country->get('lang')->has('abbr'))
                    ? $country->get('lang')->get('abbr')
                    : config('app.locale');
        
                // Create a Sitemap Index for this Country
                $robotsTxt .= "\n" . 'Sitemap: ' . url($countryLanguageCode . '/' . $country->get('icode') . '/sitemaps.xml');
            }
        }
        
        // Rending
        header("Content-Type:text/plain");
        echo $robotsTxt;
    }
}
