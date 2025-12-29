<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainRedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS for all requests in production
        if (!$request->secure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Add HSTS header for better security (only for HTTPS requests)
        if ($request->secure() && app()->environment('production')) {
            $response = $next($request);
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            return $response;
        }

        if (!request()->has('dont_redirec') && $request->getHost() === 'e7lal-production.up.railway.app') {
            return redirect()->to(
                'https://www.e7lal.com' . $request->getRequestUri(),
                301
            );
        }

        return $next($request);
    }
}
