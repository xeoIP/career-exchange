<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Http\Request as HttpRequest;

class CountriesController extends FrontController
{
    /**
     * CountriesController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index()
    {
        $data = [];

        // Countries
        $countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());

        // Bootstrap grid view
        $cols = round($countries->count() / 4, 0, PHP_ROUND_HALF_EVEN);
        $cols = ($cols > 0) ? $cols : 1; // Fix array_chunk with 0
        $data['countryCols'] = $countries->chunk($cols)->all();

        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'countries'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'countries')));
        MetaTag::set('keywords', getMetaTag('keywords', 'countries'));

        return view('countries', $data);
    }
}
