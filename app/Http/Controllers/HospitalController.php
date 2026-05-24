<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\HealthcareSyncService;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $userLat = $request->filled('user_lat') ? (float)$request->input('user_lat') : null;
        $userLng = $request->filled('user_lng') ? (float)$request->input('user_lng') : null;
        $hasUserLocation = is_numeric($userLat) && is_numeric($userLng);

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
                    ->orWhere('address_line1', 'LIKE', "%{$search}%")
                    ->orWhere('address_line2', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%")
                    ->orWhere('state', 'LIKE', "%{$search}%")
                    ->orWhere('pincode', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Benefit
        if ($request->filled('benefit') && $request->benefit !== 'All') {
            if ($request->benefit === 'ayushman') {
                $query->where('accepts_ayushman', true);
            } elseif ($request->benefit === 'janaadhaar') {
                $query->where('accepts_janaadhaar', true);
            } elseif ($request->benefit === 'cghs') {
                $query->where('accepts_cghs', true);
            } elseif ($request->benefit === 'cashless') {
                $query->where('is_cashless', true);
            }
        }

        $cities = Hospital::where('is_verified', true)->whereNotNull('city')->distinct()->pluck('city');
        $types = Hospital::where('is_verified', true)->whereNotNull('type')->distinct()->pluck('type');

        return view('hospitals.index', [
            'hospitals' => $query
                ->paginate(30)
                ->withQueryString()
                ->through(fn(Hospital $hospital) => [
                    'id' => $hospital->id,
                    'name_en' => $hospital->name_en,
                    'name_hi' => $hospital->name_hi ?: $this->buildHindiHospitalName($hospital),
                    'name' => [
                        'en' => $hospital->name_en,
                        'hi' => $hospital->name_hi ?: $this->buildHindiHospitalName($hospital),
                    ],
                    'type' => $hospital->type,
                    'address' => $hospital->display_address,
                    'address_hi' => $this->toHindiAddress($hospital->display_address),
                    'address_line1' => $hospital->address_line1,
                    'address_line1_hi' => $this->toHindiAddress($hospital->address_line1),
                    'address_line2' => $hospital->address_line2,
                    'address_line2_hi' => $this->toHindiAddress($hospital->address_line2),
                    'state' => $hospital->state,
                    'state_hi' => $this->toHindiState($hospital->state),
                    'pincode' => $hospital->pincode,
                    'city' => $hospital->city,
                    'city_hi' => $this->toHindiCity($hospital->city),
                    'latitude' => $hospital->latitude,
                    'longitude' => $hospital->longitude,
                    'emergency_phone' => $hospital->emergency_phone,
                    'is_verified' => $hospital->is_verified,
                    'accepts_ayushman' => $hospital->accepts_ayushman,
                    'accepts_janaadhaar' => $hospital->accepts_janaadhaar,
                    'accepts_cghs' => $hospital->accepts_cghs,
                    'is_cashless' => $hospital->is_cashless,
                    'cashless_schemes_list' => $hospital->cashless_schemes_list,
                    'distance_km' => $hasUserLocation
                        ? $this->calculateDistanceKm($userLat, $userLng, $hospital->latitude, $hospital->longitude)
                        : null,
                ]),
            'cities' => $cities,
            'types' => $types,
            'filters' => $request->only(['type', 'city', 'search', 'benefit', 'user_lat', 'user_lng']),
            'hasUserLocation' => $hasUserLocation,
        ]);
    }

    private function calculateDistanceKm(?float $userLat, ?float $userLng, $targetLat, $targetLng): ?float
    {
        if (!is_numeric($userLat) || !is_numeric($userLng) || !is_numeric($targetLat) || !is_numeric($targetLng)) {
            return null;
        }

        $earthRadius = 6371;
        $latFrom = deg2rad((float)$userLat);
        $lonFrom = deg2rad((float)$userLng);
        $latTo = deg2rad((float)$targetLat);
        $lonTo = deg2rad((float)$targetLng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($angle * $earthRadius, 1);
    }

    public function doctors(Hospital $hospital)
    {
        $hospital->load(['doctors.department', 'doctors.hospitals']);

        $doctors = $hospital->doctors
            ->where('is_verified', true)
            ->sortByDesc('experience_years')
            ->values()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'first_name' => $doctor->first_name,
                    'last_name' => $doctor->last_name,
                    'experience_years' => $doctor->experience_years,
                    'is_verified' => $doctor->is_verified,
                    'phone' => $doctor->phone,
                    'about_en' => $doctor->about_en,
                    'about_hi' => $doctor->about_hi,
                    'education_degrees' => $doctor->education_degrees,
                    'department' => $doctor->department ? [
                        'name_en' => $doctor->department->name_en,
                        'name_hi' => $doctor->department->name_hi,
                    ] : null,
                    'consultation_fee' => optional($doctor->pivot)->consultation_fee,
                ];
            });

        $hospitalData = [
            'id' => $hospital->id,
            'name_en' => $hospital->name_en,
            'name_hi' => $hospital->name_hi ?: $this->buildHindiHospitalName($hospital),
            'city' => $hospital->city,
            'city_hi' => $this->toHindiCity($hospital->city),
            'type' => $hospital->type,
            'emergency_phone' => $hospital->emergency_phone,
            'address_line1' => $hospital->address_line1,
            'address_line1_hi' => $this->toHindiAddress($hospital->address_line1),
            'address_line2' => $hospital->address_line2,
            'address_line2_hi' => $this->toHindiAddress($hospital->address_line2),
            'state' => $hospital->state,
            'state_hi' => $this->toHindiState($hospital->state),
            'pincode' => $hospital->pincode,
            'address' => $hospital->display_address,
            'address_hi' => $this->toHindiAddress($hospital->display_address),
        ];

        return view('hospitals.doctors', [
            'hospital' => $hospitalData,
            'doctors' => $doctors,
        ]);
    }

    private function buildHindiHospitalName(Hospital $hospital): string
    {
        $city = (string)($hospital->city ?? 'Jaipur');
        $cityHi = $this->toHindiCity($city);
        return HealthcareSyncService::getHindiHospitalName((string)$hospital->name_en, $city, $cityHi);
    }

    private function toHindiAddress(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        if (class_exists(\Transliterator::class)) {
            $trans = \Transliterator::create('Any-Devanagari');
            if ($trans) {
                return $trans->transliterate($value);
            }
        }

        return $value;
    }

    private function toHindiCity(?string $city): ?string
    {
        if (empty($city)) {
            return $city;
        }

        return HealthcareSyncService::getHindiCityName($city);
    }

    private function toHindiState(?string $state): ?string
    {
        if (empty($state)) {
            return $state;
        }

        $stateMap = [
            'Rajasthan' => 'राजस्थान',
            'Delhi' => 'दिल्ली',
            'Maharashtra' => 'महाराष्ट्र',
            'Karnataka' => 'कर्नाटक',
            'Telangana' => 'तेलंगाना',
            'Tamil Nadu' => 'तमिल नाडु',
            'Gujarat' => 'गुजरात',
            'West Bengal' => 'पश्चिम बंगाल',
        ];

        return $stateMap[$state] ?? $this->toHindiAddress($state);
    }
}
