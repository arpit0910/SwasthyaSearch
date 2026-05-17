<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function privacyPolicy(Request $request)
    {
        $locale = $request->session()->get('locale', 'en');
        return view('legal.privacy-policy', compact('locale'));
    }

    public function termsOfService(Request $request)
    {
        $locale = $request->session()->get('locale', 'en');
        return view('legal.terms-of-service', compact('locale'));
    }
}
