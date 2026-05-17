<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::where('is_verified', true)->latest();

        // Filter by Type (Hospital vs Clinic)
        if ($request->filled('type') && $request->type !== 'All') {
            $query->where('type', $request->type);
        }

        // Filter by City
        if ($request->filled('city') && $request->city !== 'All') {
            $query->where('city', $request->city);
        }

        // Filter by Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'LIKE', "%{$search}%")
                    ->orWhere('name_hi', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        $cities = Hospital::where('is_verified', true)->whereNotNull('city')->distinct()->pluck('city');
        $types = Hospital::where('is_verified', true)->whereNotNull('type')->distinct()->pluck('type');

        return view('hospitals.index', [
            'hospitals' => $query->get()->map(fn(Hospital $hospital) => [
                'id' => $hospital->id,
                'name' => [
                    'en' => $hospital->name_en,
                    'hi' => $hospital->name_hi,
                ],
                'type' => $hospital->type,
                'address' => $hospital->address,
                'address_line1' => $hospital->address_line1,
                'address_line2' => $hospital->address_line2,
                'state' => $hospital->state,
                'pincode' => $hospital->pincode,
                'city' => $hospital->city,
                'latitude' => $hospital->latitude,
                'longitude' => $hospital->longitude,
                'emergency_phone' => $hospital->emergency_phone,
                'is_verified' => $hospital->is_verified,
                'accepts_ayushman' => $hospital->accepts_ayushman,
                'accepts_janaadhaar' => $hospital->accepts_janaadhaar,
                'accepts_cghs' => $hospital->accepts_cghs,
                'is_cashless' => $hospital->is_cashless,
                'cashless_schemes_list' => $hospital->cashless_schemes_list,
            ]),
            'cities' => $cities,
            'types' => $types,
            'filters' => $request->only(['type', 'city', 'search']),
        ]);
    }
}
