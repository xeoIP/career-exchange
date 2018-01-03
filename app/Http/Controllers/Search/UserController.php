<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search;
use App\Models\User;
use Torann\LaravelMetaTags\Facades\MetaTag;

class UserController extends BaseController
{
	public $isUserSearch = true;
	public $sUser;

    /**
     * @param $countryCode
     * @param null $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($countryCode, $userId = null)
    {
        // Check multi-countries site parameters
        if (!config('larapen.core.multi_countries_website')) {
            $userId = $countryCode;
        }

        view()->share('isUserSearch', $this->isUserSearch);

        // Get User
		$this->sUser = User::find($userId);
        if (empty($this->sUser)) {
            abort(404);
        }
	
		// Redirect to User's profile If username exists
		if (!empty($this->sUser->username)) {
			$url = lurl(trans('routes.v-search-username', ['countryCode' => $countryCode, 'username' => $this->sUser->username]));
			headerLocation($url);
		}
	
		return $this->searchByUserId($this->sUser->id);
    }
	
	/**
	 * @param $countryCode
	 * @param null $username
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function profile($countryCode, $username = null)
	{
		// Check multi-countries site parameters
		if (!config('larapen.core.multi_countries_website')) {
			$username = $countryCode;
		}
		
		view()->share('isUserSearch', $this->isUserSearch);
		
		// Get User
		$this->sUser = User::where('username', $username)->first();
		if (empty($this->sUser)) {
			abort(404);
		}
		
		return $this->searchByUserId($this->sUser->id);
	}
	
	/**
	 * @param $userId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	private function searchByUserId($userId)
	{
		// Search
		$search = new Search();
		$data = $search->setUser($userId)->setRequestFilters()->fetch();
		
		// Get Titles
		$bcTab = $this->getBreadcrumb();
		$htmlTitle = $this->getHtmlTitle();
		view()->share('bcTab', $bcTab);
		view()->share('htmlTitle', $htmlTitle);
		
		// Meta Tags
		$title = $this->getTitle();
		MetaTag::set('title', $title);
		MetaTag::set('description', $title);
		
		return view('search.serp', $data);
	}
}
