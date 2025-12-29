<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainRedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Domain redirect for Railway app
        if (!request()->has('dont_redirec') && $request->getHost() === 'e7lal-production.up.railway.app') {
            $url = 'https://www.e7lal.com' . $request->getRequestUri();
            return redirect()->to($url, 301);
        }

        // Force HTTPS only for the main domain (www.e7lal.com)
        if (!$request->secure() && app()->environment('production') && $request->getHost() === 'www.e7lal.com') {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Add HSTS header for better security
        if ($request->secure() && app()->environment('production')) {
            $response = $next($request);
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            return $response;
        }

        return $next($request);
    }
}
