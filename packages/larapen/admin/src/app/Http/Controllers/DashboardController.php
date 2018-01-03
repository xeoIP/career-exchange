<?php

namespace Larapen\Admin\app\Http\Controllers;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;
use App\Models\Post;
use App\Models\Country;
use App\Models\User;

class DashboardController extends PanelController
{
	public $data = []; // the information we send to the view

	/**
	 * Create a new controller instance.
	 */
	public function __construct()
	{
		$this->middleware('admin');
		
		parent::__construct();
        
        // Get the Mini Stats data
        // Count Ads
        $countActivatedPosts = Post::verified()->count();
        $countUnactivatedPosts = Post::unverified()->count();
        
        // Count Users
        $countActivatedUsers = User::where('is_admin', 0)->verified()->count();
        $countUnactivatedUsers = User::where('is_admin', 0)->unverified()->count();
        
        // Count all users
        $countUsers = User::where('is_admin', 0)->count();
        
        // Count activated countries
        $countCountries = Country::where('active', 1)->count();
        
        view()->share('countActivatedPosts', $countActivatedPosts);
        view()->share('countUnactivatedPosts', $countUnactivatedPosts);
        view()->share('countActivatedUsers', $countActivatedUsers);
        view()->share('countUnactivatedUsers', $countUnactivatedUsers);
        view()->share('countUsers', $countUsers);
        view()->share('countCountries', $countCountries);
	}

	/**
	 * Show the admin dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard()
	{
        // Limit latest entries
        $latestEntriesLimit = 5;

        // Get latest Ads
        $posts = Post::take($latestEntriesLimit)->orderBy('id', 'DESC')->get();
        $this->data['posts'] = $posts;

        // Get latest Users
        $users = User::take($latestEntriesLimit)->orderBy('id', 'DESC')->get();
        $this->data['users'] = $users;

        // Get Stats
        $statDayNumber = 30;
        setlocale(LC_TIME, config('applang.locale'));
        $currentDate = Carbon::now();

        $stats = [];
        for ($i = 1; $i <= $statDayNumber; $i++) {
            $dateObj = ($i == 1) ? $currentDate : $currentDate->subDay();
            $date = $dateObj->toDateString();

            // Ads Stats
            $countActivatedPosts = Post::verified()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date . ' 23:59:59')
                ->count();
    
            $countUnactivatedPosts = Post::unverified()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date . ' 23:59:59')
                ->count();
            
            $stats['posts'][$i]['y'] = ucfirst($dateObj->formatLocalized('%b %d'));
            $stats['posts'][$i]['activated'] = $countActivatedPosts;
            $stats['posts'][$i]['unactivated'] = $countUnactivatedPosts;

            // Users Stats
            $countActivatedUsers = User::where('is_admin', 0)
                ->verified()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date . ' 23:59:59')
                ->count();
    
            $countUnactivatedUsers = User::where('is_admin', 0)
                ->unverified()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date . ' 23:59:59')
                ->count();
            
            $stats['users'][$i]['y'] = ucfirst($dateObj->formatLocalized('%b %d'));
            $stats['users'][$i]['activated'] = $countActivatedUsers;
            $stats['users'][$i]['unactivated'] = $countUnactivatedUsers;
        }

        $stats['posts'] = array_reverse($stats['posts'], true);
        $stats['users'] = array_reverse($stats['users'], true);

        $this->data['postsStats'] = json_encode(array_values($stats['posts']), JSON_NUMERIC_CHECK);
        $this->data['usersStats'] = json_encode(array_values($stats['users']), JSON_NUMERIC_CHECK);

		$this->data['title'] = trans('admin::messages.dashboard'); // set the page title

		return view('admin::dashboard', $this->data);
	}

	/**
	 * Redirect to the dashboard.
	 *
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function redirect()
	{
		// The '/admin' route is not to be used as a page, because it breaks the menu's active state.
		return redirect(config('larapen.admin.route_prefix', 'admin') . '/dashboard');
	}
}
