<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $activeCity = config('healthcare.active_city', 'Jaipur');
        $userLat = $request->filled('user_lat') ? (float)$request->input('user_lat') : null;
        $userLng = $request->filled('user_lng') ? (float)$request->input('user_lng') : null;
        $hasUserLocation = is_numeric($userLat) && is_numeric($userLng);

        $query = Doctor::with(['department', 'hospitals'])
            ->where('is_verified', true)
            ->whereHas('hospitals', fn($hospitalQuery) => $hospitalQuery->where('city', 'LIKE', "%{$activeCity}%"));

        // Treat nearby as the base filter: first limit to nearby doctors, then apply other filters.
        if ($hasUserLocation) {
            $allDoctorsMapped = Doctor::with('hospitals')
                ->where('is_verified', true)
                ->whereHas('hospitals', fn($hospitalQuery) => $hospitalQuery->where('city', 'LIKE', "%{$activeCity}%"))
                ->get()
                ->map(function (Doctor $doctor) use ($userLat, $userLng) {
                    $primaryHospital = $doctor->hospitals->first();
                    $distanceKm = $this->calculateDistanceKm(
                        $userLat,
                        $userLng,
                        $doctor->latitude ?? $primaryHospital?->latitude,
                        $doctor->longitude ?? $primaryHospital?->longitude
                    );

                    return [
                        'id' => $doctor->id,
                        'distance_km' => $distanceKm,
                    ];
                });

            $nearbyDoctorIds = $allDoctorsMapped
                ->filter(fn(array $doctor) => $doctor['distance_km'] !== null && $doctor['distance_km'] < 50)
                ->sortBy(fn(array $doctor) => $doctor['distance_km'])
                ->pluck('id')
                ->values();

            if ($nearbyDoctorIds->isEmpty()) {
                $nearbyDoctorIds = $allDoctorsMapped
                    ->sortBy(fn(array $doctor) => $doctor['distance_km'] ?? PHP_FLOAT_MAX)
                    ->pluck('id')
                    ->values();
            }

            if ($nearbyDoctorIds->isEmpty()) {
                $doctors = $this->paginateCollection(collect(), $request, 30);

                $nameColumn = app()->getLocale() === 'hi' ? 'name_hi' : 'name_en';
                $departments = Department::where('is_active', true)
                    ->whereNotNull('name_en')
                    ->where('name_en', '!=', '')
                    ->whereHas('doctors')
                    ->orderBy($nameColumn)
                    ->get();
                return view('doctors.index', [
                    'doctors' => $doctors,
                    'departments' => $departments->map(fn(Department $department) => $this->formatDepartment($department)),
                    'cities' => collect([$activeCity]),
                    'filters' => array_merge($request->only(['department', 'experience', 'search', 'user_lat', 'user_lng']), ['city' => [$activeCity]]),
                    'hasUserLocation' => $hasUserLocation,
                    'activeCity' => $activeCity,
                ]);
            }

            $query->whereIn('id', $nearbyDoctorIds->all());
        }

        // Filter by Department - support multiple selections
        $departments = $request->input('department', []);
        if (!is_array($departments)) {
            $departments = ($departments && $departments !== 'All') ? [$departments] : [];
        }
        $departments = array_filter($departments); // Remove empty values

        if (!empty($departments)) {
            $query->where(function ($q) use ($departments) {
                foreach ($departments as $dept) {
                    $q->orWhereHas('department', function ($sq) use ($dept) {
                        $sq->where('departments.name_en', 'LIKE', "%{$dept}%")
                            ->orWhere('departments.name_hi', 'LIKE', "%{$dept}%")
                            ->orWhere('departments.id', $dept);
                    })->orWhereHas('departments', function ($sq) use ($dept) {
                        $sq->where('departments.name_en', 'LIKE', "%{$dept}%")
                            ->orWhere('departments.name_hi', 'LIKE', "%{$dept}%")
                            ->orWhere('departments.id', $dept);
                    });
                }
            });
        }

        // Filter by Experience Years - support multiple selections
        $experiences = $request->input('experience', []);
        if (!is_array($experiences)) {
            $experiences = ($experiences && $experiences !== 'All') ? [$experiences] : [];
        }
        $experiences = array_filter($experiences); // Remove empty values

        if (!empty($experiences)) {
            $experiences = array_map('intval', $experiences);
            $query->where(function ($q) use ($experiences) {
                foreach ($experiences as $exp) {
                    $q->orWhere('experience_years', '>=', $exp);
                }
            });
        }

        // Filter by City - support multiple selections
        // Filter by Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('about_en', 'LIKE', "%{$search}%")
                    ->orWhere('about_hi', 'LIKE', "%{$search}%");
            });
        }

        $nameColumn = app()->getLocale() === 'hi' ? 'name_hi' : 'name_en';
        $departments = Department::where('is_active', true)
            ->whereNotNull('name_en')
            ->where('name_en', '!=', '')
            ->whereHas('doctors')
            ->orderBy($nameColumn)
            ->get();
        if ($hasUserLocation) {
            $doctors = $query
                ->latest()
                ->get()
                ->map(fn(Doctor $doctor) => $this->formatDoctor($doctor, $userLat, $userLng))
                ->sortBy(fn(array $doctor) => $doctor['distance_km'] ?? PHP_FLOAT_MAX)
                ->values();

            $doctors = $this->paginateCollection($doctors, $request, 30);
        } else {
            $doctors = $query
                ->latest()
                ->paginate(30)
                ->through(fn(Doctor $doctor) => $this->formatDoctor($doctor, $userLat, $userLng));
        }

        return view('doctors.index', [
            'doctors' => $doctors,
            'departments' => $departments->map(fn(Department $department) => $this->formatDepartment($department)),
            'cities' => collect([$activeCity]),
            'filters' => array_merge($request->only(['department', 'experience', 'search', 'user_lat', 'user_lng']), ['city' => [$activeCity]]),
            'hasUserLocation' => $hasUserLocation,
            'activeCity' => $activeCity,
        ]);
    }

    private function formatDepartment(Department $department): array
    {
        return [
            'id' => $department->id,
            'name' => [
                'en' => $department->name_en,
                'hi' => $department->name_hi,
            ],
            'description' => [
                'en' => $department->description_en,
                'hi' => $department->description_hi,
            ],
        ];
    }

    private function formatDoctor(Doctor $doctor, ?float $userLat = null, ?float $userLng = null): array
    {
        $primaryHospital = $doctor->hospitals->first();
        $distanceKm = $this->calculateDistanceKm(
            $userLat,
            $userLng,
            $doctor->latitude ?? $primaryHospital?->latitude,
            $doctor->longitude ?? $primaryHospital?->longitude
        );

        return [
            'id' => $doctor->id,
            'first_name' => $doctor->first_name,
            'last_name' => $doctor->last_name,
            'department' => $doctor->department ? $this->formatDepartment($doctor->department) : null,
            'registration_number' => $doctor->registration_number,
            'medical_council' => $doctor->medical_council,
            'education_degrees' => $doctor->education_degrees,
            'experience_years' => $doctor->experience_years,
            'about' => [
                'en' => $doctor->about_en,
                'hi' => $doctor->about_hi,
            ],
            'is_verified' => $doctor->is_verified,
            'email' => $doctor->email,
            'phone_1' => $doctor->phone_1 ?: $doctor->hospitals->first()?->phone_1,
            'phone_2' => $doctor->phone_2 ?: $doctor->hospitals->first()?->phone_2,
            'country_code_1' => $doctor->country_code_1,
            'country_code_2' => $doctor->country_code_2,
            'website' => $doctor->website && !str_contains($doctor->website, 'arogio.com') ? $doctor->website : null,
            'gender' => $doctor->gender,
            'languages_spoken' => $doctor->languages_spoken ?: [],
            'consultation_fee' => $doctor->consultation_fee ?: $doctor->hospitals->first()?->pivot?->consultation_fee,
            'specialization_summary' => $doctor->specialization_summary,
            'awards_recognitions' => $doctor->awards_recognitions ?: [],
            'membership_fellowships' => $doctor->membership_fellowships ?: [],
            'address_line1' => $doctor->address_line1,
            'address_line2' => $doctor->address_line2,
            'city' => $doctor->city,
            'state' => $doctor->state,
            'pincode' => $doctor->pincode,
            'latitude' => $doctor->latitude,
            'longitude' => $doctor->longitude,
            'distance_km' => $distanceKm,
            'hospitals' => $doctor->hospitals->map(fn(Hospital $hospital) => [
                'id' => $hospital->id,
                'name' => [
                    'en' => $hospital->name_en,
                    'hi' => $hospital->name_hi,
                ],
                'type' => $hospital->type,
                'address' => $hospital->address,
                'address_line1' => $hospital->address_line1,
                'address_line2' => $hospital->address_line2,
                'display_address' => $hospital->display_address,
                'state' => $hospital->state,
                'pincode' => $hospital->pincode,
                'city' => $hospital->city,
                'latitude' => $hospital->latitude,
                'longitude' => $hospital->longitude,
                'map_directions_url' => $hospital->map_directions_url,
                'phone_1' => $hospital->phone_1,
                'phone_2' => $hospital->phone_2,
                'country_code_1' => $hospital->country_code_1,
                'country_code_2' => $hospital->country_code_2,
                'is_verified' => $hospital->is_verified,
                'accepts_ayushman' => $hospital->accepts_ayushman,
                'accepts_janaadhaar' => $hospital->accepts_janaadhaar,
                'accepts_cghs' => $hospital->accepts_cghs,
                'accepts_esic' => $hospital->accepts_esic,
                'is_cashless' => $hospital->is_cashless,
                'cashless_schemes_list' => $hospital->cashless_schemes_list,
                'distance_km' => $this->calculateDistanceKm($userLat, $userLng, $hospital->latitude, $hospital->longitude),
                'pivot' => $hospital->pivot,
            ]),
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
}
