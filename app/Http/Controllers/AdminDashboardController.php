<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\BloodBank;
use App\Models\CachedMedicalQuestion;
use App\Models\Department;
use App\Models\DirectorySyncHistory;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\Hospital;
use App\Models\Symptom;
use App\Services\DirectorySyncService;
use App\Services\ScraperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'verified_doctors' => Doctor::where('is_verified', true)->count(),
            'verified_hospitals' => Hospital::where('is_verified', true)->count(),
            'verified_blood_banks' => BloodBank::where('is_verified', true)->count(),
            'articles_count' => Article::count(),
            'active_departments' => Department::where('is_active', true)->count(),
        ];

        $departments = Department::withCount('doctors')->get();
        $chartData = [
            'labels' => $departments->pluck('name_en')->toArray(),
            'data' => $departments->pluck('doctors_count')->toArray(),
        ];

        $supportedCities = ScraperService::getSupportedCities();
        $syncHistory = DirectorySyncHistory::query()
            ->latest('id')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'supportedCities', 'syncHistory'));
    }

    // --- HOSPITALS CRUD & IMPORT ---
    public function hospitals(Request $request)
    {
        $query = Hospital::query();
        if ($search = $request->query('search')) {
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_hi', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        }
        $hospitals = $query->latest()->get();
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function storeHospital(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'type' => 'required|string',
            'city' => 'required|string',
            'address' => 'nullable|string',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'country_code_1' => 'nullable|string|max:10',
            'phone_1' => 'required|string',
            'emergency_country_code' => 'nullable|string|max:10',
            'emergency_phone' => 'nullable|string',
            'is_verified' => 'boolean',
            'accepts_ayushman' => 'boolean',
            'accepts_janaadhaar' => 'boolean',
            'accepts_cghs' => 'boolean',
            'is_cashless' => 'boolean',
            'cashless_schemes_list' => 'nullable|string',
        ]);

        $schemesList = !empty($data['cashless_schemes_list']) ? array_map('trim', explode(',', $data['cashless_schemes_list'])) : null;
        $phoneInput = $data['phone_1'] ?? ($data['emergency_phone'] ?? null);
        $countryCodeInput = $data['country_code_1'] ?? ($data['emergency_country_code'] ?? '+91');
        $phoneParts = \App\Services\HealthcareSyncService::splitPhone(trim($countryCodeInput . ' ' . ($phoneInput ?? '')));

        Hospital::create([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'type' => $data['type'],
            'city' => $data['city'],
            'address' => null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'landmark' => $data['landmark'] ?? null,
            'state' => $data['state'] ?? 'Rajasthan',
            'pincode' => $data['pincode'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'country_code_1' => $phoneParts['country_code'],
            'phone_1' => $phoneParts['phone'],
            'is_verified' => $request->boolean('is_verified', true),
            'accepts_ayushman' => $request->boolean('accepts_ayushman', false),
            'accepts_janaadhaar' => $request->boolean('accepts_janaadhaar', false),
            'accepts_cghs' => $request->boolean('accepts_cghs', false),
            'is_cashless' => $request->boolean('is_cashless', false),
            'cashless_schemes_list' => $schemesList,
        ]);

        return back()->with('success', 'Hospital created successfully.');
    }

    public function updateHospital(Request $request, Hospital $hospital)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'type' => 'required|string',
            'city' => 'required|string',
            'address' => 'nullable|string',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'country_code_1' => 'nullable|string|max:10',
            'phone_1' => 'required|string',
            'emergency_country_code' => 'nullable|string|max:10',
            'emergency_phone' => 'nullable|string',
            'is_verified' => 'boolean',
            'accepts_ayushman' => 'boolean',
            'accepts_janaadhaar' => 'boolean',
            'accepts_cghs' => 'boolean',
            'is_cashless' => 'boolean',
            'cashless_schemes_list' => 'nullable|string',
        ]);

        $schemesList = !empty($data['cashless_schemes_list']) ? array_map('trim', explode(',', $data['cashless_schemes_list'])) : null;
        $phoneInput = $data['phone_1'] ?? ($data['emergency_phone'] ?? null);
        $countryCodeInput = $data['country_code_1'] ?? ($data['emergency_country_code'] ?? '+91');
        $phoneParts = \App\Services\HealthcareSyncService::splitPhone(trim($countryCodeInput . ' ' . ($phoneInput ?? '')));

        $hospital->update([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'type' => $data['type'],
            'city' => $data['city'],
            'address' => null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'landmark' => $data['landmark'] ?? null,
            'state' => $data['state'] ?? 'Rajasthan',
            'pincode' => $data['pincode'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'country_code_1' => $phoneParts['country_code'],
            'phone_1' => $phoneParts['phone'],
            'is_verified' => $request->boolean('is_verified', true),
            'accepts_ayushman' => $request->boolean('accepts_ayushman', false),
            'accepts_janaadhaar' => $request->boolean('accepts_janaadhaar', false),
            'accepts_cghs' => $request->boolean('accepts_cghs', false),
            'is_cashless' => $request->boolean('is_cashless', false),
            'cashless_schemes_list' => $schemesList,
        ]);

        return back()->with('success', 'Hospital updated successfully.');
    }

    public function destroyHospital(Hospital $hospital)
    {
        $hospital->delete();
        return back()->with('success', 'Hospital deleted successfully.');
    }

    public function exportHospitals()
    {
        $hospitals = Hospital::all();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=hospitals_export.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($hospitals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'id',
                'name_en',
                'name_hi',
                'type',
                'city',
                'state',
                'pincode',
                'address_line1',
                'address_line2',
                'landmark',
                'country_code_1',
                'country_code_2',
                'phone_1',
                'phone_2',
                'is_verified',
                'accepts_ayushman',
                'accepts_janaadhaar',
                'accepts_cghs',
                'is_cashless',
                'cashless_schemes_list',
                'latitude',
                'longitude'
            ]);

            foreach ($hospitals as $hospital) {
                fputcsv($file, [
                    $hospital->id,
                    $hospital->getTranslation('name', 'en', false) ?: $hospital->name_en,
                    $hospital->getTranslation('name', 'hi', false) ?: $hospital->name_hi,
                    $hospital->type,
                    $hospital->city,
                    $hospital->state,
                    $hospital->pincode,
                    $hospital->address_line1,
                    $hospital->address_line2,
                    $hospital->landmark,
                    $hospital->country_code_1,
                    $hospital->country_code_2,
                    $hospital->phone_1,
                    $hospital->phone_2,
                    $hospital->is_verified ? 1 : 0,
                    $hospital->accepts_ayushman ? 1 : 0,
                    $hospital->accepts_janaadhaar ? 1 : 0,
                    $hospital->accepts_cghs ? 1 : 0,
                    $hospital->is_cashless ? 1 : 0,
                    is_array($hospital->cashless_schemes_list) ? implode(';', $hospital->cashless_schemes_list) : $hospital->cashless_schemes_list,
                    $hospital->latitude,
                    $hospital->longitude,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importHospitals(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            if (count($header) !== count($row)) continue;
            $data = array_combine($header, $row);
            if (empty($data['name_en'])) continue;

            $hospital = !empty($data['id']) ? Hospital::find($data['id']) : Hospital::where('name_en', $data['name_en'])->where('city', $data['city'] ?? 'Jaipur')->first();

            $phone1 = $data['phone_1'] ?? ($data['emergency_phone_1'] ?? ($data['phone'] ?? ($data['emergency_phone'] ?? null)));
            $phone2 = $data['phone_2'] ?? ($data['emergency_phone_2'] ?? null);
            $countryCode1 = $data['country_code_1'] ?? ($data['emergency_country_code_1'] ?? ($data['emergency_country_code'] ?? '+91'));
            $countryCode2 = $data['country_code_2'] ?? ($data['emergency_country_code_2'] ?? null);

            if ($phone2 === null && is_string($data['emergency_phone'] ?? null) && str_contains($data['emergency_phone'], ',')) {
                [$phone1, $phone2] = array_pad(array_map('trim', explode(',', $data['emergency_phone'], 2)), 2, null);
            }
            if ($countryCode2 === null && is_string($data['emergency_country_code'] ?? null) && str_contains($data['emergency_country_code'], ',')) {
                [$countryCode1, $countryCode2] = array_pad(array_map('trim', explode(',', $data['emergency_country_code'], 2)), 2, null);
            }
            if ($phone2 === null && is_string($phone1) && str_contains($phone1, ',')) {
                [$phone1, $phone2] = array_pad(array_map('trim', explode(',', $phone1, 2)), 2, null);
            }
            if ($countryCode2 === null && is_string($countryCode1) && str_contains($countryCode1, ',')) {
                [$countryCode1, $countryCode2] = array_pad(array_map('trim', explode(',', $countryCode1, 2)), 2, null);
            }

            $updateData = [
                'name_en' => $data['name_en'],
                'name_hi' => !empty($data['name_hi']) ? $data['name_hi'] : $data['name_en'],
                'type' => !empty($data['type']) ? $data['type'] : 'Hospital',
                'city' => !empty($data['city']) ? $data['city'] : 'Jaipur',
                'state' => !empty($data['state']) ? $data['state'] : 'Rajasthan',
                'pincode' => !empty($data['pincode']) ? $data['pincode'] : null,
                'address' => null,
                'address_line1' => !empty($data['address_line1']) ? $data['address_line1'] : null,
                'address_line2' => !empty($data['address_line2']) ? $data['address_line2'] : null,
                'landmark' => !empty($data['landmark']) ? $data['landmark'] : null,
                'phone_1' => $phone1,
                'phone_2' => $phone2,
                'country_code_1' => $countryCode1,
                'country_code_2' => $countryCode2,
                'latitude' => !empty($data['latitude']) ? (float)$data['latitude'] : null,
                'longitude' => !empty($data['longitude']) ? (float)$data['longitude'] : null,
                'is_verified' => isset($data['is_verified']) ? filter_var($data['is_verified'], FILTER_VALIDATE_BOOLEAN) : true,
                'accepts_ayushman' => isset($data['accepts_ayushman']) ? filter_var($data['accepts_ayushman'], FILTER_VALIDATE_BOOLEAN) : false,
                'accepts_janaadhaar' => isset($data['accepts_janaadhaar']) ? filter_var($data['accepts_janaadhaar'], FILTER_VALIDATE_BOOLEAN) : false,
                'accepts_cghs' => isset($data['accepts_cghs']) ? filter_var($data['accepts_cghs'], FILTER_VALIDATE_BOOLEAN) : false,
                'is_cashless' => isset($data['is_cashless']) ? filter_var($data['is_cashless'], FILTER_VALIDATE_BOOLEAN) : false,
                'cashless_schemes_list' => !empty($data['cashless_schemes_list']) ? array_map('trim', explode(';', $data['cashless_schemes_list'])) : null,
            ];

            if ($hospital) {
                $hospital->update($updateData);
            } else {
                Hospital::create($updateData);
            }
        }
        fclose($file);
        return back()->with('success', 'Hospitals imported & updated successfully.');
    }

    public function syncHospitals(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);
        $city = $request->city;
        $cacheKey = 'scrape_progress_hospitals';

        ScraperService::scrapeHospitals($city, false, null, $cacheKey);

        return response()->json(['status' => 'completed', 'message' => "Hospitals synchronized for {$city}."]);
    }

    public function syncHospitalsProgress()
    {
        $progress = Cache::get('scrape_progress_hospitals', [
            'status' => 'idle',
            'city' => '',
            'progress' => 0,
            'message' => 'Waiting to start...',
        ]);

        return response()->json($progress);
    }

    // --- DOCTORS CRUD & IMPORT ---
    public function doctors(Request $request)
    {
        $query = Doctor::with('departments');
        if ($search = $request->query('search')) {
            $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");
        }
        $doctors = $query->latest()->get();
        $departments = Department::all();
        return view('admin.doctors.index', compact('doctors', 'departments'));
    }

    public function storeDoctor(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
            'registration_number' => 'required|string',
            'experience_years' => 'required|integer',
            'about_en' => 'required|string',
            'about_hi' => 'required|string',
            'is_verified' => 'boolean',
            'email' => 'nullable|email|max:255',
            'country_code_1' => 'nullable|string|max:10',
            'phone_1' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'languages_spoken' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric',
            'specialization_summary' => 'nullable|string',
            'awards_recognitions' => 'nullable|string',
            'membership_fellowships' => 'nullable|string',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $phoneInput = $data['phone_1'] ?? ($data['phone'] ?? null);
        $countryCodeInput = $data['country_code_1'] ?? ($data['country_code'] ?? '+91');
        $phoneParts = \App\Services\HealthcareSyncService::splitPhone(trim($countryCodeInput . ' ' . ($phoneInput ?? '')));

        $doctor = Doctor::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'department_id' => !empty($data['departments']) ? $data['departments'][0] : null,
            'registration_number' => $data['registration_number'],
            'experience_years' => $data['experience_years'],
            'about_en' => $data['about_en'],
            'about_hi' => $data['about_hi'],
            'is_verified' => $request->boolean('is_verified', true),
            'email' => $data['email'] ?? null,
            'country_code_1' => $phoneParts['country_code'],
            'phone_1' => $phoneParts['phone'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'languages_spoken' => !empty($data['languages_spoken']) ? array_map('trim', explode(',', $data['languages_spoken'])) : null,
            'consultation_fee' => $data['consultation_fee'] ?? null,
            'specialization_summary' => $data['specialization_summary'] ?? null,
            'awards_recognitions' => !empty($data['awards_recognitions']) ? array_map('trim', explode(',', $data['awards_recognitions'])) : null,
            'membership_fellowships' => !empty($data['membership_fellowships']) ? array_map('trim', explode(',', $data['membership_fellowships'])) : null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'landmark' => $data['landmark'] ?? null,
            'city' => $data['city'] ?? 'Jaipur',
            'state' => $data['state'] ?? 'Rajasthan',
            'pincode' => $data['pincode'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        if (!empty($data['departments'])) {
            $doctor->departments()->attach($data['departments']);
        }

        return back()->with('success', 'Doctor created successfully.');
    }

    public function updateDoctor(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
            'registration_number' => 'required|string',
            'experience_years' => 'required|integer',
            'about_en' => 'required|string',
            'about_hi' => 'required|string',
            'is_verified' => 'boolean',
            'email' => 'nullable|email|max:255',
            'country_code_1' => 'nullable|string|max:10',
            'phone_1' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'languages_spoken' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric',
            'specialization_summary' => 'nullable|string',
            'awards_recognitions' => 'nullable|string',
            'membership_fellowships' => 'nullable|string',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $phoneInput = $data['phone_1'] ?? ($data['phone'] ?? null);
        $countryCodeInput = $data['country_code_1'] ?? ($data['country_code'] ?? '+91');
        $phoneParts = \App\Services\HealthcareSyncService::splitPhone(trim($countryCodeInput . ' ' . ($phoneInput ?? '')));

        $doctor->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'department_id' => !empty($data['departments']) ? $data['departments'][0] : null,
            'registration_number' => $data['registration_number'],
            'experience_years' => $data['experience_years'],
            'about_en' => $data['about_en'],
            'about_hi' => $data['about_hi'],
            'is_verified' => $request->boolean('is_verified', true),
            'email' => $data['email'] ?? null,
            'country_code_1' => $phoneParts['country_code'],
            'phone_1' => $phoneParts['phone'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'languages_spoken' => !empty($data['languages_spoken']) ? array_map('trim', explode(',', $data['languages_spoken'])) : null,
            'consultation_fee' => $data['consultation_fee'] ?? null,
            'specialization_summary' => $data['specialization_summary'] ?? null,
            'awards_recognitions' => !empty($data['awards_recognitions']) ? array_map('trim', explode(',', $data['awards_recognitions'])) : null,
            'membership_fellowships' => !empty($data['membership_fellowships']) ? array_map('trim', explode(',', $data['membership_fellowships'])) : null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'landmark' => $data['landmark'] ?? null,
            'city' => $data['city'] ?? 'Jaipur',
            'state' => $data['state'] ?? 'Rajasthan',
            'pincode' => $data['pincode'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        if (!empty($data['departments'])) {
            $doctor->departments()->sync($data['departments']);
        }

        return back()->with('success', 'Doctor updated successfully.');
    }

    public function destroyDoctor(Doctor $doctor)
    {
        $doctor->delete();
        return back()->with('success', 'Doctor deleted successfully.');
    }

    public function exportDoctors()
    {
        $doctors = Doctor::with('departments')->get();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=doctors_export.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($doctors) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'id',
                'registration_number',
                'first_name',
                'last_name',
                'department_name_en',
                'department_name_hi',
                'medical_council',
                'phone_1',
                'phone_2',
                'consultation_fee',
                'experience_years',
                'education_degrees',
                'about_en',
                'about_hi',
                'city',
                'state',
                'pincode',
                'address_line1',
                'address_line2',
                'landmark',
                'languages_spoken',
                'gender',
                'is_verified',
                'latitude',
                'longitude'
            ]);

            foreach ($doctors as $doctor) {
                $dept = $doctor->departments->first() ?? $doctor->department;
                fputcsv($file, [
                    $doctor->id,
                    $doctor->registration_number,
                    $doctor->first_name,
                    $doctor->last_name,
                    $dept ? ($dept->getTranslation('name', 'en', false) ?: $dept->name_en) : 'General Medicine',
                    $dept ? ($dept->getTranslation('name', 'hi', false) ?: $dept->name_hi) : 'सामान्य चिकित्सा',
                    $doctor->medical_council,
                    $doctor->phone_1,
                    $doctor->phone_2,
                    $doctor->consultation_fee,
                    $doctor->experience_years,
                    is_array($doctor->education_degrees) ? implode(';', $doctor->education_degrees) : $doctor->education_degrees,
                    $doctor->getTranslation('about', 'en', false) ?: $doctor->about_en,
                    $doctor->getTranslation('about', 'hi', false) ?: $doctor->about_hi,
                    $doctor->city,
                    $doctor->state,
                    $doctor->pincode,
                    $doctor->address_line1,
                    $doctor->address_line2,
                    $doctor->landmark,
                    is_array($doctor->languages_spoken) ? implode(';', $doctor->languages_spoken) : $doctor->languages_spoken,
                    $doctor->gender,
                    $doctor->is_verified ? 1 : 0,
                    $doctor->latitude,
                    $doctor->longitude,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importDoctors(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            if (count($header) !== count($row)) continue;
            $data = array_combine($header, $row);
            if (empty($data['first_name'])) continue;

            $doctor = !empty($data['id']) ? Doctor::find($data['id']) : Doctor::where('registration_number', $data['registration_number'] ?? '')->first();

            $deptNameEn = !empty($data['department_name_en']) ? $data['department_name_en'] : 'General Medicine';
            $dept = Department::where('name_en', 'like', "%{$deptNameEn}%")->first();
            if (!$dept) {
                $dept = Department::create([
                    'name_en' => $deptNameEn,
                    'name_hi' => !empty($data['department_name_hi']) ? $data['department_name_hi'] : $deptNameEn,
                    'description_en' => 'Imported department',
                    'description_hi' => 'Imported department',
                    'is_active' => true,
                ]);
            }

            $phone1 = $data['phone_1'] ?? ($data['phone'] ?? null);
            $phone2 = $data['phone_2'] ?? null;
            $countryCode1 = $data['country_code_1'] ?? ($data['country_code'] ?? '+91');
            $countryCode2 = $data['country_code_2'] ?? null;
            if ($phone2 === null && is_string($phone1) && str_contains($phone1, ',')) {
                [$phone1, $phone2] = array_pad(array_map('trim', explode(',', $phone1, 2)), 2, null);
            }
            if ($countryCode2 === null && is_string($countryCode1) && str_contains($countryCode1, ',')) {
                [$countryCode1, $countryCode2] = array_pad(array_map('trim', explode(',', $countryCode1, 2)), 2, null);
            }

            $updateData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? '',
                'registration_number' => !empty($data['registration_number']) ? $data['registration_number'] : null,
                'department_id' => $dept ? $dept->id : null,
                'medical_council' => !empty($data['medical_council']) ? $data['medical_council'] : null,
                'country_code_1' => $countryCode1,
                'country_code_2' => $countryCode2,
                'phone_1' => $phone1,
                'phone_2' => $phone2,
                'consultation_fee' => !empty($data['consultation_fee']) ? (float)$data['consultation_fee'] : null,
                'experience_years' => !empty($data['experience_years']) ? (int)$data['experience_years'] : null,
                'education_degrees' => !empty($data['education_degrees']) ? array_map('trim', explode(';', $data['education_degrees'])) : \App\Services\ScraperService::getRealDegreesForDepartment($deptNameEn),
                'about_en' => !empty($data['about_en']) ? $data['about_en'] : null,
                'about_hi' => !empty($data['about_hi']) ? $data['about_hi'] : null,
                'city' => !empty($data['city']) ? $data['city'] : 'Jaipur',
                'state' => !empty($data['state']) ? $data['state'] : 'Rajasthan',
                'pincode' => !empty($data['pincode']) ? $data['pincode'] : null,
                'address_line1' => !empty($data['address_line1']) ? $data['address_line1'] : null,
                'address_line2' => !empty($data['address_line2']) ? $data['address_line2'] : null,
                'landmark' => !empty($data['landmark']) ? $data['landmark'] : null,
                'languages_spoken' => !empty($data['languages_spoken']) ? array_map('trim', explode(';', $data['languages_spoken'])) : null,
                'gender' => !empty($data['gender']) ? $data['gender'] : null,
                'latitude' => !empty($data['latitude']) ? (float)$data['latitude'] : null,
                'longitude' => !empty($data['longitude']) ? (float)$data['longitude'] : null,
                'is_verified' => isset($data['is_verified']) ? filter_var($data['is_verified'], FILTER_VALIDATE_BOOLEAN) : true,
            ];

            if ($doctor) {
                $doctor->update($updateData);
            } else {
                $doctor = Doctor::create($updateData);
            }

            if ($dept) {
                $doctor->departments()->syncWithoutDetaching([$dept->id]);
            }
        }
        fclose($file);
        return back()->with('success', 'Doctors imported & updated successfully.');
    }

    public function syncDoctors(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);
        $city = $request->city;
        $cacheKey = 'scrape_progress_doctors';

        ScraperService::scrapeDoctors($city, false, null, $cacheKey);

        return response()->json(['status' => 'completed', 'message' => "Doctors synchronized for {$city}."]);
    }

    public function syncDoctorsProgress()
    {
        $progress = Cache::get('scrape_progress_doctors', [
            'status' => 'idle',
            'city' => '',
            'progress' => 0,
            'message' => 'Waiting to start...',
        ]);

        return response()->json($progress);
    }

    // --- BLOOD BANKS CRUD & IMPORT & SYNC ---
    public function bloodBanks(Request $request)
    {
        $query = \App\Models\BloodBank::query();
        if ($search = $request->query('search')) {
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_hi', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        }
        $bloodBanks = $query->latest()->get();
        return view('admin.blood_banks.index', compact('bloodBanks'));
    }

    public function storeBloodBank(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'city' => 'required|string',
            'address_en' => 'required|string',
            'address_hi' => 'required|string',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'landmark' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'emergency_country_code' => 'nullable|string|max:10',
            'emergency_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
            'is_24_7' => 'boolean',
            'is_government' => 'boolean',
            'component_facility' => 'boolean',
            'apheresis_facility' => 'boolean',
            'available_blood_groups' => 'nullable|array',
        ]);
        $phoneParts = \App\Services\HealthcareSyncService::splitPhone(
            trim(($data['country_code'] ?? '+91') . ' ' . ($data['phone'] ?? ''))
        );
        $emergencyPhoneParts = \App\Services\HealthcareSyncService::splitPhone(
            trim(($data['emergency_country_code'] ?? '+91') . ' ' . ($data['emergency_phone'] ?? ''))
        );

        \App\Models\BloodBank::create([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'city' => $data['city'],
            'address_en' => $data['address_en'],
            'address_hi' => $data['address_hi'],
            'state' => $data['state'] ?? 'Rajasthan',
            'pincode' => $data['pincode'] ?? null,
            'landmark' => $data['landmark'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'country_code' => $phoneParts['country_code'],
            'phone' => $phoneParts['phone'],
            'emergency_country_code' => $emergencyPhoneParts['country_code'],
            'emergency_phone' => $emergencyPhoneParts['phone'],
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'is_verified' => $request->boolean('is_verified', true),
            'is_24_7' => $request->boolean('is_24_7', true),
            'is_government' => $request->boolean('is_government', false),
            'component_facility' => $request->boolean('component_facility', true),
            'apheresis_facility' => $request->boolean('apheresis_facility', false),
            'available_blood_groups' => $data['available_blood_groups'] ?? ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            'last_updated_stock_at' => now(),
        ]);

        return back()->with('success', 'Blood Bank created successfully.');
    }

    public function updateBloodBank(Request $request, \App\Models\BloodBank $bloodBank)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'city' => 'required|string',
            'address_en' => 'required|string',
            'address_hi' => 'required|string',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'landmark' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'emergency_country_code' => 'nullable|string|max:10',
            'emergency_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
            'is_24_7' => 'boolean',
            'is_government' => 'boolean',
            'component_facility' => 'boolean',
            'apheresis_facility' => 'boolean',
            'available_blood_groups' => 'nullable|array',
        ]);
        $phoneParts = \App\Services\HealthcareSyncService::splitPhone(
            trim(($data['country_code'] ?? '+91') . ' ' . ($data['phone'] ?? ''))
        );
        $emergencyPhoneParts = \App\Services\HealthcareSyncService::splitPhone(
            trim(($data['emergency_country_code'] ?? '+91') . ' ' . ($data['emergency_phone'] ?? ''))
        );

        $bloodBank->update([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'city' => $data['city'],
            'address_en' => $data['address_en'],
            'address_hi' => $data['address_hi'],
            'state' => $data['state'] ?? 'Rajasthan',
            'pincode' => $data['pincode'] ?? null,
            'landmark' => $data['landmark'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'country_code' => $phoneParts['country_code'],
            'phone' => $phoneParts['phone'],
            'emergency_country_code' => $emergencyPhoneParts['country_code'],
            'emergency_phone' => $emergencyPhoneParts['phone'],
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'is_verified' => $request->boolean('is_verified', true),
            'is_24_7' => $request->boolean('is_24_7', true),
            'is_government' => $request->boolean('is_government', false),
            'component_facility' => $request->boolean('component_facility', true),
            'apheresis_facility' => $request->boolean('apheresis_facility', false),
            'available_blood_groups' => $data['available_blood_groups'] ?? ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            'last_updated_stock_at' => now(),
        ]);

        return back()->with('success', 'Blood Bank updated successfully.');
    }

    public function destroyBloodBank(\App\Models\BloodBank $bloodBank)
    {
        $bloodBank->delete();
        return back()->with('success', 'Blood Bank deleted successfully.');
    }

    public function exportBloodBanks()
    {
        $bloodBanks = \App\Models\BloodBank::all();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=blood_banks_export.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($bloodBanks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'id',
                'name_en',
                'name_hi',
                'city',
                'state',
                'pincode',
                'landmark',
                'address_en',
                'address_hi',
                'country_code',
                'phone',
                'emergency_country_code',
                'emergency_phone',
                'email',
                'website',
                'is_verified',
                'is_24_7',
                'is_government',
                'component_facility',
                'apheresis_facility',
                'available_blood_groups',
                'latitude',
                'longitude'
            ]);

            foreach ($bloodBanks as $bb) {
                fputcsv($file, [
                    $bb->id,
                    $bb->name_en,
                    $bb->name_hi,
                    $bb->city,
                    $bb->state,
                    $bb->pincode,
                    $bb->landmark,
                    $bb->address_en,
                    $bb->address_hi,
                    $bb->country_code,
                    $bb->phone,
                    $bb->emergency_country_code,
                    $bb->emergency_phone,
                    $bb->email,
                    $bb->website,
                    $bb->is_verified ? 1 : 0,
                    $bb->is_24_7 ? 1 : 0,
                    $bb->is_government ? 1 : 0,
                    $bb->component_facility ? 1 : 0,
                    $bb->apheresis_facility ? 1 : 0,
                    is_array($bb->available_blood_groups) ? implode(';', $bb->available_blood_groups) : '',
                    $bb->latitude,
                    $bb->longitude,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importBloodBanks(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            if (count($header) !== count($row)) continue;
            $data = array_combine($header, $row);
            if (empty($data['name_en'])) continue;

            $bb = !empty($data['id']) ? \App\Models\BloodBank::find($data['id']) : \App\Models\BloodBank::where('name_en', $data['name_en'])->where('city', $data['city'] ?? 'Jaipur')->first();

            $phoneParts = \App\Services\HealthcareSyncService::splitPhone(
                trim(($data['country_code'] ?? '+91') . ' ' . ($data['phone'] ?? ''))
            );
            $emergPhoneParts = \App\Services\HealthcareSyncService::splitPhone(
                trim(($data['emergency_country_code'] ?? '+91') . ' ' . ($data['emergency_phone'] ?? ''))
            );

            $updateData = [
                'name_en' => $data['name_en'],
                'name_hi' => !empty($data['name_hi']) ? $data['name_hi'] : $data['name_en'],
                'city' => !empty($data['city']) ? $data['city'] : 'Jaipur',
                'state' => !empty($data['state']) ? $data['state'] : 'Rajasthan',
                'pincode' => !empty($data['pincode']) ? $data['pincode'] : null,
                'landmark' => !empty($data['landmark']) ? $data['landmark'] : null,
                'address_en' => !empty($data['address_en']) ? $data['address_en'] : ($data['address'] ?? ''),
                'address_hi' => !empty($data['address_hi']) ? $data['address_hi'] : ($data['address'] ?? ''),
                'country_code' => $phoneParts['country_code'],
                'phone' => $phoneParts['phone'],
                'emergency_country_code' => $emergPhoneParts['country_code'],
                'emergency_phone' => $emergPhoneParts['phone'],
                'email' => !empty($data['email']) ? $data['email'] : null,
                'website' => !empty($data['website']) ? $data['website'] : null,
                'latitude' => !empty($data['latitude']) ? (float)$data['latitude'] : null,
                'longitude' => !empty($data['longitude']) ? (float)$data['longitude'] : null,
                'is_verified' => isset($data['is_verified']) ? filter_var($data['is_verified'], FILTER_VALIDATE_BOOLEAN) : true,
                'is_24_7' => isset($data['is_24_7']) ? filter_var($data['is_24_7'], FILTER_VALIDATE_BOOLEAN) : true,
                'is_government' => isset($data['is_government']) ? filter_var($data['is_government'], FILTER_VALIDATE_BOOLEAN) : false,
                'component_facility' => isset($data['component_facility']) ? filter_var($data['component_facility'], FILTER_VALIDATE_BOOLEAN) : true,
                'apheresis_facility' => isset($data['apheresis_facility']) ? filter_var($data['apheresis_facility'], FILTER_VALIDATE_BOOLEAN) : false,
                'available_blood_groups' => !empty($data['available_blood_groups']) ? array_map('trim', explode(';', $data['available_blood_groups'])) : ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
                'last_updated_stock_at' => now(),
            ];

            if ($bb) {
                $bb->update($updateData);
            } else {
                \App\Models\BloodBank::create($updateData);
            }
        }
        fclose($file);
        return back()->with('success', 'Blood Banks imported & updated successfully.');
    }

    public function syncBloodBanks(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);
        $city = $request->city;
        $cacheKey = 'scrape_progress_bloodbanks';

        ScraperService::scrapeBloodBanks($city, false, null, $cacheKey);

        return response()->json(['status' => 'completed', 'message' => "Blood Banks synchronized for {$city}."]);
    }

    public function syncBloodBanksProgress()
    {
        $progress = Cache::get('scrape_progress_bloodbanks', [
            'status' => 'idle',
            'city' => '',
            'progress' => 0,
            'message' => 'Waiting to start...',
        ]);

        return response()->json($progress);
    }

    public function syncDirectoryAll(Request $request, DirectorySyncService $directorySyncService)
    {
        $request->validate(['city' => 'required|string|max:255']);
        $city = trim($request->string('city')->value());
        $supportedCities = ScraperService::getSupportedCities();

        if (!in_array(ucwords(strtolower($city)), $supportedCities, true)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unsupported city. Supported cities: ' . implode(', ', $supportedCities),
            ], 422);
        }

        $result = $directorySyncService->syncCity($city, false, DirectorySyncService::PROGRESS_KEY);

        return response()->json($result, $result['status'] === 'failed' ? 500 : 200);
    }

    public function syncDirectoryAllProgress()
    {
        $progress = Cache::get(DirectorySyncService::PROGRESS_KEY, [
            'status' => 'idle',
            'city' => '',
            'progress' => 0,
            'message' => 'Waiting to start...',
        ]);

        return response()->json($progress);
    }

    // --- DEPARTMENTS CRUD & IMPORT ---
    public function departments(Request $request)
    {
        $query = Department::query();
        if ($search = $request->query('search')) {
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_hi', 'like', "%{$search}%");
        }
        $departments = $query->latest()->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_hi' => 'required|string',
            'is_active' => 'boolean',
        ]);

        Department::create([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'description_en' => $data['description_en'],
            'description_hi' => $data['description_hi'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_hi' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $department->update([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'description_en' => $data['description_en'],
            'description_hi' => $data['description_hi'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Department updated successfully.');
    }

    public function destroyDepartment(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted successfully.');
    }

    public function importDepartments(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            if (!isset($data['name_en'])) continue;

            Department::firstOrCreate(
                ['name_en' => $data['name_en']],
                [
                    'name_en' => $data['name_en'],
                    'name_hi' => $data['name_hi'] ?? $data['name_en'],
                    'description_en' => $data['description_en'] ?? 'Department details',
                    'description_hi' => $data['description_hi'] ?? 'विभाग विवरण',
                    'is_active' => true,
                ]
            );
        }
        fclose($file);
        return back()->with('success', 'Departments imported successfully.');
    }

    // --- DISEASES CRUD & IMPORT ---
    public function diseases(Request $request)
    {
        $query = Disease::with(['department', 'symptoms']);
        if ($search = $request->query('search')) {
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_hi', 'like', "%{$search}%");
        }
        $diseases = $query->latest()->get();
        $departments = Department::all();
        return view('admin.diseases.index', compact('diseases', 'departments'));
    }

    public function storeDisease(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'symptoms_en' => 'nullable|string',
            'symptoms_hi' => 'nullable|string',
        ]);

        $disease = Disease::create([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'department_id' => $data['department_id'],
        ]);

        $this->syncDiseaseSymptoms($disease, $data['symptoms_en'] ?? '', $data['symptoms_hi'] ?? '');

        return back()->with('success', 'Disease/Symptom created successfully.');
    }

    public function updateDisease(Request $request, Disease $disease)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'symptoms_en' => 'nullable|string',
            'symptoms_hi' => 'nullable|string',
        ]);

        $disease->update([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'department_id' => $data['department_id'],
        ]);

        $this->syncDiseaseSymptoms($disease, $data['symptoms_en'] ?? '', $data['symptoms_hi'] ?? '');

        return back()->with('success', 'Disease/Symptom updated successfully.');
    }

    public function destroyDisease(Disease $disease)
    {
        $disease->delete();
        return back()->with('success', 'Disease/Symptom deleted successfully.');
    }

    public function importDiseases(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            if (!isset($data['name_en'])) continue;

            $dept = Department::where('name_en', 'like', "%{$data['department_name_en']}%")->first();
            if (!$dept && isset($data['department_name_en'])) {
                $dept = Department::create([
                    'name_en' => $data['department_name_en'],
                    'name_hi' => $data['department_name_hi'] ?? $data['department_name_en'],
                    'description_en' => 'Imported department',
                    'description_hi' => 'Imported department',
                    'is_active' => true,
                ]);
            }

            $disease = Disease::firstOrCreate(
                ['name_en' => $data['name_en']],
                [
                    'name_en' => $data['name_en'],
                    'name_hi' => $data['name_hi'] ?? $data['name_en'],
                    'department_id' => $dept ? $dept->id : 1,
                ]
            );

            $symptomsEn = $data['symptoms_en'] ?? ($data['symptom_en'] ?? '');
            $symptomsHi = $data['symptoms_hi'] ?? ($data['symptom_hi'] ?? '');
            $this->syncDiseaseSymptoms($disease, $symptomsEn, $symptomsHi);
        }
        fclose($file);
        return back()->with('success', 'Diseases imported successfully.');
    }

    public function exportDiseases()
    {
        $diseases = Disease::with(['department', 'symptoms'])->get();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=disease_symptom_mapping_export.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($diseases) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'id',
                'disease_name_en',
                'disease_name_hi',
                'department_name_en',
                'department_name_hi',
                'symptoms_en',
                'symptoms_hi',
            ]);

            foreach ($diseases as $disease) {
                fputcsv($file, [
                    $disease->id,
                    $disease->name_en,
                    $disease->name_hi,
                    $disease->department?->name_en,
                    $disease->department?->name_hi,
                    $disease->symptoms->pluck('name_en')->implode('; '),
                    $disease->symptoms->pluck('name_hi')->filter()->implode('; '),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function syncDiseaseSymptoms(Disease $disease, string $symptomsEnRaw, string $symptomsHiRaw = ''): void
    {
        $symptomsEn = collect(preg_split('/[,;\n]+/', $symptomsEnRaw))
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values();

        $symptomsHi = collect(preg_split('/[,;\n]+/', $symptomsHiRaw))
            ->map(fn ($value) => trim((string) $value))
            ->values();

        $symptomIds = [];
        foreach ($symptomsEn as $index => $symptomEn) {
            $symptomHi = $symptomsHi->get($index);
            $symptom = Symptom::firstOrCreate(
                ['name_en' => $symptomEn],
                ['name_hi' => $symptomHi ?: null]
            );

            if (!$symptom->name_hi && $symptomHi) {
                $symptom->update(['name_hi' => $symptomHi]);
            }

            $symptomIds[] = $symptom->id;
        }

        $disease->symptoms()->sync($symptomIds);
    }

    // --- ARTICLES CRUD ---
    public function articles(Request $request)
    {
        $query = Article::query();
        if ($search = $request->query('search')) {
            $query->where('title_en', 'like', "%{$search}%")
                ->orWhere('title_hi', 'like', "%{$search}%");
        }
        $articles = $query->latest()->get();
        return view('admin.articles.index', compact('articles'));
    }

    public function storeArticle(Request $request)
    {
        $data = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_hi' => 'required|string|max:255',
            'excerpt_en' => 'required|string',
            'excerpt_hi' => 'required|string',
            'content_en' => 'required|string',
            'content_hi' => 'required|string',
        ]);

        Article::create([
            'title_en' => $data['title_en'],
            'title_hi' => $data['title_hi'],
            'excerpt_en' => $data['excerpt_en'],
            'excerpt_hi' => $data['excerpt_hi'],
            'content_en' => $data['content_en'],
            'content_hi' => $data['content_hi'],
        ]);

        return back()->with('success', 'Article created successfully.');
    }

    public function updateArticle(Request $request, Article $article)
    {
        $data = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_hi' => 'required|string|max:255',
            'excerpt_en' => 'required|string',
            'excerpt_hi' => 'required|string',
            'content_en' => 'required|string',
            'content_hi' => 'required|string',
        ]);

        $article->update([
            'title_en' => $data['title_en'],
            'title_hi' => $data['title_hi'],
            'excerpt_en' => $data['excerpt_en'],
            'excerpt_hi' => $data['excerpt_hi'],
            'content_en' => $data['content_en'],
            'content_hi' => $data['content_hi'],
        ]);

        return back()->with('success', 'Article updated successfully.');
    }

    public function destroyArticle(Article $article)
    {
        $article->delete();
        return back()->with('success', 'Article deleted successfully.');
    }

    // --- FAQS CRUD ---
    public function faqs(Request $request)
    {
        $query = Faq::query();
        if ($search = $request->query('search')) {
            $query->where('question_en', 'like', "%{$search}%")
                ->orWhere('question_hi', 'like', "%{$search}%");
        }
        $faqs = $query->latest()->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
            'category' => 'required|string|max:255',
        ]);

        Faq::create([
            'question_en' => $data['question_en'],
            'question_hi' => $data['question_hi'],
            'answer_en' => $data['answer_en'],
            'answer_hi' => $data['answer_hi'],
            'category' => $data['category'],
        ]);

        return back()->with('success', 'FAQ created successfully.');
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
            'category' => 'required|string|max:255',
        ]);

        $faq->update([
            'question_en' => $data['question_en'],
            'question_hi' => $data['question_hi'],
            'answer_en' => $data['answer_en'],
            'answer_hi' => $data['answer_hi'],
            'category' => $data['category'],
        ]);

        return back()->with('success', 'FAQ updated successfully.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully.');
    }

    // --- GENERAL MEDICAL Q&A CRUD ---
    public function generalQa(Request $request)
    {
        $query = Faq::query()->where('category', 'General Medical');
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('question_en', 'like', "%{$search}%")
                    ->orWhere('question_hi', 'like', "%{$search}%")
                    ->orWhere('answer_en', 'like', "%{$search}%")
                    ->orWhere('answer_hi', 'like', "%{$search}%");
            });
        }

        $faqs = $query->latest()->get();
        return view('admin.general_qa.index', compact('faqs'));
    }

    public function storeGeneralQa(Request $request)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
        ]);

        Faq::create([
            'question_en' => $data['question_en'],
            'question_hi' => $data['question_hi'],
            'answer_en' => $data['answer_en'],
            'answer_hi' => $data['answer_hi'],
            'category' => 'General Medical',
        ]);

        return back()->with('success', 'General medical Q&A created successfully.');
    }

    public function updateGeneralQa(Request $request, Faq $faq)
    {
        abort_unless($faq->category === 'General Medical', 404);

        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
        ]);

        $faq->update([
            'question_en' => $data['question_en'],
            'question_hi' => $data['question_hi'],
            'answer_en' => $data['answer_en'],
            'answer_hi' => $data['answer_hi'],
            'category' => 'General Medical',
        ]);

        return back()->with('success', 'General medical Q&A updated successfully.');
    }

    public function destroyGeneralQa(Faq $faq)
    {
        abort_unless($faq->category === 'General Medical', 404);

        $faq->delete();
        return back()->with('success', 'General medical Q&A deleted successfully.');
    }

    // --- CACHED MEDICAL QUESTIONS CRUD ---
    public function cachedMedicalQuestions(Request $request)
    {
        $query = CachedMedicalQuestion::query();
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('question_en', 'like', "%{$search}%")
                    ->orWhere('question_hi', 'like', "%{$search}%")
                    ->orWhere('answer_en', 'like', "%{$search}%")
                    ->orWhere('answer_hi', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $questions = $query->latest()->get();
        return view('admin.cached_medical_questions.index', compact('questions'));
    }

    public function storeCachedMedicalQuestion(Request $request)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
            'detailed_answer_en' => 'nullable|string',
            'detailed_answer_hi' => 'nullable|string',
            'category' => 'required|string|max:255',
        ]);

        CachedMedicalQuestion::create($data);

        return back()->with('success', 'Cached medical question created successfully.');
    }

    public function updateCachedMedicalQuestion(Request $request, CachedMedicalQuestion $cachedMedicalQuestion)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
            'detailed_answer_en' => 'nullable|string',
            'detailed_answer_hi' => 'nullable|string',
            'category' => 'required|string|max:255',
        ]);

        $cachedMedicalQuestion->update($data);

        return back()->with('success', 'Cached medical question updated successfully.');
    }

    public function destroyCachedMedicalQuestion(CachedMedicalQuestion $cachedMedicalQuestion)
    {
        $cachedMedicalQuestion->delete();
        return back()->with('success', 'Cached medical question deleted successfully.');
    }
}
