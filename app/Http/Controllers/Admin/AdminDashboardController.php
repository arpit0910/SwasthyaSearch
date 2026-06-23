<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\BloodBank;
use App\Models\CachedMedicalQuestion;
use App\Models\ChatbotFailedQuery;
use App\Models\Consultation;
use App\Models\Department;
use App\Models\DirectorySyncHistory;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\GeneralQuestion;
use App\Models\Hospital;
use App\Models\Medicine;
use App\Models\MedicineReport;
use App\Models\Quiz;
use App\Models\Symptom;
use App\Models\SymptomTestSubmission;
use App\Models\UserSubmission;
use App\Services\DirectorySyncService;
use App\Services\ScraperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'faqs_count' => Faq::count(),
            'general_qa_count' => GeneralQuestion::count(),
            'cached_medical_questions_count' => CachedMedicalQuestion::count(),
            'pending_submissions_count' => UserSubmission::where('status', 'pending')->count(),
            'failed_queries_count' => ChatbotFailedQuery::count(),
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

        $recentSubmissions = UserSubmission::query()
            ->latest()
            ->limit(6)
            ->get();

        $recentFailedQueries = ChatbotFailedQuery::query()
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'supportedCities',
            'syncHistory',
            'recentSubmissions',
            'recentFailedQueries'
        ));
    }

    public function symptomTestReports(Request $request)
    {
        $days = max(7, min(90, (int) $request->integer('days', 30)));
        $trendStart = now()->startOfDay()->subDays($days - 1);

        $submissionsQuery = SymptomTestSubmission::query()->with(['topDisease', 'recommendedDepartment']);

        if ($search = trim((string) $request->query('search', ''))) {
            $submissionsQuery->where(function ($query) use ($search) {
                $query->where('symptom_text', 'like', "%{$search}%")
                    ->orWhere('gender', 'like', "%{$search}%")
                    ->orWhere('locale', 'like', "%{$search}%");
            });
        }

        if ($gender = trim((string) $request->query('gender', ''))) {
            $submissionsQuery->where('gender', $gender);
        }

        $totalSubmissions = SymptomTestSubmission::count();
        $recentSubmissions = (clone $submissionsQuery)->latest()->paginate(12)->withQueryString();

        $trendRows = SymptomTestSubmission::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $trendStart)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $trendLabels = [];
        $trendValues = [];
        for ($offset = 0; $offset < $days; $offset++) {
            $date = $trendStart->copy()->addDays($offset);
            $key = $date->toDateString();
            $trendLabels[] = $date->format('d M');
            $trendValues[] = (int) ($trendRows[$key] ?? 0);
        }

        $genderBreakdown = SymptomTestSubmission::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(gender), ''), 'Unspecified') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->pluck('total', 'label')
            ->toArray();

        $topDiseaseRows = SymptomTestSubmission::query()
            ->select('top_disease_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('top_disease_id')
            ->groupBy('top_disease_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $diseasesById = Disease::whereIn('id', $topDiseaseRows->pluck('top_disease_id')->filter()->all())->get()->keyBy('id');

        $topDiseases = $topDiseaseRows->map(function ($row) use ($diseasesById) {
            $disease = $diseasesById->get($row->top_disease_id);
            return [
                'label' => $disease ? ($disease->getTranslation('name', 'en') ?: $disease->getTranslation('name', 'hi') ?: 'Disease #' . $row->top_disease_id) : 'Disease #' . $row->top_disease_id,
                'total' => (int) $row->total,
            ];
        })->values()->all();

        $topSymptoms = $this->buildTopSymptomsReport();

        $stats = [
            'total_submissions' => $totalSubmissions,
            'last_7_days' => SymptomTestSubmission::where('created_at', '>=', now()->subDays(6))->count(),
            'last_30_days' => SymptomTestSubmission::where('created_at', '>=', now()->subDays(29))->count(),
            'avg_age' => (float) round((float) SymptomTestSubmission::avg('age'), 1),
            'female_count' => SymptomTestSubmission::where('gender', 'female')->count(),
            'male_count' => SymptomTestSubmission::where('gender', 'male')->count(),
            'other_count' => SymptomTestSubmission::whereNotIn('gender', ['male', 'female'])->whereNotNull('gender')->where('gender', '!=', '')->count(),
        ];

        return view('admin.symptom-tests.index', compact(
            'days',
            'stats',
            'trendLabels',
            'trendValues',
            'genderBreakdown',
            'topDiseases',
            'topSymptoms',
            'recentSubmissions'
        ));
    }

    public function consultations()
    {
        $pendingConsultations = Consultation::query()
            ->where('status', Consultation::STATUS_PENDING)
            ->latest()
            ->get();

        $acceptedConsultations = Consultation::query()
            ->where('status', Consultation::STATUS_ACCEPTED)
            ->latest('updated_at')
            ->get();

        $activeConsultations = Consultation::query()
            ->where('status', Consultation::STATUS_ACTIVE)
            ->latest('updated_at')
            ->get();

        $rejectedConsultations = Consultation::query()
            ->where('status', Consultation::STATUS_REJECTED)
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $completedConsultations = Consultation::query()
            ->where('status', Consultation::STATUS_COMPLETED)
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('admin.consultations.index', compact(
            'pendingConsultations',
            'acceptedConsultations',
            'activeConsultations',
            'rejectedConsultations',
            'completedConsultations'
        ));
    }

    public function acceptConsultation(string $uuid)
    {
        $consultation = Consultation::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $consultation->update([
            'doctor_id' => auth()->guard('admin')->id(),
            'status' => Consultation::STATUS_ACCEPTED,
        ]);

        return back()->with('success', 'Consultation accepted. You can join the call now.');
    }

    public function rejectConsultation(string $uuid)
    {
        $consultation = Consultation::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $consultation->update([
            'doctor_id' => auth()->guard('admin')->id(),
            'status' => Consultation::STATUS_REJECTED,
            'sdp_answer' => null,
            'ice_candidates_doctor' => [],
        ]);

        return back()->with('success', 'Consultation rejected.');
    }

    public function joinConsultation(string $uuid)
    {
        $consultation = Consultation::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($consultation->status === Consultation::STATUS_REJECTED) {
            return redirect()->route('admin.consultations')
                ->with('error', 'Rejected consultations cannot be joined.');
        }

        if (in_array($consultation->status, [Consultation::STATUS_PENDING, Consultation::STATUS_ACCEPTED], true)) {
            $consultation->update([
                'doctor_id' => auth()->guard('admin')->id(),
                'status' => Consultation::STATUS_ACTIVE,
            ]);
            $consultation->refresh();
        }

        return view('admin.consultations.room', compact('consultation'));
    }

    private function buildTopSymptomsReport(): array
    {
        $counts = [];

        SymptomTestSubmission::query()
            ->latest()
            ->limit(500)
            ->get(['selected_symptoms', 'symptom_text'])
            ->each(function (SymptomTestSubmission $submission) use (&$counts) {
                $selectedSymptoms = is_array($submission->selected_symptoms) ? $submission->selected_symptoms : [];
                foreach ($selectedSymptoms as $symptom) {
                    $symptom = trim((string) $symptom);
                    if ($symptom !== '') {
                        $counts[Str::lower($symptom)] = ($counts[Str::lower($symptom)] ?? 0) + 1;
                    }
                }

                $freeTextSymptoms = preg_split('/[,;\n]+/', (string) $submission->symptom_text) ?: [];
                foreach ($freeTextSymptoms as $symptom) {
                    $symptom = trim((string) $symptom);
                    if ($symptom !== '') {
                        $counts[Str::lower($symptom)] = ($counts[Str::lower($symptom)] ?? 0) + 1;
                    }
                }
            });

        return collect($counts)
            ->sortDesc()
            ->take(12)
            ->map(function ($total, $label) {
                return [
                    'label' => Str::of($label)->replaceMatches('/\s+/', ' ')->title()->toString(),
                    'total' => (int) $total,
                ];
            })
            ->values()
            ->all();
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

    public function createHospital()
    {
        return view('admin.hospitals.create_page');
    }

    public function editHospital(Hospital $hospital)
    {
        return view('admin.hospitals.edit_page', compact('hospital'));
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
            'country_code_2' => 'nullable|string|max:10',
            'phone_1' => 'required|string',
            'phone_2' => 'nullable|string|max:20',
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
            'country_code_2' => $data['country_code_2'] ?? null,
            'phone_2' => $data['phone_2'] ?? null,
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
            'country_code_2' => 'nullable|string|max:10',
            'phone_1' => 'required|string',
            'phone_2' => 'nullable|string|max:20',
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
            'country_code_2' => $data['country_code_2'] ?? null,
            'phone_2' => $data['phone_2'] ?? null,
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

    public function createDoctor()
    {
        $departments = Department::all();
        return view('admin.doctors.create_page', compact('departments'));
    }

    public function editDoctor(Doctor $doctor)
    {
        $departments = Department::all();
        return view('admin.doctors.edit_page', compact('doctor', 'departments'));
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
            'country_code_2' => 'nullable|string|max:10',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
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
            'country_code_2' => $data['country_code_2'] ?? null,
            'phone_2' => $data['phone_2'] ?? null,
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
            'country_code_2' => 'nullable|string|max:10',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
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
            'country_code_2' => $data['country_code_2'] ?? null,
            'phone_2' => $data['phone_2'] ?? null,
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

    public function createBloodBank()
    {
        return view('admin.blood_banks.create_page');
    }

    public function editBloodBank(BloodBank $bloodBank)
    {
        return view('admin.blood_banks.edit_page', compact('bloodBank'));
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

    public function createDepartment()
    {
        return view('admin.departments.create_page');
    }

    public function editDepartment(Department $department)
    {
        return view('admin.departments.edit_page', compact('department'));
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

    public function createDisease()
    {
        $departments = Department::all();
        return view('admin.diseases.create_page', compact('departments'));
    }

    public function editDisease(Disease $disease)
    {
        $departments = Department::all();
        return view('admin.diseases.edit_page', compact('disease', 'departments'));
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

    public function createArticle()
    {
        return view('admin.articles.create_page');
    }

    public function editArticle(Article $article)
    {
        return view('admin.articles.edit_page', compact('article'));
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

    // --- MEDICINES CRUD ---
    public function medicines(Request $request)
    {
        $query = Medicine::query();

        if ($search = trim((string) $request->query('search', ''))) {
            $query->search($search);
        }

        if ($status = trim((string) $request->query('status', ''))) {
            $query->where('review_status', $status);
        }

        $medicines = $query->select(['id', 'name', 'slug', 'generic_name', 'category', 'review_status', 'is_published', 'brand_names_json'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.medicines.index', compact('medicines'));
    }

    public function createMedicine()
    {
        return view('admin.medicines.create_page');
    }

    public function editMedicine(Medicine $medicine)
    {
        return view('admin.medicines.edit_page', compact('medicine'));
    }

    public function storeMedicine(Request $request)
    {
        Medicine::create($this->validatedMedicineData($request));

        return redirect()->route('admin.medicines')->with('success', 'Medicine created successfully.');
    }

    public function updateMedicine(Request $request, Medicine $medicine)
    {
        $medicine->update($this->validatedMedicineData($request));

        return redirect()->route('admin.medicines.edit', $medicine)->with('success', 'Medicine updated successfully.');
    }

    public function destroyMedicine(Medicine $medicine)
    {
        $medicine->delete();

        return back()->with('success', 'Medicine deleted successfully.');
    }

    public function exportMedicines()
    {
        $medicines = Medicine::all();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=medicines_export.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'id', 'name', 'slug', 'generic_name', 'brand_names_json', 'composition', 'strength',
            'medicine_type', 'category', 'prescription_required', 'purpose_en', 'purpose_hi',
            'overview_en', 'overview_hi', 'uses_en', 'uses_hi', 'benefits_en', 'benefits_hi',
            'dosage_information_en', 'dosage_information_hi', 'mechanism_en', 'mechanism_hi',
            'common_side_effects_en', 'common_side_effects_hi', 'serious_side_effects_en', 'serious_side_effects_hi',
            'drug_interactions_en', 'drug_interactions_hi', 'food_interactions_en', 'food_interactions_hi',
            'alcohol_warning_en', 'alcohol_warning_hi', 'pregnancy_warning_en', 'pregnancy_warning_hi',
            'breastfeeding_warning_en', 'breastfeeding_warning_hi', 'kidney_warning_en', 'kidney_warning_hi',
            'liver_warning_en', 'liver_warning_hi', 'driving_warning_en', 'driving_warning_hi',
            'allergy_warning_en', 'allergy_warning_hi', 'precautions_en', 'precautions_hi',
            'contraindications_en', 'contraindications_hi', 'avoid_if_en', 'avoid_if_hi',
            'missed_dose_en', 'missed_dose_hi', 'overdose_en', 'overdose_hi', 'storage_en', 'storage_hi',
            'expert_advice_en', 'expert_advice_hi', 'when_to_contact_doctor_en', 'when_to_contact_doctor_hi',
            'faqs_json', 'source_references_json', 'meta_title_en', 'meta_title_hi', 'meta_description_en',
            'meta_description_hi', 'reviewed_by', 'last_reviewed_at', 'ai_generated', 'medically_reviewed',
            'review_status', 'is_published'
        ];

        $callback = function () use ($medicines, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($medicines as $medicine) {
                $row = [];
                foreach ($columns as $column) {
                    $val = $medicine->{$column};
                    if (in_array($column, ['brand_names_json', 'faqs_json', 'source_references_json'])) {
                        $val = is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $val;
                    } elseif ($val instanceof \DateTimeInterface) {
                        $val = $val->format('Y-m-d H:i:s');
                    } elseif (is_bool($val)) {
                        $val = $val ? 1 : 0;
                    }
                    $row[] = $val;
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importMedicines(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            if (count($header) !== count($row)) continue;
            $data = array_combine($header, $row);
            if (empty($data['name'])) continue;

            $medicine = !empty($data['id']) ? Medicine::find($data['id']) : Medicine::where('name', $data['name'])->first();

            $brandNames = [];
            if (!empty($data['brand_names_json'])) {
                $decoded = json_decode($data['brand_names_json'], true);
                $brandNames = is_array($decoded) ? $decoded : array_map('trim', explode(';', $data['brand_names_json']));
            }
            $sourceRefs = [];
            if (!empty($data['source_references_json'])) {
                $decoded = json_decode($data['source_references_json'], true);
                $sourceRefs = is_array($decoded) ? $decoded : array_map('trim', explode(';', $data['source_references_json']));
            }
            $faqs = [];
            if (!empty($data['faqs_json'])) {
                $decoded = json_decode($data['faqs_json'], true);
                $faqs = is_array($decoded) ? $decoded : [];
            }

            $updateData = [
                'name' => $data['name'],
                'slug' => !empty($data['slug']) ? $data['slug'] : Str::slug($data['name']),
                'generic_name' => $data['generic_name'] ?? null,
                'brand_names_json' => $brandNames,
                'composition' => $data['composition'] ?? null,
                'strength' => $data['strength'] ?? null,
                'medicine_type' => $data['medicine_type'] ?? null,
                'category' => $data['category'] ?? null,
                'prescription_required' => isset($data['prescription_required']) ? filter_var($data['prescription_required'], FILTER_VALIDATE_BOOLEAN) : false,
                'purpose_en' => $data['purpose_en'] ?? null,
                'purpose_hi' => $data['purpose_hi'] ?? null,
                'overview_en' => $data['overview_en'] ?? null,
                'overview_hi' => $data['overview_hi'] ?? null,
                'uses_en' => $data['uses_en'] ?? null,
                'uses_hi' => $data['uses_hi'] ?? null,
                'benefits_en' => $data['benefits_en'] ?? null,
                'benefits_hi' => $data['benefits_hi'] ?? null,
                'dosage_information_en' => $data['dosage_information_en'] ?? null,
                'dosage_information_hi' => $data['dosage_information_hi'] ?? null,
                'mechanism_en' => $data['mechanism_en'] ?? null,
                'mechanism_hi' => $data['mechanism_hi'] ?? null,
                'common_side_effects_en' => $data['common_side_effects_en'] ?? null,
                'common_side_effects_hi' => $data['common_side_effects_hi'] ?? null,
                'serious_side_effects_en' => $data['serious_side_effects_en'] ?? null,
                'serious_side_effects_hi' => $data['serious_side_effects_hi'] ?? null,
                'drug_interactions_en' => $data['drug_interactions_en'] ?? null,
                'drug_interactions_hi' => $data['drug_interactions_hi'] ?? null,
                'food_interactions_en' => $data['food_interactions_en'] ?? null,
                'food_interactions_hi' => $data['food_interactions_hi'] ?? null,
                'alcohol_warning_en' => $data['alcohol_warning_en'] ?? null,
                'alcohol_warning_hi' => $data['alcohol_warning_hi'] ?? null,
                'pregnancy_warning_en' => $data['pregnancy_warning_en'] ?? null,
                'pregnancy_warning_hi' => $data['pregnancy_warning_hi'] ?? null,
                'breastfeeding_warning_en' => $data['breastfeeding_warning_en'] ?? null,
                'breastfeeding_warning_hi' => $data['breastfeeding_warning_hi'] ?? null,
                'kidney_warning_en' => $data['kidney_warning_en'] ?? null,
                'kidney_warning_hi' => $data['kidney_warning_hi'] ?? null,
                'liver_warning_en' => $data['liver_warning_en'] ?? null,
                'liver_warning_hi' => $data['liver_warning_hi'] ?? null,
                'driving_warning_en' => $data['driving_warning_en'] ?? null,
                'driving_warning_hi' => $data['driving_warning_hi'] ?? null,
                'allergy_warning_en' => $data['allergy_warning_en'] ?? null,
                'allergy_warning_hi' => $data['allergy_warning_hi'] ?? null,
                'precautions_en' => $data['precautions_en'] ?? null,
                'precautions_hi' => $data['precautions_hi'] ?? null,
                'contraindications_en' => $data['contraindications_en'] ?? null,
                'contraindications_hi' => $data['contraindications_hi'] ?? null,
                'avoid_if_en' => $data['avoid_if_en'] ?? null,
                'avoid_if_hi' => $data['avoid_if_hi'] ?? null,
                'missed_dose_en' => $data['missed_dose_en'] ?? null,
                'missed_dose_hi' => $data['missed_dose_hi'] ?? null,
                'overdose_en' => $data['overdose_en'] ?? null,
                'overdose_hi' => $data['overdose_hi'] ?? null,
                'storage_en' => $data['storage_en'] ?? null,
                'storage_hi' => $data['storage_hi'] ?? null,
                'expert_advice_en' => $data['expert_advice_en'] ?? null,
                'expert_advice_hi' => $data['expert_advice_hi'] ?? null,
                'when_to_contact_doctor_en' => $data['when_to_contact_doctor_en'] ?? null,
                'when_to_contact_doctor_hi' => $data['when_to_contact_doctor_hi'] ?? null,
                'faqs_json' => $faqs,
                'source_references_json' => $sourceRefs,
                'meta_title_en' => $data['meta_title_en'] ?? null,
                'meta_title_hi' => $data['meta_title_hi'] ?? null,
                'meta_description_en' => $data['meta_description_en'] ?? null,
                'meta_description_hi' => $data['meta_description_hi'] ?? null,
                'reviewed_by' => $data['reviewed_by'] ?? null,
                'last_reviewed_at' => !empty($data['last_reviewed_at']) ? $data['last_reviewed_at'] : null,
                'ai_generated' => isset($data['ai_generated']) ? filter_var($data['ai_generated'], FILTER_VALIDATE_BOOLEAN) : false,
                'medically_reviewed' => isset($data['medically_reviewed']) ? filter_var($data['medically_reviewed'], FILTER_VALIDATE_BOOLEAN) : false,
                'review_status' => !empty($data['review_status']) ? $data['review_status'] : 'draft',
                'is_published' => isset($data['is_published']) ? filter_var($data['is_published'], FILTER_VALIDATE_BOOLEAN) : false,
            ];

            if ($medicine) {
                $medicine->update($updateData);
            } else {
                Medicine::create($updateData);
            }
        }
        fclose($file);
        return back()->with('success', 'Medicines imported & updated successfully.');
    }

    public function medicineReports(Request $request)
    {
        $query = MedicineReport::query()->with('medicine')->latest();

        if ($status = trim((string) $request->query('status', ''))) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('admin.medicine_reports.index', compact('reports'));
    }

    public function updateMedicineReport(Request $request, MedicineReport $medicineReport)
    {
        $data = $request->validate([
            'status' => 'required|in:new,reviewing,resolved,rejected',
            'admin_notes' => 'nullable|string|max:4000',
        ]);

        $medicineReport->update($data);

        return back()->with('success', 'Medicine report updated successfully.');
    }

    // --- QUIZZES CRUD ---
    public function quizzes(Request $request)
    {
        $query = Quiz::query();

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_hi', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $quizzes = $query->latest()->paginate(20)->withQueryString();

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function createQuiz()
    {
        return view('admin.quizzes.create_page');
    }

    public function editQuiz(Quiz $quiz)
    {
        return view('admin.quizzes.edit_page', compact('quiz'));
    }

    public function storeQuiz(Request $request)
    {
        Quiz::create($this->validatedQuizData($request));

        return redirect()->route('admin.quizzes')->with('success', 'Quiz created successfully.');
    }

    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $quiz->update($this->validatedQuizData($request));

        return redirect()->route('admin.quizzes.edit', $quiz)->with('success', 'Quiz updated successfully.');
    }

    public function destroyQuiz(Quiz $quiz)
    {
        $quiz->delete();

        return back()->with('success', 'Quiz deleted successfully.');
    }

    private function validatedMedicineData(Request $request): array
    {
        $medicine = $request->route('medicine');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('medicines', 'slug')->ignore($medicine?->id),
            ],
            'generic_name' => 'nullable|string|max:255',
            'brand_names' => 'nullable|string',
            'composition' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'medicine_type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'prescription_required' => 'nullable|boolean',
            'purpose_en' => 'nullable|string',
            'purpose_hi' => 'nullable|string',
            'overview_en' => 'nullable|string',
            'overview_hi' => 'nullable|string',
            'uses_en' => 'nullable|string',
            'uses_hi' => 'nullable|string',
            'benefits_en' => 'nullable|string',
            'benefits_hi' => 'nullable|string',
            'dosage_information_en' => 'nullable|string',
            'dosage_information_hi' => 'nullable|string',
            'mechanism_en' => 'nullable|string',
            'mechanism_hi' => 'nullable|string',
            'common_side_effects_en' => 'nullable|string',
            'common_side_effects_hi' => 'nullable|string',
            'serious_side_effects_en' => 'nullable|string',
            'serious_side_effects_hi' => 'nullable|string',
            'drug_interactions_en' => 'nullable|string',
            'drug_interactions_hi' => 'nullable|string',
            'food_interactions_en' => 'nullable|string',
            'food_interactions_hi' => 'nullable|string',
            'alcohol_warning_en' => 'nullable|string',
            'alcohol_warning_hi' => 'nullable|string',
            'pregnancy_warning_en' => 'nullable|string',
            'pregnancy_warning_hi' => 'nullable|string',
            'breastfeeding_warning_en' => 'nullable|string',
            'breastfeeding_warning_hi' => 'nullable|string',
            'kidney_warning_en' => 'nullable|string',
            'kidney_warning_hi' => 'nullable|string',
            'liver_warning_en' => 'nullable|string',
            'liver_warning_hi' => 'nullable|string',
            'driving_warning_en' => 'nullable|string',
            'driving_warning_hi' => 'nullable|string',
            'allergy_warning_en' => 'nullable|string',
            'allergy_warning_hi' => 'nullable|string',
            'precautions_en' => 'nullable|string',
            'precautions_hi' => 'nullable|string',
            'contraindications_en' => 'nullable|string',
            'contraindications_hi' => 'nullable|string',
            'avoid_if_en' => 'nullable|string',
            'avoid_if_hi' => 'nullable|string',
            'missed_dose_en' => 'nullable|string',
            'missed_dose_hi' => 'nullable|string',
            'overdose_en' => 'nullable|string',
            'overdose_hi' => 'nullable|string',
            'storage_en' => 'nullable|string',
            'storage_hi' => 'nullable|string',
            'expert_advice_en' => 'nullable|string',
            'expert_advice_hi' => 'nullable|string',
            'when_to_contact_doctor_en' => 'nullable|string',
            'when_to_contact_doctor_hi' => 'nullable|string',
            'faqs' => 'nullable|string',
            'source_references' => 'nullable|string',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_hi' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string',
            'meta_description_hi' => 'nullable|string',
            'reviewed_by' => 'nullable|string|max:255',
            'last_reviewed_at' => 'nullable|date',
            'ai_generated' => 'nullable|boolean',
            'medically_reviewed' => 'nullable|boolean',
            'review_status' => 'required|in:' . implode(',', Medicine::REVIEW_STATUSES),
            'is_published' => 'nullable|boolean',
        ]);

        $data['brand_names_json'] = collect(preg_split('/[\r\n,]+/', (string) ($data['brand_names'] ?? '')))
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values()
            ->all();

        $data['faqs_json'] = collect(preg_split('/\r\n|\r|\n/', (string) ($data['faqs'] ?? '')))
            ->map(function ($line) {
                [$question, $answer] = array_pad(array_map('trim', explode('|', (string) $line, 2)), 2, null);
                if (blank($question) || blank($answer)) {
                    return null;
                }

                return compact('question', 'answer');
            })
            ->filter()
            ->values()
            ->all();

        $data['source_references_json'] = collect(preg_split('/\r\n|\r|\n/', (string) ($data['source_references'] ?? '')))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();

        $data['prescription_required'] = $request->boolean('prescription_required');
        $data['ai_generated'] = $request->boolean('ai_generated');
        $data['medically_reviewed'] = $request->boolean('medically_reviewed');
        $data['is_published'] = $request->boolean('is_published');
        $data['updated_by'] = auth()->guard('admin')->id();
        $data['created_by'] = $medicine?->created_by ?? auth()->guard('admin')->id();

        unset($data['brand_names'], $data['faqs'], $data['source_references']);

        return $data;
    }

    private function validatedQuizData(Request $request): array
    {
        $quiz = $request->route('quiz');
        $data = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_hi' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('quizzes', 'slug')->ignore($quiz?->id)],
            'category' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_hi' => 'nullable|string',
            'intro_en' => 'nullable|string',
            'intro_hi' => 'nullable|string',
            'questions_payload' => 'nullable|string',
            'results_payload' => 'nullable|string',
            'disclaimer_en' => 'nullable|string',
            'disclaimer_hi' => 'nullable|string',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_hi' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string',
            'meta_description_hi' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        $data['questions_json'] = json_decode((string) ($data['questions_payload'] ?? '[]'), true) ?: [];
        $data['result_ranges_json'] = json_decode((string) ($data['results_payload'] ?? '[]'), true) ?: [];
        $data['is_published'] = $request->boolean('is_published');
        unset($data['questions_payload'], $data['results_payload']);

        return $data;
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

    public function createFaq()
    {
        return view('admin.faqs.create_page');
    }

    public function editFaq(Faq $faq)
    {
        return view('admin.faqs.edit_page', compact('faq'));
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
        $query = GeneralQuestion::query();
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('question_en', 'like', "%{$search}%")
                    ->orWhere('question_hi', 'like', "%{$search}%")
                    ->orWhere('answer_en', 'like', "%{$search}%")
                    ->orWhere('answer_hi', 'like', "%{$search}%");
            });
        }

        $generalQuestions = $query->latest()->get();
        return view('admin.general_qa.index', compact('generalQuestions'));
    }

    public function createGeneralQa()
    {
        return view('admin.general_qa.create_page');
    }

    public function editGeneralQa(GeneralQuestion $generalQuestion)
    {
        return view('admin.general_qa.edit_page', compact('generalQuestion'));
    }

    public function storeGeneralQa(Request $request)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
            'detailed_answer_en' => 'nullable|string',
            'detailed_answer_hi' => 'nullable|string',
        ]);

        GeneralQuestion::create([
            'question_en' => $data['question_en'],
            'question_hi' => $data['question_hi'],
            'answer_en' => $data['answer_en'],
            'answer_hi' => $data['answer_hi'],
            'detailed_answer_en' => $data['detailed_answer_en'] ?? null,
            'detailed_answer_hi' => $data['detailed_answer_hi'] ?? null,
        ]);

        return back()->with('success', 'General question created successfully.');
    }

    public function updateGeneralQa(Request $request, GeneralQuestion $generalQuestion)
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_hi' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_hi' => 'required|string',
            'detailed_answer_en' => 'nullable|string',
            'detailed_answer_hi' => 'nullable|string',
        ]);

        $generalQuestion->update([
            'question_en' => $data['question_en'],
            'question_hi' => $data['question_hi'],
            'answer_en' => $data['answer_en'],
            'answer_hi' => $data['answer_hi'],
            'detailed_answer_en' => $data['detailed_answer_en'] ?? null,
            'detailed_answer_hi' => $data['detailed_answer_hi'] ?? null,
        ]);

        return back()->with('success', 'General question updated successfully.');
    }

    public function destroyGeneralQa(GeneralQuestion $generalQuestion)
    {
        $generalQuestion->delete();
        return back()->with('success', 'General question deleted successfully.');
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

        $questions = $query->latest()->paginate(25)->withQueryString();
        return view('admin.cached_medical_questions.index', compact('questions'));
    }

    public function createCachedMedicalQuestion()
    {
        return view('admin.cached_medical_questions.create_page');
    }

    public function editCachedMedicalQuestion(CachedMedicalQuestion $cachedMedicalQuestion)
    {
        return view('admin.cached_medical_questions.edit_page', compact('cachedMedicalQuestion'));
    }

    public function showCachedMedicalQuestion(CachedMedicalQuestion $cachedMedicalQuestion)
    {
        return view('admin.cached_medical_questions.show_page', compact('cachedMedicalQuestion'));
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

    public function submissions(Request $request)
    {
        $query = UserSubmission::query();
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $submissions = $query->latest()->paginate(15);
        return view('admin.submissions.index', compact('submissions'));
    }

    public function rejectSubmission(UserSubmission $submission)
    {
        $submission->update(['status' => 'rejected']);
        return back()->with('success', 'Submission has been marked as rejected.');
    }

    public function exportSubmissions()
    {
        $submissions = UserSubmission::all();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=user_submissions_export.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'id',
                'type',
                'name',
                'phone',
                'city',
                'status',
                'address',
                'registration_number',
                'specialization',
                'hospital_type',
                'accepts_ayushman',
                'accepts_janaadhaar',
                'created_at',
            ]);

            foreach ($submissions as $sub) {
                $details = $sub->details ?? [];
                fputcsv($file, [
                    $sub->id,
                    $sub->type,
                    $sub->name,
                    $sub->phone,
                    $sub->city,
                    $sub->status,
                    $details['address'] ?? '',
                    $details['registration_number'] ?? '',
                    $details['specialization'] ?? '',
                    $details['hospital_type'] ?? '',
                    isset($details['accepts_ayushman']) ? ($details['accepts_ayushman'] ? 1 : 0) : '',
                    isset($details['accepts_janaadhaar']) ? ($details['accepts_janaadhaar'] ? 1 : 0) : '',
                    $sub->created_at->toDateTimeString(),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importSubmission(UserSubmission $submission)
    {
        if ($submission->type === 'doctor') {
            $details = $submission->details ?? [];
            $names = explode(' ', trim($submission->name), 2);
            $firstName = $names[0];
            $lastName = $names[1] ?? '';

            $doctor = null;
            if (!empty($details['registration_number'])) {
                $doctor = Doctor::where('registration_number', $details['registration_number'])->first();
            }

            $spec = $details['specialization'] ?? 'General Medicine';
            $dept = Department::where('name_en', 'like', "%{$spec}%")->first();
            if (!$dept) {
                $dept = Department::create([
                    'name_en' => $spec,
                    'name_hi' => $spec,
                    'description_en' => 'Imported via user submission',
                    'description_hi' => 'उपयोगकर्ता सुझाव द्वारा आयातित',
                    'is_active' => true,
                ]);
            }

            $doctorData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'registration_number' => $details['registration_number'] ?? null,
                'department_id' => $dept->id,
                'phone_1' => $submission->phone,
                'city' => $submission->city ?? 'Jaipur',
                'state' => 'Rajasthan',
                'address_line1' => $details['address'] ?? null,
                'is_verified' => true,
                'education_degrees' => ScraperService::getRealDegreesForDepartment($spec),
                'languages_spoken' => ['Hindi', 'English'],
                'experience_years' => 5,
                'consultation_fee' => 500,
            ];

            if ($doctor) {
                $doctor->update($doctorData);
            } else {
                $doctor = Doctor::create($doctorData);
            }
            $doctor->departments()->syncWithoutDetaching([$dept->id]);
        } elseif ($submission->type === 'hospital') {
            $details = $submission->details ?? [];
            $hospital = Hospital::where('name_en', $submission->name)->first();

            $hospitalData = [
                'name_en' => $submission->name,
                'name_hi' => $submission->name,
                'type' => $details['hospital_type'] ?? 'General',
                'phone_1' => $submission->phone,
                'city' => $submission->city ?? 'Jaipur',
                'state' => 'Rajasthan',
                'address_line1' => $details['address'] ?? null,
                'is_verified' => true,
                'accepts_ayushman_card' => !empty($details['accepts_ayushman']),
                'accepts_jan_aadhaar' => !empty($details['accepts_janaadhaar']),
                'accepts_ayushman' => !empty($details['accepts_ayushman']),
                'accepts_janaadhaar' => !empty($details['accepts_janaadhaar']),
            ];

            if ($hospital) {
                $hospital->update($hospitalData);
            } else {
                $hospital = Hospital::create($hospitalData);
            }
        }

        $submission->update(['status' => 'approved']);

        return back()->with('success', 'Submission has been successfully verified, imported, and marked as approved.');
    }
}
