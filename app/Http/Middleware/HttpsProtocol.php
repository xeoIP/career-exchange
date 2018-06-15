<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
	/**
	 * Redirects any non-secure requests to their secure counterparts.
	 *
	 * @param $request
	 * @param Closure $next
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next)
	{
		if (config('larapen.core.force_https') == true) {
			$request->setTrustedProxies([$request->getClientIp()]);
			if (!$request->secure()) {
				/* $request->server('HTTP_X_FORWARDED_PROTO') != 'https' */
				// Production is not currently secure
				// return redirect()->secure($request->getRequestUri());
			}
		}

		return $next($request);
	}
}
