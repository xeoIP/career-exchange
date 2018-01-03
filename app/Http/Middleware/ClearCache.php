<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;

class ClearCache
{
	/**
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 */
    public function handle($request, Closure $next)
    {
        $exitCode = Artisan::call('cache:clear');
        
        return $next($request);
    }
}
