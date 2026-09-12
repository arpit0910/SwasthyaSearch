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
        $cityInput = $request->input('city', config('healthcare.active_city', 'Jaipur'));
        $activeCity = trim((string) (is_array($cityInput) ? ($cityInput[0] ?? '') : $cityInput));
        $activeCity = $activeCity ?: config('healthcare.active_city', 'Jaipur');
        $list = fn ($key) => array_values(array_filter((array) $request->input($key, []), fn ($v) => is_scalar($v) && $v !== '' && $v !== 'All'));
        $departmentFilter = $list('department');
        $typeFilter = $list('type');
        $benefitFilter = $list('benefit');
        $experience = max(0, min(60, (int) (collect($list('experience'))->min() ?? 0)));
        $userLat = is_numeric($request->input('user_lat')) ? (float) $request->input('user_lat') : null;
        $userLng = is_numeric($request->input('user_lng')) ? (float) $request->input('user_lng') : null;
        $hasUserLocation = $userLat !== null && $userLng !== null && abs($userLat) <= 90 && abs($userLng) <= 180;
        if (! $hasUserLocation) { $userLat = null; $userLng = null; }
        $perPage = in_array((int) $request->input('per_page'), [10, 20, 50], true) ? (int) $request->input('per_page') : 10;
        $sort = in_array($request->input('sort'), ['name', 'experience', 'nearest', 'latest'], true) ? $request->input('sort') : 'experience';
        $search = trim((string) $request->input('search', ''));

        $query = Doctor::with(['department', 'hospitals' => fn ($q) => $q->whereRaw('LOWER(TRIM(city)) = ?', [mb_strtolower($activeCity)])])
            ->where('is_verified', true)
            ->whereHas('hospitals', function ($q) use ($activeCity, $typeFilter, $benefitFilter) {
                $q->whereRaw('LOWER(TRIM(city)) = ?', [mb_strtolower($activeCity)]);
                if ($typeFilter) $q->whereIn('type', $typeFilter);
                $benefits = ['esic' => 'accepts_esic', 'cghs' => 'accepts_cghs', 'ayushman' => 'accepts_ayushman', 'janaadhaar' => 'accepts_janaadhaar', 'cashless' => 'is_cashless'];
                foreach ($benefitFilter as $benefit) if (isset($benefits[$benefit])) $q->where($benefits[$benefit], true);
            });
        if ($departmentFilter) {
            $query->where(function ($q) use ($departmentFilter) {
                foreach ($departmentFilter as $department) {
                    $match = fn ($sq) => $sq->where('departments.id', $department)->orWhere('departments.name_en', 'like', "%{$department}%")->orWhere('departments.name_hi', 'like', "%{$department}%");
                    $q->orWhereHas('department', $match)->orWhereHas('departments', $match);
                }
            });
        }
        if ($experience) $query->where('experience_years', '>=', $experience);
        if ($search !== '') {
            $query->where(fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('education_degrees', 'like', "%{$search}%")
                ->orWhere('specialization_summary', 'like', "%{$search}%")
                ->orWhereHas('department', fn ($d) => $d->where('name_en', 'like', "%{$search}%")->orWhere('name_hi', 'like', "%{$search}%"))
                ->orWhereHas('hospitals', fn ($h) => $h->where('name_en', 'like', "%{$search}%")->orWhere('name_hi', 'like', "%{$search}%")));
        }
        if ($sort === 'name') $query->orderBy('first_name')->orderBy('last_name');
        elseif ($sort === 'experience') $query->orderByDesc('experience_years');
        else $query->latest();
        if ($hasUserLocation) {
            $items = $query->get()->map(fn (Doctor $doctor) => $this->formatDoctor($doctor, $userLat, $userLng))
                ->filter(fn ($doctor) => $doctor['distance_km'] !== null && $doctor['distance_km'] <= 50)
                ->sortBy('distance_km')->values();
            $doctors = $this->paginateCollection($items, $request, $perPage);
        } else {
            $doctors = $query->paginate($perPage)->withQueryString()->through(fn (Doctor $doctor) => $this->formatDoctor($doctor));
        }
        $departments = Department::where('is_active', true)->orderBy(app()->getLocale() === 'hi' ? 'name_hi' : 'name_en')->get();
        return view('doctors.index', [
            'doctors' => $doctors,
            'departments' => $departments->map(fn (Department $department) => $this->formatDepartment($department)),
            'cities' => Hospital::whereNotNull('city')->distinct()->orderBy('city')->pluck('city')->push($activeCity)->unique()->values(),
            'types' => Hospital::whereNotNull('type')->distinct()->orderBy('type')->pluck('type'),
            'filters' => ['city' => [$activeCity], 'department' => $departmentFilter, 'experience' => [$experience], 'type' => $typeFilter, 'benefit' => $benefitFilter, 'search' => $search, 'sort' => $sort, 'per_page' => $perPage, 'user_lat' => $userLat, 'user_lng' => $userLng],
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
