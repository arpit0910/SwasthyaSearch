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
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index()
    {
        $nameColumn = app()->getLocale() === 'hi' ? 'name_hi' : 'name_en';

        return Inertia::render('Home/Index', [
            'departments' => Department::where('is_active', true)->orderBy($nameColumn)->get()->map(fn (Department $department) => $this->formatDepartment($department)),
            'doctors' => Doctor::with(['department', 'hospitals'])->where('is_verified', true)->latest()->take(6)->get()->map(fn (Doctor $doctor) => $this->formatDoctor($doctor)),
            'articles' => Article::with('comments')->where('is_published', true)->latest()->get(),
            'faqs' => Faq::latest()->get(),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $locale = app()->getLocale();

        if (empty($query)) {
            return response()->json([
                'doctors' => Doctor::with(['department', 'hospitals'])->where('is_verified', true)->latest()->take(10)->get()->map(fn (Doctor $doctor) => $this->formatDoctor($doctor)),
                'matched_department' => null,
                'matched_disease' => null,
            ]);
        }

        $matchedDeptId = null;
        $matchedDiseaseName = null;

        // 1. Vector Similarity Search or Fallback Text Search on Diseases
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
            // Fallback to LIKE search
            $diseaseMatch = Disease::where(function ($diseaseQuery) use ($query) {
                $diseaseQuery->where('name_en', 'LIKE', "%{$query}%")
                    ->orWhere('name_hi', 'LIKE', "%{$query}%");
            })->with('department')->first();
            if ($diseaseMatch) {
                $matchedDeptId = $diseaseMatch->department_id;
                $matchedDiseaseName = $locale === 'hi' ? $diseaseMatch->name_hi : $diseaseMatch->name_en;
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

        // 2. Search Doctors (Direct Matching or Filtered by Department)
        if ($matchedDeptId) {
            $doctors = Doctor::where('department_id', $matchedDeptId)
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->get();
        } else {
            // Search doctor name
            $doctors = Doctor::where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->get();
        }

        $matchedDept = $matchedDeptId ? Department::find($matchedDeptId) : null;
        $deptName = $matchedDept ? ($locale === 'hi' ? $matchedDept->name_hi : $matchedDept->name_en) : null;

        return response()->json([
            'doctors' => $doctors->map(fn (Doctor $doctor) => $this->formatDoctor($doctor)),
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
            'phone' => $doctor->phone ?: ($doctor->hospitals->first()?->emergency_phone ?: '+91-141-2345678'),
            'website' => $doctor->website && !str_contains($doctor->website, 'swasthyasearch.com') ? $doctor->website : null,
            'gender' => $doctor->gender,
            'languages_spoken' => $doctor->languages_spoken ?: ['English', 'Hindi'],
            'consultation_fee' => $doctor->consultation_fee ?: ($doctor->hospitals->first()?->pivot?->consultation_fee ?: 500),
            'specialization_summary' => $doctor->specialization_summary,
            'awards_recognitions' => $doctor->awards_recognitions ?: [],
            'membership_fellowships' => $doctor->membership_fellowships ?: [],
            'hospitals' => $doctor->hospitals->map(fn (Hospital $hospital) => [
                'id' => $hospital->id,
                'name' => [
                    'en' => $hospital->name_en,
                    'hi' => $hospital->name_hi,
                ],
                'type' => $hospital->type,
                'address' => $hospital->address ?: 'Jaipur, Rajasthan',
                'city' => $hospital->city ?: 'Jaipur',
                'latitude' => $hospital->latitude ?: 26.9124,
                'longitude' => $hospital->longitude ?: 75.7873,
                'emergency_phone' => $hospital->emergency_phone ?: '+91-141-2345678',
                'is_verified' => $hospital->is_verified,
                'pivot' => $hospital->pivot,
            ]),
        ];
    }
}
