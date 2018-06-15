<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class HttpCache
{
	/**
	 * @param $request
	 * @param Closure $next
	 * @param string $cache
	 * @return mixed
	 */
    public function handle($request, Closure $next, $cache = 'yes')
    {
        $response = $next($request);
        
        // Don't minify the HTML in development environment
        if (config('settings.activation_http_cache') == 0) {
            return $response;
        }
        
        // Security Headers
        $response->header("X-Content-Type-Options", "nosniff");
        
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            // Get the last-modified-date of this very file
            $lastModified = filemtime($_SERVER['SCRIPT_FILENAME']);
            // Get a unique hash of this file (etag)
            $eTagFile = md5_file($_SERVER['SCRIPT_FILENAME']);
            // Get the HTTP_IF_MODIFIED_SINCE header if set
            $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
            // Get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
            $eTagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
            
            // Set last-modified header
            $response->header("Last-Modified", gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
            $response->header("Etag", "$eTagFile");
            $response->header('Cache-Control', 'public');
            $response->header("Pragma", "cache"); //HTTP 1.0
            
            // Check if page has changed. If not, send 304 and exit
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
                if ($ifModifiedSince == $lastModified || $eTagHeader == $eTagFile) {
                    $response->header('HTTP/1.1', '304 Not Modified');
                }
            }
            
        } else {
            $response->header("Cache-Control", "max-age=86400, public, s-maxage=86400"); //HTTP 1.1
            $response->header("Pragma", "cache"); //HTTP 1.0
            $response->header("Expires", Carbon::now()->addDay()->format('D, d M Y H:i:s') . ' GMT'); // Date in the future or now
        }
        
        /*
        // No caching for pages
        $response->header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        //$response->header("Pragma", "no-cache");
        $response->header("Expires"," Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        */
        
        return $response;
    }
}
