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
            'doctors' => $query->get()->map(fn(Doctor $doctor) => $this->formatDoctor($doctor)),
            'departments' => $departments->map(fn(Department $department) => $this->formatDepartment($department)),
            'cities' => $cities,
            'filters' => $request->only(['department', 'experience', 'city', 'search']),
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
        ];
    }

    private function formatDoctor(Doctor $doctor): array
    {
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
            'phone' => $doctor->phone ?: ($doctor->hospitals->first()?->emergency_phone ?: '+91-141-2345678'),
            'website' => $doctor->website && !str_contains($doctor->website, 'swasthyasearch.com') ? $doctor->website : null,
            'gender' => $doctor->gender,
            'languages_spoken' => $doctor->languages_spoken ?: ['English', 'Hindi'],
            'consultation_fee' => $doctor->consultation_fee ?: ($doctor->hospitals->first()?->pivot?->consultation_fee ?: 500),
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
            'hospitals' => $doctor->hospitals->map(fn(Hospital $hospital) => [
                'id' => $hospital->id,
                'name' => [
                    'en' => $hospital->name_en,
                    'hi' => $hospital->name_hi,
                ],
                'type' => $hospital->type,
                'address' => $hospital->address ?: 'Jaipur, Rajasthan',
                'address_line1' => $hospital->address_line1,
                'address_line2' => $hospital->address_line2,
                'state' => $hospital->state,
                'pincode' => $hospital->pincode,
                'city' => $hospital->city ?: 'Jaipur',
                'latitude' => $hospital->latitude ?: 26.9124,
                'longitude' => $hospital->longitude ?: 75.7873,
                'emergency_phone' => $hospital->emergency_phone ?: '+91-141-2345678',
                'is_verified' => $hospital->is_verified,
                'accepts_ayushman' => $hospital->accepts_ayushman,
                'accepts_janaadhaar' => $hospital->accepts_janaadhaar,
                'accepts_cghs' => $hospital->accepts_cghs,
                'is_cashless' => $hospital->is_cashless,
                'cashless_schemes_list' => $hospital->cashless_schemes_list,
                'pivot' => $hospital->pivot,
            ]),
        ];
    }
}
