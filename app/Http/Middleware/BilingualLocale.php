<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class BilingualLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if (in_array($locale, ['en', 'hi'])) {
                App::setLocale($locale);
            }
        } else {
            // Default to English if no session locale exists
            App::setLocale('en');
        }

        return $next($request);
    }
}
