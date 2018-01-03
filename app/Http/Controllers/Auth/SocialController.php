<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\Ip;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FrontController;
use App\Models\User;
use Illuminate\Support\Facades\Request as Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Post;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotification;

class SocialController extends FrontController
{
    use AuthenticatesUsers;
    
    protected $redirectTo = '/account';
    protected $redirectPath = '/account';
    private $network = ['facebook', 'google', 'twitter'];
    
    /**
     * SocialController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return mixed
     */
    public function redirectToProvider()
    {
        $provider = Request::segment(2);
        if (!in_array($provider, $this->network)) {
            $provider = Request::segment(3);
        }
        if (!in_array($provider, $this->network)) {
            abort(404);
        }
        
        return Socialite::driver($provider)->redirect();
    }
    
    /**
     * Obtain the user information from Provider.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback()
    {
        $provider = Request::segment(2);
        if (!in_array($provider, $this->network)) {
            $provider = Request::segment(3);
        }
        if (!in_array($provider, $this->network)) {
            abort(404);
        }
        
        // Country Code
        if (isset($this->country) && $this->country) {
            $countryCode = $this->country->get('code');
        } else {
            $countryCode = (isset($this->ipCountry) && $this->ipCountry) ? $this->ipCountry->get('code') : null;
        }
        
        // API CALL - GET USER FROM PROVIDER
        try {
            $userData = Socialite::driver($provider)->user();
            
            // Data not found
            if (!$userData) {
                $message = t("Unknown error. Please try again in a few minutes.");
                flash($message)->error();
                
                return redirect(config('app.locale') . '/' . trans('routes.login'));
            }
            
            // Email not found
            if (!$userData || !filter_var($userData->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $message = t("Email address not found. You can't use your :provider account on our website.", ['provider' => ucfirst($provider)]);
                flash($message)->error();
                
                return redirect(config('app.locale') . '/' . trans('routes.login'));
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (is_string($message) && !empty($message)) {
                flash($message)->error();
            } else {
                $message = "Unknown error. The social network API doesn't work.";
                flash($message)->error();
            }
            
            return redirect(config('app.locale') . '/' . trans('routes.login'));
        }
        
        // Debug
        // dd($userData);
        
        // DATA MAPPING
        try {
            $mapUser = [];
            if ($provider == 'facebook') {
                $mapUser['name'] = (isset($userData->user['name'])) ? $userData->user['name'] : '';
                if ($mapUser['name'] == '') {
                    if (isset($userData->user['first_name']) && isset($userData->user['last_name'])) {
                        $mapUser['name'] = $userData->user['first_name'] . ' ' . $userData->user['last_name'];
                    }
                }
            } else {
                if ($provider == 'google') {
                    $mapUser = [
                        'name' => (isset($userData->name)) ? $userData->name : '',
                    ];
                }
            }
            
            // GET LOCAL USER
            $user = User::where('provider', $provider)->where('provider_id', $userData->getId())->first();
            
            // CREATE LOCAL USER IF DON'T EXISTS
            if (empty($user)) {
                // Before... Check if user has not signup with an email
                $user = User::where('email', $userData->getEmail())->first();
                if (empty($user)) {
                    $userInfo = [
                        'country_code'   => $countryCode,
                        'name'           => $mapUser['name'],
                        'email'          => $userData->getEmail(),
                        'ip_addr'        => Ip::get(),
                        'verified_email' => 1,
                        'verified_phone' => 1,
                        'provider'       => $provider,
                        'provider_id'    => $userData->getId(),
                        'created_at'     => date('Y-m-d H:i:s'),
                    ];
                    $user = new User($userInfo);
                    $user->save();
                    
                    // Update Ads created by this email
                    if (isset($user->id) && $user->id > 0) {
                        Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('email', $userInfo['email'])->update(['user_id' => $user->id]);
                    }
                    
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
                            flash($e->getMessage())->error();
                        }
                    }
                    
                } else {
                    // Update 'created_at' if empty (for time ago module)
                    if (empty($user->created_at)) {
                        $user->created_at = date('Y-m-d H:i:s');
                        $user->save();
                    }
                }
            }
            
            // GET A SESSION FOR USER
            if (Auth::loginUsingId($user->id)) {
                return redirect()->intended(config('app.locale') . '/account');
            } else {
                $message = t("Error on user's login.");
                flash($message)->error();
                
                return redirect(config('app.locale') . '/' . trans('routes.login'));
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (is_string($message) && !empty($message)) {
                flash($message)->error();
            } else {
                $message = "Unknown error. The service does not work.";
                flash($message)->error();
            }
            
            return redirect(config('app.locale') . '/' . trans('routes.login'));
        }
    }
}
