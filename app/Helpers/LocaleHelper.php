<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Request;

class LocaleHelper
{
    /**
     * Determine the current locale for the request.
     * Checks for a 'lang' query parameter (e.g., ?lang=hi), falls back to the
     * application locale, and defaults to 'en' if nothing matches.
     *
     * @return string 'en' or 'hi'
     */
    public static function current(): string
    {
        $locale = Request::query('lang');
        if ($locale && in_array($locale, ['en', 'hi'])) {
            return $locale;
        }

        $appLocale = AppFacade::getLocale();
        if (in_array($appLocale, ['en', 'hi'])) {
            return $appLocale;
        }

        return 'en';
    }
}
