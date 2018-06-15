<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CompanyController extends BaseController
{
	public $isCompanySearch = true;

    /**
     * @param $countryCode
     * @param null $companyName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($countryCode, $companyName = null)
    {
        // Check multi-countries site parameters
        if (!config('larapen.core.multi_countries_website')) {
            $companyName = $countryCode;
        }

        view()->share('isCompanySearch', $this->isCompanySearch);

        // Get Company Name
        $this->companyName = rawurldecode($companyName);

        // Search
        $search = new Search();
        $data = $search->setCompany($companyName)->setRequestFilters()->fetch();
    
        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // Meta Tags
        $title = $this->getTitle();
        MetaTag::set('title', $title);
        MetaTag::set('description', $title);

        // Translation vars
        view()->share('uriPathCompanyName', $companyName);

        return view('search.serp', $data);
    }
}
