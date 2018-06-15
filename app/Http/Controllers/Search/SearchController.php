<?php

namespace App\Http\Controllers\Search;


use App\Helpers\Search;
use App\Http\Controllers\Search\Traits\PreSearchTrait;
use Illuminate\Support\Facades\Input;
use Torann\LaravelMetaTags\Facades\MetaTag;

class SearchController extends BaseController
{
    use PreSearchTrait;

	public $isIndexSearch = true;

    protected $cat = null;
    protected $subCat = null;
    protected $city = null;
    protected $admin = null;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        view()->share('isIndexSearch', $this->isIndexSearch);

        // Pre-Search
        if (Input::filled('c')) {
            if (Input::filled('sc')) {
                $this->getCategory(Input::get('c'), Input::get('sc'));
            } else {
                $this->getCategory(Input::get('c'));
            }
        }
        if (Input::filled('l') || Input::filled('location')) {
            $city = $this->getCity(Input::get('l'), Input::get('location'));
        }
        if (Input::filled('r') && !Input::filled('l')) {
            $admin = $this->getAdmin(Input::get('r'));
        }

        // Pre-Search values
        $preSearch = [
            'city'  => (isset($city) && !empty($city)) ? $city : null,
            'admin' => (isset($admin) && !empty($admin)) ? $admin : null,
        ];

        // Search
        $search = new Search($preSearch);
        $data = $search->fechAll();

        // Export Search Result
        view()->share('count', $data['count']);
        view()->share('posts', $data['posts']);

        // Get Titles
        $title = $this->getTitle();
        $this->getBreadcrumb();
        $this->getHtmlTitle();

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $title);

        return view('search.serp');
    }
}
