<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;

class Admin
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
            if (!Auth::guard($guard)->user()->is_admin) {
                Auth::logout();
                //Alert::error("Permission Denied.")->flash();
                flash()->error("Permission Denied.");
                return redirect()->guest('login');
            }
        } else {
            if ($request->path() != config('larapen.admin.route_prefix', 'admin') . '/login') {
                Alert::error("Permission Denied.")->flash();
                
                return redirect()->guest(config('larapen.admin.route_prefix', 'admin') . '/login');
            }
        }
        
        return $next($request);
    }
}
