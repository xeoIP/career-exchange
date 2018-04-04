<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\RegisterRequest;
use App\Mail\UserNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Helpers\Ip;
use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Models\Gender;
use App\Models\UserType;
use App\Models\User;
use App\Http\Controllers\FrontController;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

class RegisterController extends FrontController
{
	use RegistersUsers, VerificationTrait;
	
	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/account';
	
	/**
	 * @var array
	 */
	public $msg = [];
	
	/**
	 * SignupController constructor.
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
		$this->redirectTo = config('app.locale') . '/account';
	}
	
	/**
	 * Show the form the create a new user account.
	 *
	 * @return View
	 */
	public function showRegistrationForm()
	{
		$data = [];
		
		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();
		$data['userTypes'] = UserType::all();
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'register'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		
		return view('auth.register.index', $data);
	}
	
	/**
	 * Store a new ad post.
	 *
	 * @param RegisterRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function register(RegisterRequest $request)
	{
		// Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.email_verification') == 1 && $request->filled('email');
        $phoneVerificationRequired = config('settings.phone_verification') == 1 && $request->filled('phone');


		// Store User
		$userInfo = [
			'country_code'   => config('country.code'),
			'gender_id'      => $request->input('gender'),
			'name'           => $request->input('name'),
			'user_type_id'   => $request->input('user_type'),
			'phone'          => $request->input('phone'),
			'email'          => $request->input('email'),
			'username'       => $request->input('username'),
			'password'       => bcrypt($request->input('password')),
			'phone_hidden'   => $request->input('phone_hidden'),
			'ip_addr'        => Ip::get(),
			'verified_email' => 1,
			'verified_phone' => 1,
		];
		
		// Email verification key generation
		if ($emailVerificationRequired) {
			$userInfo['email_token'] = md5(microtime() . mt_rand());
			$userInfo['verified_email'] = 0;
		}
		
		// Mobile activation key generation
		if ($phoneVerificationRequired) {
			$userInfo['phone_token'] = mt_rand(100000, 999999);
			$userInfo['verified_phone'] = 0;
		}
		
		// Save the User into database
		$user = new User($userInfo);
		$user->save();


		// Add Job seekers resume
		if ($request->input('user_type') == 3) {
			if ($request->hasFile('filename')) {
				// Save user's resume
				$resumeInfo = [
					'country_code' => config('country.code'),
					'user_id'      => $user->id,
					'active'       => 1,
				];
				$resume = new Resume($resumeInfo);
				$resume->save();
				
				// Upload user's resume
				$resume->filename = $request->file('filename');
				$resume->save();
			}
		}
		
		// Message Notification & Redirection
		$request->session()->flash('message', t("Your account has been created."));
		$nextUrl = config('app.locale') . '/register/finish';
		
		
		// Send Admin Notification Email
		if (config('settings.admin_email_notification') == 1) {
			try {
				// Get all admin users
				$admins = User::where('is_admin', 1)->get();
				if ($admins->count() > 0) {
					foreach ($admins as $admin) {
						Mail::send(new UserNotification($user, $admin));
					}
				}
			} catch (\Exception $e) {
				flash()->error($e->getMessage());
			}
		}
		
		// Send Email Verification message
		if ($emailVerificationRequired) {
			// Save the Next URL before verification
			session(['userNextUrl' => $nextUrl]);
			
			// Send
			$this->sendVerificationEmail($user);
			
			// Show the Re-send link
			$this->showReSendVerificationEmailLink($user, 'user');
		}
		
		// Send Phone Verification message
		if ($phoneVerificationRequired) {
			// Save the Next URL before verification
			session(['userNextUrl' => $nextUrl]);
			
			// Send
			$this->sendVerificationSms($user);
			
			// Show the Re-send link
			$this->showReSendVerificationSmsLink($user, 'user');
			
			// Go to Phone Number verification
			$nextUrl = config('app.locale') . '/verify/user/phone/';
		}
		
		// Redirect to the user area If Email or Phone verification is not required
		if (!$emailVerificationRequired && !$phoneVerificationRequired) {
			if (Auth::loginUsingId($user->id)) {
				return redirect()->intended(config('app.locale') . '/account');
			}
		}
		
		// Redirection
		return redirect($nextUrl);
	}
	
	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
	 */
	public function finish()
	{
		// Keep Success Message for the page refreshing
		session()->keep(['message']);
		if (!session()->has('success')) {
			return redirect(config('app.locale') . '/');
		}
		
		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));
		
		return view('auth.register.finish');
	}
}
