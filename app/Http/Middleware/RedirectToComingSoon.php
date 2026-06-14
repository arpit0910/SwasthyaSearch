<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToComingSoon
{
    public function handle(Request $request, Closure $next): Response
    {
// Disabled coming soon redirect for live site
        // if (
        //     app()->environment('live')
        //     && ! auth('admin')->check()
        //     && ! $request->is('admin/login')
        //     && ! $request->routeIs('coming-soon')
        //     && ! $request->is('coming-soon')
        // ) {
        //     return redirect()->route('coming-soon');
        // }

        return $next($request);
    }
}
