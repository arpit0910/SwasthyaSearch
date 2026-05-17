<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\Hospital;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'verified_doctors' => Doctor::where('is_verified', true)->count(),
            'verified_hospitals' => Hospital::where('is_verified', true)->count(),
            'articles_count' => Article::count(),
            'active_departments' => Department::where('is_active', true)->count(),
        ];

        $departments = Department::withCount('doctors')->get();
        $chartData = [
            'labels' => $departments->pluck('name_en')->toArray(),
            'data' => $departments->pluck('doctors_count')->toArray(),
        ];

        return view('admin.dashboard', compact('stats', 'chartData'));
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
        $hospitals = $query->latest()->paginate(10);
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function storeHospital(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'type' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'emergency_phone' => 'required|string',
            'is_verified' => 'boolean',
        ]);

        Hospital::create([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'type' => $data['type'],
            'city' => $data['city'],
            'address' => $data['address'],
            'emergency_phone' => $data['emergency_phone'],
            'is_verified' => $request->boolean('is_verified', true),
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
            'address' => 'required|string',
            'emergency_phone' => 'required|string',
            'is_verified' => 'boolean',
        ]);

        $hospital->update([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'type' => $data['type'],
            'city' => $data['city'],
            'address' => $data['address'],
            'emergency_phone' => $data['emergency_phone'],
            'is_verified' => $request->boolean('is_verified', true),
        ]);

        return back()->with('success', 'Hospital updated successfully.');
    }

    public function destroyHospital(Hospital $hospital)
    {
        $hospital->delete();
        return back()->with('success', 'Hospital deleted successfully.');
    }

    public function importHospitals(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            if (!isset($data['name_en'])) continue;

            Hospital::firstOrCreate(
                ['name_en' => $data['name_en']],
                [
                    'name_en' => $data['name_en'],
                    'name_hi' => $data['name_hi'] ?? $data['name_en'],
                    'type' => $data['type'] ?? 'Hospital',
                    'address' => $data['address'] ?? 'General Address',
                    'city' => $data['city'] ?? 'Delhi',
                    'emergency_phone' => $data['emergency_phone'] ?? '102',
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'is_verified' => true,
                ]
            );
        }
        fclose($file);
        return back()->with('success', 'Hospitals imported successfully.');
    }

    // --- DOCTORS CRUD & IMPORT ---
    public function doctors(Request $request)
    {
        $query = Doctor::with('departments');
        if ($search = $request->query('search')) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
        }
        $doctors = $query->latest()->paginate(10);
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
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'languages_spoken' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric',
            'specialization_summary' => 'nullable|string',
            'awards_recognitions' => 'nullable|string',
            'membership_fellowships' => 'nullable|string',
        ]);

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
            'phone' => $data['phone'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'languages_spoken' => !empty($data['languages_spoken']) ? array_map('trim', explode(',', $data['languages_spoken'])) : null,
            'consultation_fee' => $data['consultation_fee'] ?? null,
            'specialization_summary' => $data['specialization_summary'] ?? null,
            'awards_recognitions' => !empty($data['awards_recognitions']) ? array_map('trim', explode(',', $data['awards_recognitions'])) : null,
            'membership_fellowships' => !empty($data['membership_fellowships']) ? array_map('trim', explode(',', $data['membership_fellowships'])) : null,
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
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'languages_spoken' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric',
            'specialization_summary' => 'nullable|string',
            'awards_recognitions' => 'nullable|string',
            'membership_fellowships' => 'nullable|string',
        ]);

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
            'phone' => $data['phone'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'languages_spoken' => !empty($data['languages_spoken']) ? array_map('trim', explode(',', $data['languages_spoken'])) : null,
            'consultation_fee' => $data['consultation_fee'] ?? null,
            'specialization_summary' => $data['specialization_summary'] ?? null,
            'awards_recognitions' => !empty($data['awards_recognitions']) ? array_map('trim', explode(',', $data['awards_recognitions'])) : null,
            'membership_fellowships' => !empty($data['membership_fellowships']) ? array_map('trim', explode(',', $data['membership_fellowships'])) : null,
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

    public function importDoctors(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            if (!isset($data['first_name'])) continue;

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

            $doctor = Doctor::firstOrCreate(
                ['registration_number' => $data['registration_number'] ?? ('REG-' . rand(1000, 9999))],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'] ?? '',
                    'department_id' => $dept ? $dept->id : null,
                    'medical_council' => $data['medical_council'] ?? 'MCI',
                    'education_degrees' => !empty($data['education_degrees']) ? array_map('trim', explode(';', $data['education_degrees'])) : ['MBBS'],
                    'experience_years' => (int)($data['experience_years'] ?? 10),
                    'about_en' => $data['about_en'] ?? 'Expert doctor',
                    'about_hi' => $data['about_hi'] ?? 'विशेषज्ञ डॉक्टर',
                    'is_verified' => true,
                ]
            );

            if ($dept) {
                $doctor->departments()->syncWithoutDetaching([$dept->id]);
            }
        }
        fclose($file);
        return back()->with('success', 'Doctors imported successfully.');
    }

    // --- DEPARTMENTS CRUD & IMPORT ---
    public function departments(Request $request)
    {
        $query = Department::query();
        if ($search = $request->query('search')) {
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_hi', 'like', "%{$search}%");
        }
        $departments = $query->latest()->paginate(10);
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
        $query = Disease::with('department');
        if ($search = $request->query('search')) {
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_hi', 'like', "%{$search}%");
        }
        $diseases = $query->latest()->paginate(10);
        $departments = Department::all();
        return view('admin.diseases.index', compact('diseases', 'departments'));
    }

    public function storeDisease(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Disease::create([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'department_id' => $data['department_id'],
        ]);

        return back()->with('success', 'Disease/Symptom created successfully.');
    }

    public function updateDisease(Request $request, Disease $disease)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_hi' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $disease->update([
            'name_en' => $data['name_en'],
            'name_hi' => $data['name_hi'],
            'department_id' => $data['department_id'],
        ]);

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

            Disease::firstOrCreate(
                ['name_en' => $data['name_en']],
                [
                    'name_en' => $data['name_en'],
                    'name_hi' => $data['name_hi'] ?? $data['name_en'],
                    'department_id' => $dept ? $dept->id : 1,
                ]
            );
        }
        fclose($file);
        return back()->with('success', 'Diseases imported successfully.');
    }

    // --- ARTICLES CRUD ---
    public function articles(Request $request)
    {
        $query = Article::query();
        if ($search = $request->query('search')) {
            $query->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_hi', 'like', "%{$search}%");
        }
        $articles = $query->latest()->paginate(10);
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
        $faqs = $query->latest()->paginate(10);
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
}
