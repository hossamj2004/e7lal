<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainRedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getHost() === 'e7lal-production.up.railway.app') {
            return redirect()->to(
                'https://www.e7lal.com' . $request->getRequestUri(),
                301
            );
        }

        return $next($request);
    }
}
