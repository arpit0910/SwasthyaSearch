<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToComingSoon
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            app()->environment('live')
            && ! $request->routeIs('coming-soon')
            && ! $request->is('coming-soon')
        ) {
            return redirect()->route('coming-soon');
        }

        return $next($request);
    }
}
