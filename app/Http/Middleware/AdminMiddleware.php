<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'غير مصرح لك بالدخول'], 403);
            }
            return redirect()->route('home')->with('error', 'غير مصرح لك بالدخول');
        }

        return $next($request);
    }
}


