<?php

namespace App\Http\Middleware;

use Closure;

class HtmlMinify
{
    /**
     * @param $request
     * @param Closure $next
     * @param int $cache
     * @return mixed
     */
    public function handle($request, Closure $next, $cache = 1)
    {
        $response = $next($request);
        
        // Don't minify the HTML in development environment
        if (config('settings.activation_minify_html') == 0) {
            return $response;
        }
	
		// Minify HTML
		$content = $response->getContent();
		$search = [
			'/\>[^\S ]+/us',    // strip whitespaces after tags, except space
			'/[^\S ]+\</us',    // strip whitespaces before tags, except space
			'/(\s)+/us',        // shorten multiple whitespace sequences
		];
		$replace = [
			'>',
			'<',
			'\\1',
		];
		$buffer = preg_replace($search, $replace, $content);
		if (empty($buffer)) {
			$buffer = $content;
		}
	
		return $response->setContent($buffer);
    }
}
