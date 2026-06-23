<?php

namespace App\Http\Controllers;

use App\Models\UserSubmission;
use Illuminate\Http\Request;
use App\Helpers\LocaleHelper;

class SuggestionController extends Controller
{
    public function create(Request $request)
    {
        $isHi = LocaleHelper::current() === 'hi';
        $defaultType = $request->query('type') === 'hospital' ? 'hospital' : 'doctor';

        return view('suggestions.create', compact('isHi', 'defaultType'));
    }

    public function store(Request $request)
    {
        $isHi = LocaleHelper::current() === 'hi';

        $rules = [
            'type' => 'required|in:doctor,hospital',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
        ];

        if ($request->input('type') === 'doctor') {
            $rules['registration_number'] = 'required|string|max:100';
            $rules['specialization'] = 'required|string|max:255';
        } else {
            $rules['hospital_type'] = 'required|string|max:100';
        }

        $request->validate($rules);

        $details = [
            'address' => $request->input('address'),
        ];

        if ($request->input('type') === 'doctor') {
            $details['registration_number'] = $request->input('registration_number');
            $details['specialization'] = $request->input('specialization');
        } else {
            $details['hospital_type'] = $request->input('hospital_type');
            $details['accepts_ayushman'] = $request->has('accepts_ayushman');
            $details['accepts_janaadhaar'] = $request->has('accepts_janaadhaar');
        }

        UserSubmission::create([
            'type' => $request->input('type'),
            'status' => 'pending',
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'details' => $details,
        ]);

        $msg = $isHi 
            ? 'आपके सुझाव के लिए धन्यवाद! विवरणों को सत्यापित करने के बाद निर्देशिका में जोड़ दिया जाएगा।' 
            : 'Thank you for your suggestion! The details will be verified and added to the directory.';

        return back()
            ->with('success', $msg)
            ->with('site_popup', [
                'type' => 'success',
                'title' => 'Thank you for your contribution',
                'message' => $msg,
            ]);
    }
}
