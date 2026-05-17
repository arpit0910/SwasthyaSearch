<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Disease;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function about()
    {
        return Inertia::render('Pages/About');
    }

    public function contact()
    {
        return Inertia::render('Pages/Contact');
    }

    public function departments()
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'hi' ? 'name_hi' : 'name_en';

        $departments = Department::withCount(['diseases', 'doctors'])
            ->where('is_active', true)
            ->orderBy($nameColumn)
            ->get()
            ->map(fn (Department $department) => [
                'id' => $department->id,
                'name' => [
                    'en' => $department->name_en,
                    'hi' => $department->name_hi,
                ],
                'description' => [
                    'en' => $department->description_en,
                    'hi' => $department->description_hi,
                ],
                'diseases_count' => $department->diseases_count,
                'doctors_count' => $department->doctors_count,
            ]);

        return Inertia::render('Pages/Departments', [
            'departments' => $departments,
        ]);
    }

    public function diseases(Request $request)
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'hi' ? 'name_hi' : 'name_en';

        $query = Disease::with('department')
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->where('department_id', $request->integer('department'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name_en', 'LIKE', "%{$search}%")
                        ->orWhere('name_hi', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy($nameColumn);

        $departments = Department::where('is_active', true)
            ->orderBy($nameColumn)
            ->get()
            ->map(fn (Department $department) => [
                'id' => $department->id,
                'name' => [
                    'en' => $department->name_en,
                    'hi' => $department->name_hi,
                ],
            ]);

        $diseases = $query->get()->map(fn (Disease $disease) => [
            'id' => $disease->id,
            'name' => [
                'en' => $disease->name_en,
                'hi' => $disease->name_hi,
            ],
            'department' => $disease->department ? [
                'id' => $disease->department->id,
                'name' => [
                    'en' => $disease->department->name_en,
                    'hi' => $disease->department->name_hi,
                ],
            ] : null,
        ]);

        return Inertia::render('Pages/Diseases', [
            'departments' => $departments,
            'diseases' => $diseases,
            'filters' => $request->only(['department', 'search']),
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // In a real app we might store this in a contacts table or send an email.
        // Here we return back with a success flash message.
        return back()->with('success', 'Your message has been sent successfully. Our support team will get back to you within 24 hours!');
    }
}
