<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;

class BannedUser
{
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            // Block access if user is banned
            if (Auth::guard($guard)->user()->blocked) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response(trans('admin::messages.unauthorized'), 401);
                } else {
                    Auth::logout();
                    
                    $message = "This user has been banned.";
                    
                    flash()->error($message);
                    //Alert::error($message)->flash();
                    
                    return redirect()->guest('login');
                }
            }
        }
        
        return $next($request);
    }
}
