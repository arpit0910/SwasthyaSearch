<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\HealthcareSyncService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $userLat = $request->filled('user_lat') ? (float)$request->input('user_lat') : null;
        $userLng = $request->filled('user_lng') ? (float)$request->input('user_lng') : null;
        $hasUserLocation = is_numeric($userLat) && is_numeric($userLng);

        $query = Hospital::where('is_verified', true);

        // Nearby-first behavior: limit to nearby hospitals first, then apply all other filters.
        if ($hasUserLocation) {
            $nearbyHospitalIds = Hospital::where('is_verified', true)
                ->get()
                ->map(function (Hospital $hospital) use ($userLat, $userLng) {
                    return [
                        'id' => $hospital->id,
                        'distance_km' => $this->calculateDistanceKm($userLat, $userLng, $hospital->latitude, $hospital->longitude),
                    ];
                })
                ->filter(fn(array $hospital) => $hospital['distance_km'] !== null && $hospital['distance_km'] < 50)
                ->sortBy('distance_km')
                ->pluck('id')
                ->values();

            if ($nearbyHospitalIds->isEmpty()) {
                $hospitals = $this->paginateCollection(collect(), $request, 30);
                $cities = Hospital::where('is_verified', true)->whereNotNull('city')->distinct()->pluck('city');
                $types = Hospital::where('is_verified', true)->whereNotNull('type')->distinct()->pluck('type');

                return view('hospitals.index', [
                    'hospitals' => $hospitals,
                    'cities' => $cities,
                    'types' => $types,
                    'filters' => $request->only(['type', 'city', 'search', 'benefit', 'user_lat', 'user_lng']),
                    'hasUserLocation' => $hasUserLocation,
                ]);
            }

            $query->whereIn('id', $nearbyHospitalIds->all());
        }

        // Filter by Type (Hospital vs Clinic) - support multiple selections
        $types = $request->input('type', []);
        if (!is_array($types)) {
            $types = ($types && $types !== 'All') ? [$types] : [];
        }
        $types = array_filter($types); // Remove empty values
        
        if (!empty($types)) {
            $query->whereIn('type', $types);
        }

        // Filter by City - support multiple selections
        $cities = $request->input('city', []);
        if (!is_array($cities)) {
            $cities = ($cities && $cities !== 'All') ? [$cities] : [];
        }
        $cities = array_filter($cities); // Remove empty values
        
        if (!empty($cities)) {
            $query->whereIn('city', $cities);
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

        // Filter by Benefit - support multiple selections
        $benefits = $request->input('benefit', []);
        if (!is_array($benefits)) {
            $benefits = ($benefits && $benefits !== 'All') ? [$benefits] : [];
        }
        $benefits = array_filter($benefits); // Remove empty values

        if (!empty($benefits)) {
            $query->where(function ($q) use ($benefits) {
                foreach ($benefits as $benefit) {
                    if ($benefit === 'ayushman') {
                        $q->orWhere('accepts_ayushman', true);
                    } elseif ($benefit === 'janaadhaar') {
                        $q->orWhere('accepts_janaadhaar', true);
                    } elseif ($benefit === 'cghs') {
                        $q->orWhere('accepts_cghs', true);
                    } elseif ($benefit === 'cashless') {
                        $q->orWhere('is_cashless', true);
                    }
                }
            });
        }

        $cities = Hospital::where('is_verified', true)->whereNotNull('city')->distinct()->pluck('city');
        $types = Hospital::where('is_verified', true)->whereNotNull('type')->distinct()->pluck('type');

        // If user location provided, keep distance sorting after applying all other filters.
        if ($hasUserLocation) {
            $hospitals = $query
                ->latest()
                ->get()
                ->map(fn(Hospital $hospital) => $this->formatHospital($hospital, $userLat, $userLng))
                ->sortBy('distance_km')
                ->values();

            $hospitals = $this->paginateCollection($hospitals, $request, 30);
        } else {
            // Original formatting for non-location queries
            $hospitals = $query
                ->latest()
                ->paginate(30)
                ->through(fn(Hospital $hospital) => $this->formatHospital($hospital, null, null));
        }

        return view('hospitals.index', [
            'hospitals' => $hospitals,
            'cities' => $cities,
            'types' => $types,
            'filters' => $request->only(['type', 'city', 'search', 'benefit', 'user_lat', 'user_lng']),
            'hasUserLocation' => $hasUserLocation,
        ]);
    }

    private function formatHospital(Hospital $hospital, ?float $userLat = null, ?float $userLng = null): array
    {
        return [
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
            'phone_1' => $hospital->phone_1,
            'phone_2' => $hospital->phone_2,
            'country_code_1' => $hospital->country_code_1,
            'country_code_2' => $hospital->country_code_2,
            'is_verified' => $hospital->is_verified,
            'accepts_ayushman' => $hospital->accepts_ayushman,
            'accepts_janaadhaar' => $hospital->accepts_janaadhaar,
            'accepts_cghs' => $hospital->accepts_cghs,
            'is_cashless' => $hospital->is_cashless,
            'cashless_schemes_list' => $hospital->cashless_schemes_list,
            'distance_km' => $this->calculateDistanceKm($userLat, $userLng, $hospital->latitude, $hospital->longitude),
        ];
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

    private function paginateCollection($items, Request $request, int $perPage = 30): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($items);
        $slice = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
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
                    'phone_1' => $doctor->phone_1,
                    'phone_2' => $doctor->phone_2,
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
            'phone_1' => $hospital->phone_1,
            'phone_2' => $hospital->phone_2,
            'country_code_1' => $hospital->country_code_1,
            'country_code_2' => $hospital->country_code_2,
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
