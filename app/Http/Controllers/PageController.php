<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function emergency()
    {
        return view('pages.emergency', [
            'locale' => app()->getLocale(),
        ]);
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

        return view('pages.departments', [
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

        return view('pages.diseases', [
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
        $message = 'Your message has been sent successfully. Our support team will get back to you within 24 hours.';

        return back()
            ->with('success', $message)
            ->with('site_popup', [
                'type' => 'success',
                'title' => 'Message sent',
                'message' => $message,
            ]);
    }

    public function submitFeedback(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'required|string|max:255',
            'comments' => 'required|string|max:2000',
        ]);

        $message = 'Thank you for your valuable feedback! Your input helps us improve Arogio for everyone.';

        return back()
            ->with('success', $message)
            ->with('site_popup', [
                'type' => 'success',
                'title' => 'Thanks for your feedback',
                'message' => $message,
            ]);
    }

    public function submitLeadCapture(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! We will stay in touch with useful updates.',
        ]);
    }

    public function submitConsultationRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^[0-9+\\-()\\s]{10,20}$/'],
            'reason' => 'required|string|min:10|max:2000',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|date_format:H:i',
        ]);

        DB::table('consultation_requests')->insert([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'phone' => trim($validated['phone']),
            'reason' => trim($validated['reason']),
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'ip_address' => (string) $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $message = 'Your consultation request has been received. Our team will contact you shortly.';

        return back()
            ->with('consultation_request_success', $message)
            ->with('site_popup', [
                'type' => 'success',
                'title' => 'Request received',
                'message' => $message,
            ]);
    }

    public function submitListingReport(Request $request)
    {
        $validated = $request->validate([
            'entity_type' => 'required|string|in:doctor,hospital,blood_bank',
            'entity_id' => 'required|integer|min:1',
            'entity_name' => 'required|string|max:255',
            'issue' => 'required|string|in:wrong_phone,wrong_address,duplicate,closed,other',
            'details' => 'required|string|min:3|max:1000',
        ]);

        DB::table('listing_feedback')->insert([
            'entity_type' => $validated['entity_type'],
            'entity_id' => $validated['entity_id'],
            'entity_name' => $validated['entity_name'],
            'vote_type' => 'red',
            'issue' => $validated['issue'],
            'details' => $validated['details'],
            'ip_address' => (string) $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $counts = $this->getListingVoteCounts($validated['entity_type'], (int) $validated['entity_id']);

        return response()->json([
            'success' => true,
            'message' => 'Thank you. Your report has been submitted.',
            'popup_title' => 'Thanks for your contribution',
            'counts' => $counts,
        ]);
    }

    public function submitListingVote(Request $request)
    {
        $validated = $request->validate([
            'entity_type' => 'required|string|in:doctor,hospital,blood_bank',
            'entity_id' => 'required|integer|min:1',
            'entity_name' => 'required|string|max:255',
            'vote_type' => 'required|string|in:green',
        ]);

        DB::table('listing_feedback')->insert([
            'entity_type' => $validated['entity_type'],
            'entity_id' => $validated['entity_id'],
            'entity_name' => $validated['entity_name'],
            'vote_type' => 'green',
            'ip_address' => (string) $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $counts = $this->getListingVoteCounts($validated['entity_type'], (int) $validated['entity_id']);

        return response()->json([
            'success' => true,
            'message' => 'Vote submitted.',
            'popup_title' => 'Thanks for your confirmation',
            'counts' => $counts,
        ]);
    }

    private function getListingVoteCounts(string $entityType, int $entityId): array
    {
        $rows = DB::table('listing_feedback')
            ->select('vote_type', DB::raw('COUNT(*) as total'))
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->groupBy('vote_type')
            ->get();

        $green = (int) optional($rows->firstWhere('vote_type', 'green'))->total;
        $red = (int) optional($rows->firstWhere('vote_type', 'red'))->total;

        return [
            'green' => $green,
            'red' => $red,
        ];
    }
}
