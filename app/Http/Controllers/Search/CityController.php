<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search;
use App\Models\City;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CityController extends BaseController
{
	public $isCitySearch = true;

    protected $city = null;

    /**
     * @param $countryCode
     * @param $cityName
     * @param null $cityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($countryCode, $cityName, $cityId = null)
    {
        // Check multi-countries site parameters
        if (!config('larapen.core.multi_countries_website')) {
            $cityId = $cityName;
            $cityName = $countryCode;
        }

        view()->share('isCitySearch', $this->isCitySearch);

        // Get the City
        $this->city = City::find((int)$cityId);
        if (empty($this->city)) {
            abort(404);
        }
        view()->share('city', $this->city);

        // Search
        $search = new Search();
        $data = $search->setLocationByCityCoordinates($this->city->latitude, $this->city->longitude)->setRequestFilters()->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);
        
        // Meta Tags
        $title = $this->getTitle();
        $description = t('Jobs in :location',
                ['location' => $this->city->name]) . ', ' . $this->country->get('name') . '. ' . t('Looking for a job') . ' - ' . $this->city->name . ', ' . $this->country->get('name');

        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Translation vars
        view()->share('uriPathCityName', $cityName);
        view()->share('uriPathCityId', $cityId);
        
        return view('search.serp', $data);
    }
}
