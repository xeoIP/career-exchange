<?php

namespace Larapen\Admin\app\Http\Middleware;

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
            // Block access if user is not admin role
            if (!Auth::guard($guard)->user()->is_admin) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response(trans('admin::messages.unauthorized'), 401);
                } else {
                    Auth::logout();
                    flash()->error("Permission Denied.");
                    return redirect()->guest('login');
                }
            }
        } else {
            // Block access if user is guest (not logged in)
            if ($request->ajax() || $request->wantsJson()) {
                return response(trans('admin::messages.unauthorized'), 401);
            } else {
                if ($request->path() != config('larapen.admin.route_prefix', 'admin') . '/login') {
                    Alert::error("Permission Denied.")->flash();
                    return redirect()->guest(config('larapen.admin.route_prefix', 'admin') . '/login');
                }
            }
        }
        
        return $next($request);
    }
}
