<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Arr;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Search\Traits\TitleTrait;
use App\Http\Requests\SendPostByEmailRequest;
use App\Models\SubAdmin1;
use App\Models\PostType;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\PostSentByEmail;
use App\Models\Post;

class BaseController extends FrontController
{
    use TitleTrait;
    
    public $request;
    public $countries;
    
    /**
     * All Types of Search
     * Variables declaration required
     */
    public $isIndexSearch = false;
    public $isCatSearch = false;
    public $isSubCatSearch = false;
    public $isCitySearch = false;
    public $isAdminSearch = false;
    public $isUserSearch = false;
    public $isCompanySearch = false;
    
    private $cats;
    
    /**
     * SearchController constructor.
     *
     * SearchController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        
        // From Laravel 5.3.4 or above
        $this->middleware(function ($request, $next) {
            $this->commonQueries();
            
            return $next($request);
        });
        
        $this->request = $request;
    }
    
    /**
     * Common Queries
     */
    public function commonQueries()
    {
        $countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        $this->countries = $countries;
        view()->share('countries', $countries);
        
        
        // Get all Categories
        $cacheId = 'categories.all.' . config('app.locale');
        $cats = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $cats = Category::trans()->orderBy('lft')->get();
            return $cats;
        });
        if ($cats->count() > 0) {
            $cats = collect($cats)->keyBy('tid');
        }
        view()->share('cats', $cats);
        $this->cats = $cats;
    
        
        // LEFT MENU VARS
        // Count Categories Posts
        $sql = 'SELECT sc.id, c.parent_id, count(*) as total' . '
            FROM ' . table('posts') . ' as a
            INNER JOIN ' . table('categories') . ' as sc ON sc.id=a.category_id AND sc.active=1
            INNER JOIN ' . table('categories') . ' as c ON c.id=sc.parent_id AND c.active=1
            WHERE a.country_code = :countryCode AND (a.verified_email=1 AND a.verified_phone=1) AND a.archived!=1 AND a.deleted_at IS NULL
            GROUP BY sc.id';
        $bindings = [
            'countryCode' => $this->country->get('code'),
        ];
        $countSubCatPosts = DB::select(DB::raw($sql), $bindings);
        $countSubCatPosts = collect($countSubCatPosts)->keyBy('id');
        view()->share('countSubCatPosts', $countSubCatPosts);
        
        // Count Parent Categories Posts
        $sql1 = 'SELECT c.id as id, count(*) as total' . '
            FROM ' . table('posts') . ' as a
            INNER JOIN ' . table('categories') . ' as c ON c.id=a.category_id AND c.active=1
            WHERE a.country_code = :countryCode AND (a.verified_email=1 AND a.verified_phone=1) AND a.archived!=1 AND a.deleted_at IS NULL
            GROUP BY c.id';
        $sql2 = 'SELECT c.id as id, count(*) as total' . '
            FROM ' . table('posts') . ' as a
            INNER JOIN ' . table('categories') . ' as sc ON sc.id=a.category_id AND sc.active=1
            INNER JOIN ' . table('categories') . ' as c ON c.id=sc.parent_id AND c.active=1
            WHERE a.country_code = :countryCode AND (a.verified_email=1 AND a.verified_phone=1) AND a.archived!=1 AND a.deleted_at IS NULL
            GROUP BY c.id';
        $sql = 'SELECT cat.id, SUM(total) as total' . '
            FROM ((' . $sql1 . ') UNION ALL (' . $sql2 . ')) cat
            GROUP BY cat.id';
        $bindings = [
            'countryCode' => $this->country->get('code'),
        ];
        $countCatPosts = DB::select(DB::raw($sql), $bindings);
        $countCatPosts = collect($countCatPosts)->keyBy('id');
        view()->share('countCatPosts', $countCatPosts);
        
        // Get the 100 most populate Cities
        $limit = 100;
        $cacheId = config('country.code') . '.cities.take.' . $limit;
        $cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($limit) {
            $cities = City::currentCountry()->take($limit)->orderBy('population', 'DESC')->orderBy('name')->get();
            return $cities;
        });
        view()->share('cities', $cities);
        
        
        // Get Post Types
        $cacheId = 'postTypes.all.' . config('app.locale');
        $postTypes = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $postTypes = PostType::trans()->orderBy('lft')->get();
            return $postTypes;
        });
        view()->share('postTypes', $postTypes);
        
        // Get Date Ranges
        $dates = Arr::toObject([
            '2'  => '24 ' . t('hours'),
            '4'  => '3 ' . t('days'),
            '8'  => '7 ' . t('days'),
            '31' => '30 ' . t('days'),
        ]);
        $this->dates = $dates;
        view()->share('dates', $dates);
        // END - LEFT MENU VARS
        
        
        // Get the Country first Administrative Division
        $cacheId = config('country.code') . '.subAdmin1s.all';
        $modalAdmins = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $modalAdmins = SubAdmin1::currentCountry()->orderBy('name')->get(['code', 'name'])->keyBy('code');
            return $modalAdmins;
        });
        view()->share('modalAdmins', $modalAdmins);
        
        
        // Check and load Reviews plugin
        $reviewsPlugin = load_installed_plugin('reviews');
        view()->share('reviewsPlugin', $reviewsPlugin);
    }
    
    /**
     * @param SendPostByEmailRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendByEmail(SendPostByEmailRequest $request)
    {
        // Get Post
        $post = Post::find($request->input('post'));
        if (!empty($post)) {
            // Store data
            $mailData = [
                'post_id'         => $post->id,
                'sender_email'    => $request->input('sender_email'),
                'recipient_email' => $request->input('recipient_email'),
                'message'         => $request->input('message'),
            ];
            
            // Send the Post by email
            try {
                Mail::send(new PostSentByEmail($post, $mailData));
                flash(t("Your message has sent successfully"))->success();
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
        
        return redirect(URL::previous());
    }
}
