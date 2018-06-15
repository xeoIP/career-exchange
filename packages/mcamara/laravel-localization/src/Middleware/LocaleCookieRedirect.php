<?php namespace Larapen\LaravelLocalization\Middleware;

use Illuminate\Http\RedirectResponse;
use Closure;

class LocaleCookieRedirect extends LaravelLocalizationMiddlewareBase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If the URL of the request is in exceptions.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $params = explode('/', $request->path());
        $locale = $request->cookie('locale', false);

        if (count($params) > 0 && $locale = app('laravellocalization')->checkLocaleInSupportedLocales($params[0])) {
            //cookie('locale', $params[0]);

            //return $next($request);
            return $next($request)->withCookie(cookie()->forever('locale', $params[0]));
        }

        /*
        if ($locale && !(app('laravellocalization')->getDefaultLocale() === $locale && app('laravellocalization')->hideDefaultLocaleInURL())) {
            app('session')->reflash();
            $redirection = app('laravellocalization')->getLocalizedURL($locale);

            return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
        }
        */
        if ($locale && app('laravellocalization')->checkLocaleInSupportedLocales($locale) && !(app('laravellocalization')->getDefaultLocale() === $locale && app('laravellocalization')->hideDefaultLocaleInURL())) {
            $redirection = app('laravellocalization')->getLocalizedURL($locale);
            $redirectResponse = new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);

            return $redirectResponse->withCookie(cookie()->forever('locale', $params[0]));
        }

        return $next($request);
    }
}