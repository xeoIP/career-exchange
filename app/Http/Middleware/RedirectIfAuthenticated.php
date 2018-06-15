<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($request->segment(1) == config('larapen.admin.route_prefix', 'admin')) {
                return redirect(config('larapen.admin.route_prefix', 'admin') . '/?login=success');
            } else {
                return redirect('/?login=success');
            }
        }
        
        return $next($request);
    }
}
