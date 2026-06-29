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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];

        if ($request->has('address_line_1')) {
            $rules['address_line_1'] = 'required|string|max:255';
            $rules['address_line_2'] = 'nullable|string|max:255';
            $rules['state'] = 'required|string|max:100';
            $rules['pincode'] = 'required|string|max:20';
        } else {
            $rules['address'] = 'required|string|max:500';
        }

        if ($request->input('type') === 'doctor') {
            $rules['registration_number'] = 'required|string|max:100';
            $rules['specialization'] = 'required|string|max:255';
        } else {
            $rules['hospital_type'] = 'required|string|max:100';
        }

        $request->validate($rules);

        $address = $request->input('address');
        if ($request->has('address_line_1')) {
            $addressParts = array_filter([
                $request->input('address_line_1'),
                $request->input('address_line_2'),
                $request->input('city'),
                $request->input('state'),
                $request->input('pincode'),
            ]);
            $address = implode(', ', $addressParts);
        }

        $details = [
            'address' => $address,
            'latitude' => $request->input('latitude') !== null ? (float) $request->input('latitude') : null,
            'longitude' => $request->input('longitude') !== null ? (float) $request->input('longitude') : null,
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
