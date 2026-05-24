<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\Hospital;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index()
    {
        $nameColumn = app()->getLocale() === 'hi' ? 'name_hi' : 'name_en';

        return view('home.index', [
            'departments' => Department::where('is_active', true)->orderBy($nameColumn)->get()->map(fn(Department $department) => $this->formatDepartment($department)),
            'doctors' => Doctor::with(['department', 'hospitals'])->where('is_verified', true)->latest()->take(6)->get()->map(fn(Doctor $doctor) => $this->formatDoctor($doctor)),
            'articles' => Article::with('comments')->where('is_published', true)->latest()->take(6)->get(),
            'faqs' => Faq::latest()->get(),
            'stats' => [
                'cities' => Hospital::distinct('city')->count('city') ?: 1,
                'doctors' => Doctor::count(),
                'departments' => Department::where('is_active', true)->count(),
                'hospitals' => Hospital::count(),
                'blood_banks' => \App\Models\BloodBank::count(),
            ],
        ]);
    }

    public function search(Request $request)
    {
        $query = $this->normalizeSymptomQuery((string) $request->input('q', ''));
        $locale = app()->getLocale();

        if (empty($query)) {
            return response()->json([
                'doctors' => Doctor::with(['department', 'hospitals'])->where('is_verified', true)->latest()->take(10)->get()->map(fn(Doctor $doctor) => $this->formatDoctor($doctor)),
                'matched_department' => null,
                'matched_disease' => null,
            ]);
        }

        $matchedDeptId = null;
        $matchedDiseaseName = null;
        $terms = array_filter(explode(' ', trim($query)));
        $isLikelyDoctorNameQuery = preg_match('/\bdr\.?\b/i', $query) === 1 || count($terms) >= 2;

        // High-confidence symptom intent routing first (prevents bad "pain" fallbacks).
        $intentMatch = $this->resolveSymptomIntentDepartment($query);
        if ($intentMatch) {
            $matchedDeptId = $intentMatch['department_id'];
            $matchedDiseaseName = $intentMatch['matched_label'];
        }

        // 1. Deterministic symptom/disease text match first (prevents incorrect vector-only matches).
        if (!$matchedDeptId) {
            $diseaseCandidates = Disease::where(function ($diseaseQuery) use ($query, $terms) {
                $diseaseQuery->where('name_en', 'LIKE', "%{$query}%")
                    ->orWhere('name_hi', 'LIKE', "%{$query}%");

                foreach ($terms as $term) {
                    $diseaseQuery->orWhere('name_en', 'LIKE', "%{$term}%")
                        ->orWhere('name_hi', 'LIKE', "%{$term}%");
                }
            })->with('department')->get();

            $diseaseMatch = $this->pickBestDiseaseMatch($diseaseCandidates, $query, $terms);
            if ($diseaseMatch) {
                $matchedDeptId = $diseaseMatch->department_id;
                $matchedDiseaseName = $locale === 'hi' ? $diseaseMatch->name_hi : $diseaseMatch->name_en;
            }
        }

        // 2. If text match is not found, use vector similarity.
        if (!$matchedDeptId) {
            try {
                /** @var mixed $stringObj */
                $stringObj = Str::of($query);
                $userEmbedding = method_exists($stringObj, 'toEmbeddings') ? $stringObj->toEmbeddings() : json_encode(array_fill(0, 1536, 0.01));
                $diseases = Disease::whereVectorSimilarTo('symptoms_embedding', $userEmbedding)
                    ->with(['department'])
                    ->get();

                if ($diseases->isNotEmpty()) {
                    $firstMatch = $diseases->first();
                    $matchedDeptId = $firstMatch->department_id;
                    $matchedDiseaseName = $locale === 'hi' ? $firstMatch->name_hi : $firstMatch->name_en;
                }
            } catch (Exception $e) {
                // Ignore vector errors and continue with department/name fallback.
            }
        }

        // Check direct match on Department name if no disease matched
        if (!$matchedDeptId) {
            $deptMatch = Department::where(function ($departmentQuery) use ($query) {
                $departmentQuery->where('name_en', 'LIKE', "%{$query}%")
                    ->orWhere('name_hi', 'LIKE', "%{$query}%");
            })->first();
            if ($deptMatch) {
                $matchedDeptId = $deptMatch->id;
            }
        }

        if ($matchedDeptId) {
            $doctors = Doctor::where('department_id', $matchedDeptId)
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->get();
        } else {
            $doctors = collect();
        }

        // 2. If no symptom/department mapping produced doctors, attempt doctor-name search.
        if ($doctors->isEmpty() && $isLikelyDoctorNameQuery && !empty($terms)) {
            $nameQuery = Doctor::with(['department', 'hospitals'])->where('is_verified', true);
            $nameQuery->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->where('first_name', 'LIKE', "%{$term}%")
                            ->orWhere('last_name', 'LIKE', "%{$term}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"])
                            ->orWhere('specialization_summary', 'LIKE', "%{$term}%");
                    });
                }
            });
            $doctors = $nameQuery->get();
        }

        $matchedDept = $matchedDeptId ? Department::find($matchedDeptId) : null;
        $deptName = $matchedDept ? ($locale === 'hi' ? $matchedDept->name_hi : $matchedDept->name_en) : null;

        return response()->json([
            'doctors' => $doctors->map(fn(Doctor $doctor) => $this->formatDoctor($doctor)),
            'matched_department' => $deptName,
            'matched_disease' => $matchedDiseaseName,
        ]);
    }

    public function switchLocale(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|in:en,hi',
        ]);

        Session::put('locale', $validated['locale']);

        return back();
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
            'phone' => $doctor->phone ?: $doctor->hospitals->first()?->emergency_phone,
            'website' => $doctor->website && !str_contains($doctor->website, 'swasthyasearch.com') ? $doctor->website : null,
            'gender' => $doctor->gender,
            'languages_spoken' => $doctor->languages_spoken ?: [],
            'consultation_fee' => $doctor->consultation_fee ?: $doctor->hospitals->first()?->pivot?->consultation_fee,
            'specialization_summary' => $doctor->specialization_summary,
            'awards_recognitions' => $doctor->awards_recognitions ?: [],
            'membership_fellowships' => $doctor->membership_fellowships ?: [],
            'hospitals' => $doctor->hospitals->map(fn(Hospital $hospital) => [
                'id' => $hospital->id,
                'name' => [
                    'en' => $hospital->name_en,
                    'hi' => $hospital->name_hi,
                ],
                'type' => $hospital->type,
                'address' => $hospital->address,
                'city' => $hospital->city,
                'latitude' => $hospital->latitude,
                'longitude' => $hospital->longitude,
                'emergency_phone' => $hospital->emergency_phone,
                'is_verified' => $hospital->is_verified,
                'accepts_ayushman' => $hospital->accepts_ayushman || $hospital->accepts_ayushman_card,
                'accepts_janaadhaar' => $hospital->accepts_janaadhaar || $hospital->accepts_jan_aadhaar,
                'accepts_cghs' => $hospital->accepts_cghs,
                'rgahs_approved' => $hospital->rgahs_approved,
                'is_cashless' => $hospital->is_cashless || $hospital->cashless_treatment_available,
                'cashless_schemes_list' => $hospital->cashless_schemes_list ?: [],
                'pivot' => $hospital->pivot,
            ]),
        ];
    }

    private function normalizeSymptomQuery(string $query): string
    {
        $normalized = mb_strtolower(trim($query));
        if ($normalized === '') {
            return '';
        }

        $replacements = [
            'join pain' => 'joint pain',
            'hedache' => 'headache',
            'feaver' => 'fever',
            'stomuch pain' => 'stomach pain',
        ];

        foreach ($replacements as $wrong => $correct) {
            if (str_contains($normalized, $wrong)) {
                $normalized = str_replace($wrong, $correct, $normalized);
            }
        }

        return $normalized;
    }

    private function resolveSymptomIntentDepartment(string $query): ?array
    {
        $q = mb_strtolower(trim($query));
        if ($q === '') {
            return null;
        }

        $intentMap = [
            [
                'label' => 'Fever',
                'keywords' => ['fever', 'बुखार'],
                'department_like' => ['general medicine', 'internal medicine'],
            ],
            [
                'label' => 'Cough',
                'keywords' => ['cough', 'खांसी', 'खासी'],
                'department_like' => ['pulmonology', 'respiratory', 'general medicine'],
            ],
            [
                'label' => 'Stomach Pain',
                'keywords' => ['stomach pain', 'abdominal pain', 'पेट दर्द', 'पेट में दर्द'],
                'department_like' => ['gastroenterology', 'general medicine'],
            ],
            [
                'label' => 'Skin Rash',
                'keywords' => ['skin rash', 'rash', 'त्वचा चकत्ते', 'चकत्ते'],
                'department_like' => ['dermatology', 'skin'],
            ],
            [
                'label' => 'Joint Pain',
                'keywords' => ['joint pain', 'join pain', 'knee pain', 'shoulder pain', 'जोड़ों का दर्द', 'घुटने का दर्द'],
                'department_like' => ['orthoped', 'orthopaed'],
            ],
            [
                'label' => 'Headache',
                'keywords' => ['headache', 'migraine', 'सिरदर्द', 'सिर दर्द'],
                'department_like' => ['neurology', 'general medicine'],
            ],
        ];

        foreach ($intentMap as $intent) {
            $matched = false;
            foreach ($intent['keywords'] as $keyword) {
                if (str_contains($q, mb_strtolower($keyword))) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                continue;
            }

            $dept = Department::where('is_active', true)
                ->where(function ($query) use ($intent) {
                    $first = true;
                    foreach ($intent['department_like'] as $needle) {
                        $pattern = '%' . mb_strtolower($needle) . '%';
                        if ($first) {
                            $query->whereRaw('LOWER(name_en) LIKE ?', [$pattern])
                                ->orWhereRaw('LOWER(name_hi) LIKE ?', [$pattern]);
                            $first = false;
                        } else {
                            $query->orWhereRaw('LOWER(name_en) LIKE ?', [$pattern])
                                ->orWhereRaw('LOWER(name_hi) LIKE ?', [$pattern]);
                        }
                    }
                })
                ->first();

            if ($dept) {
                return [
                    'department_id' => $dept->id,
                    'matched_label' => $intent['label'],
                ];
            }
        }

        return null;
    }

    private function pickBestDiseaseMatch($diseases, string $query, array $terms): ?Disease
    {
        if (!$diseases || $diseases->isEmpty()) {
            return null;
        }

        $query = mb_strtolower($query);
        $best = null;
        $bestScore = -1;

        foreach ($diseases as $disease) {
            $en = mb_strtolower((string) ($disease->name_en ?? ''));
            $hi = mb_strtolower((string) ($disease->name_hi ?? ''));
            $name = $en !== '' ? $en : $hi;
            if ($name === '') {
                continue;
            }

            $score = 0;
            if ($name === $query) {
                $score += 1000;
            }
            if (str_contains($name, $query)) {
                $score += 350;
            }

            $matchedTerms = 0;
            foreach ($terms as $term) {
                $t = mb_strtolower((string) $term);
                if ($t !== '' && (str_contains($en, $t) || str_contains($hi, $t))) {
                    $matchedTerms++;
                }
            }
            $score += $matchedTerms * 120;

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $disease;
            }
        }

        return $best;
    }
}
