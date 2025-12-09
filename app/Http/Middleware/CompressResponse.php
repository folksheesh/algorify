<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if client accepts gzip compression
        if (strpos($request->header('Accept-Encoding'), 'gzip') !== false) {
            // Only compress HTML, CSS, JavaScript
            $contentType = $response->headers->get('Content-Type');
            if (in_array($contentType, ['text/html', 'text/css', 'application/javascript', 'application/json'])) {
                ob_start('ob_gzhandler');
                $response->setContent(ob_get_clean());
                $response->headers->set('Content-Encoding', 'gzip');
            }
        }

        return $response;
    }
}
