<?php

namespace App\Http\Controllers;

use App\Helpers\Arr;
use App\Models\Post;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\SubAdmin1;
use App\Models\City;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

class HomeController extends FrontController
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Check Country URL for SEO
        $countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        view()->share('countries', $countries);
        // Check page slug
        #$pageSlug = PageController::getNameHtml();
        #view()->share('pageSlug', $pageSlug);
    }

    /**
     * @return View
     */
    public function index()
    {
        //return redirect('account');
        $data = [];

        // Get all homepage sections
        $data['sections'] = Cache::remember('homeSections', $this->cacheExpiration, function () {
            $sections = HomeSection::orderBy('lft')->get();
            return $sections;
        });

        if ($data['sections']->count() > 0) {
            foreach ($data['sections'] as $section) {
                // Check if method exists
                if (!method_exists($this, $section->method)) {
                    continue;
                }

                // Call the method
                try {
                    if (isset($section->options)) {
                        $this->{$section->method}($section->options);
                    } else {
                        $this->{$section->method}();
                    }
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                    continue;
                }
            }
        }

        // Get SEO
        $this->setSeo();

        return view('home.index', $data);
    }

    /**
     * Get locations & SVG map
     *
     * @param array $options
     */
    protected function getLocations($options = [])
    {
        // Get the Default Max. Items
        $maxItems = 14;
        if (isset($options['max_items'])) {
            $maxItems = (int)$options['max_items'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($options);

        // Modal - States Collection
        $cacheId = config('country.code') . '.home.getLocations.modalAdmins';
        $modalAdmins = Cache::remember($cacheId, $cacheExpiration, function () {
            $modalAdmins = SubAdmin1::currentCountry()->orderBy('name')->get(['code', 'name'])->keyBy('code');
            return $modalAdmins;
        });
        view()->share('modalAdmins', $modalAdmins);

        // Get cities
        $cacheId = config('country.code') . 'home.getLocations.cities';
        $cities = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
            $cities = City::currentCountry()->take($maxItems)->orderBy('population', 'DESC')->orderBy('name')->get();
            return $cities;
        });
        $cities = collect($cities)->push(Arr::toObject([
            'id'             => 999999999,
            'name'           => t('More cities') . ' &raquo;',
            'subadmin1_code' => 0,
        ]));

        // Get cities number of columns
        $nbCol = 4;
        if (file_exists(config('larapen.core.maps.path') . strtolower(config('country.code')) . '.svg')) {
            if (isset($options['show_map']) and $options['show_map'] == '1') {
                $nbCol = 3;
            }
        }

        // Chunk
        $cols = round($cities->count() / $nbCol, 0); // PHP_ROUND_HALF_EVEN
        $cols = ($cols > 0) ? $cols : 1; // Fix array_chunk with 0
        $cities = $cities->chunk($cols);

        view()->share('cities', $cities);
        view()->share('citiesOptions', $options);
    }

    /**
     * Get sponsored posts
     *
     * @param array $options
     */
    protected function getSponsoredPosts($options = [])
    {
        // Get the Default Max. Items
        $maxItems = 20;
        if (isset($options['max_items'])) {
            $maxItems = (int)$options['max_items'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($options);

        $sponsored = null;

        // Get Posts
        $posts = $this->getPosts($maxItems, 'sponsored', $cacheExpiration);

        if (!empty($posts)) {
            shuffle($posts); // Random
            $sponsored = [
                'title' => t('Home - Sponsored Jobs'),
                'link'  => lurl(trans('routes.v-search', ['countryCode' => $this->country->get('icode')])),
                'posts' => $posts,
            ];
            $sponsored = Arr::toObject($sponsored);
        }

        view()->share('featured', $sponsored);
        view()->share('featuredOptions', $options);
    }

    /**
     * Get latest posts
     *
     * @param array $options
     */
    protected function getLatestPosts($options = [])
    {
        // Get the Default Max. Items
        $maxItems = 5;
        if (isset($options['max_items'])) {
            $maxItems = (int)$options['max_items'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($options);

        $latest = null;

        // Get Posts
        $posts = $this->getPosts($maxItems, 'latest', $cacheExpiration);

        if (!empty($posts)) {
            shuffle($posts);
            $latest = [
                'title' => t('Home - Latest Jobs'),
                'link'  => lurl(trans('routes.v-search', ['countryCode' => $this->country->get('icode')])),
                'posts' => $posts,
            ];
            $latest = Arr::toObject($latest);
        }

        view()->share('latest', $latest);
        view()->share('latestOptions', $options);
    }

    /**
     * Get featured ads companies
     *
     * @param array $options
     */
    private function getFeaturedPostsCompanies($options = [])
    {
        // Get the Default Max. Items
        $maxItems = 12;
        if (isset($options['max_items'])) {
            $maxItems = (int)$options['max_items'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($options);

        $featuredCompanies = null;

        // Get Categories
        $reviewedCondition = '';
        if (config('settings.posts_review_activation')) {
            $reviewedCondition = ' AND a.reviewed = 1';
        }
        $sql = 'SELECT DISTINCT a.*, COUNT(a.id) as count_posts' . '
				FROM ' . table('posts') . ' as a
				INNER JOIN ' . table('categories') . ' as c ON c.id=a.category_id AND c.active=1
				LEFT JOIN ' . table('payments') . ' as py ON py.post_id=a.id
                LEFT JOIN ' . table('packages') . ' as p ON p.id=py.package_id
				WHERE a.country_code = :country_code
					AND (a.verified_email=1 OR a.verified_phone=1)
					AND a.archived!=1
					AND a.deleted_at IS NULL ' . $reviewedCondition . '
				GROUP BY a.company_name
				ORDER BY p.lft DESC, a.logo DESC, a.created_at DESC
				LIMIT 0,' . $maxItems;
        $bindings = [
            'country_code' => config('country.code'),
        ];

        $cacheId = config('country.code') . '.home.getFeaturedPostsCompanies';
        $posts = Cache::remember($cacheId, $cacheExpiration, function () use ($sql, $bindings) {
            $posts = DB::select(DB::raw($sql), $bindings);
            return $posts;
        });

        if (!empty($posts)) {
            shuffle($posts);
            $featuredCompanies = [
                'title' => t('Home - Featured Company'),
                'link'  => lurl(trans('routes.v-search', ['countryCode' => $this->country->get('icode')])),
                'posts' => $posts,
            ];
            $featuredCompanies = Arr::toObject($featuredCompanies);
        }

        view()->share('featuredCompanies', $featuredCompanies);
        view()->share('featuredCompaniesOptions', $options);
    }

    /**
     * Get list of categories
     *
     * @param array $options
     */
    protected function getCategories($options = [])
    {
        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($options);

        if (empty($cacheExpiration) || $cacheExpiration <= 0) {
            $categories = Category::trans()->where('parent_id', 0)->orderBy('lft')->get();
        } else {
            $cacheId = 'categories.parents.' . config('app.locale');
            $categories = Cache::remember($cacheId, $cacheExpiration, function () {
                $categories = Category::trans()->where('parent_id', 0)->orderBy('lft')->get();
                return $categories;
            });
        }

        $cols = round($categories->count() / 3, 0); // PHP_ROUND_HALF_EVEN
        $cols = ($cols > 0) ? $cols : 1; // Fix array_chunk with 0
        $categories = $categories->chunk($cols);

        view()->share('categories', $categories);
    }

    /**
     * Get mini stats data
     */
    protected function getStats()
    {
        // Count posts
        $countPosts = Post::currentCountry()->count();

        // Count cities
        $countCities = City::currentCountry()->count();

        // Count users
        $countUsers = User::count();

        // Share vars
        view()->share('countPosts', $countPosts);
        view()->share('countCities', $countCities);
        view()->share('countUsers', $countUsers);
    }

    /**
     * Set SEO information
     */
    protected function setSeo()
    {
        $title = getMetaTag('title', 'home');
        $description = getMetaTag('description', 'home');
        $keywords = getMetaTag('keywords', 'home');

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', strip_tags($description));
        MetaTag::set('keywords', $keywords);

        // Open Graph
        $this->og->title($title)->description($description);
        view()->share('og', $this->og);
    }

    /**
     * @param int $limit
     * @param string $type (latest OR featured)
     * @param int $cacheExpiration
     * @return mixed
     */
    private function getPosts($limit = 20, $type = 'latest', $cacheExpiration = 0)
    {
        $paymentJoin = '';
        $featuredOrder = '';
        if ($type == 'sponsored') {
            $paymentJoin .= 'INNER JOIN ' . table('payments') . ' as py ON py.post_id=a.id' . "\n";
            $paymentJoin .= 'INNER JOIN ' . table('packages') . ' as p ON p.id=py.package_id' . "\n";
            $featuredOrder = 'p.lft DESC, ';
        } else {
            $paymentJoin .= 'LEFT JOIN ' . table('payments') . ' as py ON py.post_id=a.id' . "\n";
            $paymentJoin .= 'LEFT JOIN ' . table('packages') . ' as p ON p.id=py.package_id' . "\n";
        }
        $reviewedCondition = '';
        if (config('settings.posts_review_activation')) {
            $reviewedCondition = ' AND a.reviewed = 1';
        }
        $sql = 'SELECT DISTINCT a.*, py.package_id as py_package_id' . '
                FROM ' . table('posts') . ' as a
                INNER JOIN ' . table('categories') . ' as c ON c.id=a.category_id AND c.active=1
                ' . $paymentJoin . '
                WHERE a.country_code = :country_code
                	AND (a.verified_email=1 AND a.verified_phone=1)
                	AND a.archived!=1 ' . $reviewedCondition . '
                GROUP BY a.id
                ORDER BY ' . $featuredOrder . 'a.created_at DESC
                LIMIT 0,' . (int)$limit;
        $bindings = [
            'country_code' => config('country.code'),
        ];

        $cacheId = config('country.code') . '.home.getPosts.' . $type;
        $posts = Cache::remember($cacheId, $cacheExpiration, function () use ($sql, $bindings) {
            $posts = DB::select(DB::raw($sql), $bindings);
            return $posts;
        });

        return $posts;
    }

    /**
     * @param array $options
     * @return int
     */
    private function getCacheExpirationTime($options = [])
    {
        // Get the Default Cache Expiration Time
        $cacheExpiration = 0;
        if (isset($options['cache_expiration'])) {
            $cacheExpiration = (int)$options['cache_expiration'];
        }

        return $cacheExpiration;
    }
}
