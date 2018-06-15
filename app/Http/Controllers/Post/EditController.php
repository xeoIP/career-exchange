<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Post\Traits\EditTrait;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\PostRequest;
use App\Models\PostType;
use App\Models\Category;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\SalaryType;
use App\Http\Controllers\FrontController;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

class EditController extends FrontController
{
    use EditTrait, VerificationTrait;

    public $data;
    public $msg = [];
    public $uri = [];

    /**
     * EditController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // From Laravel 5.3.4 or above
        $this->middleware(function ($request, $next) {
            $this->commonQueries();
            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // References
        $data = [];
    
        // Get Countries
        $data['countries'] = $this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        $this->countries = $data['countries'];
        view()->share('countries', $data['countries']);
    
        // Get Categories
        $data['categories'] = Category::trans()->where('parent_id', 0)->with([
            'children' => function ($query) {
                $query->trans();
            },
        ])->orderBy('lft')->get();
        view()->share('categories', $data['categories']);
    
        // Get Post Types
        $data['postTypes'] = PostType::trans()->get();
        view()->share('postTypes', $data['postTypes']);
    
        // Get Salary Types
        $data['salaryTypes'] = SalaryType::trans()->get();
        view()->share('salaryTypes', $data['salaryTypes']);
    
        // Count Packages
        $data['countPackages'] = Package::trans()->count();
        view()->share('countPackages', $data['countPackages']);
    
        // Count Payment Methods
        $data['countPaymentMethods'] = PaymentMethod::where(function ($query) {
            $query->whereRaw('FIND_IN_SET("' . $this->country->get('icode') . '", LOWER(countries)) > 0')
                ->orWhereNull('countries');
        })->count();
        view()->share('countPaymentMethods', $data['countPaymentMethods']);
    
        // Save common's data
        $this->data = $data;
    }
    
    /**
     * Show the form the create a new ad post.
     *
     * @param $postId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm($postId)
    {
        return $this->getUpdateForm($postId);
    }
    
    /**
     * Store a new ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForm($postId, PostRequest $request)
    {
        return $this->postUpdateForm($postId, $request);
    }
}
