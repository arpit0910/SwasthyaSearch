<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $userLat = $request->filled('user_lat') ? (float)$request->input('user_lat') : null;
        $userLng = $request->filled('user_lng') ? (float)$request->input('user_lng') : null;
        $hasUserLocation = is_numeric($userLat) && is_numeric($userLng);

        $query = Doctor::with(['department', 'hospitals'])->where('is_verified', true)->latest();

        // Filter by Department
        if ($request->filled('department') && $request->department !== 'All') {
            $dept = $request->department;
            $query->whereHas('department', function ($q) use ($dept) {
                $q->where('name_en', 'LIKE', "%{$dept}%")
                    ->orWhere('name_hi', 'LIKE', "%{$dept}%")
                    ->orWhere('id', $dept);
            });
        }

        // Filter by Experience Years
        if ($request->filled('experience') && $request->experience !== 'All') {
            $exp = (int) $request->experience;
            $query->where('experience_years', '>=', $exp);
        }

        // Filter by City
        if ($request->filled('city') && $request->city !== 'All') {
            $city = $request->city;
            $query->whereHas('hospitals', function ($q) use ($city) {
                $q->where('city', $city);
            });
        }

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
        $cities = Hospital::where('is_verified', true)->whereNotNull('city')->distinct()->pluck('city');

        return view('doctors.index', [
            'doctors' => $query
                ->paginate(30)
                ->withQueryString()
                ->through(fn(Doctor $doctor) => $this->formatDoctor($doctor, $userLat, $userLng)),
            'departments' => $departments->map(fn(Department $department) => $this->formatDepartment($department)),
            'cities' => $cities,
            'filters' => $request->only(['department', 'experience', 'city', 'search', 'user_lat', 'user_lng']),
            'hasUserLocation' => $hasUserLocation,
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
            'phone' => $doctor->phone ?: $doctor->hospitals->first()?->emergency_phone,
            'website' => $doctor->website && !str_contains($doctor->website, 'swasthyasearch.com') ? $doctor->website : null,
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
}
