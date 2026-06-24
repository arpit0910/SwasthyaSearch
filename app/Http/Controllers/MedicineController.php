<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Medicine;
use App\Models\MedicineReport;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->query('search', ''));

        $medicines = Medicine::query()
            ->select(['id', 'name', 'slug', 'generic_name', 'prescription_required', 'composition', 'category', 'purpose_en', 'purpose_hi', 'brand_names_json', 'is_published', 'review_status'])
            ->published()
            ->search($search)
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('medicines.index', [
            'medicines' => $medicines,
            'search' => $search,
            'locale' => $locale,
        ]);
    }

    public function show(Medicine $medicine)
    {
        abort_unless($medicine->is_published && in_array($medicine->review_status, ['reviewed', 'published'], true), 404);

        $locale = app()->getLocale();
        $normalizedLocale = in_array($locale, ['en', 'hi'], true) ? $locale : 'en';
        $relatedMedicines = Medicine::query()
            ->select(['id', 'name', 'slug', 'generic_name', 'category', 'is_published', 'review_status'])
            ->published()
            ->whereKeyNot($medicine->id)
            ->when($medicine->category, fn ($query) => $query->where('category', $medicine->category))
            ->limit(4)
            ->get();

        $relatedArticles = Article::query()
            ->select(['id', 'title_en', 'title_hi', 'excerpt_en', 'excerpt_hi', 'is_published'])
            ->where('is_published', true)
            ->where(function ($query) use ($medicine) {
                $query->where('title_en', 'like', '%' . $medicine->name . '%')
                    ->orWhere('title_hi', 'like', '%' . $medicine->name . '%')
                    ->orWhere('excerpt_en', 'like', '%' . ($medicine->generic_name ?: $medicine->name) . '%')
                    ->orWhere('excerpt_hi', 'like', '%' . ($medicine->generic_name ?: $medicine->name) . '%');
            })
            ->limit(3)
            ->get();

        $faqs = collect(is_array($medicine->faqs_json) ? $medicine->faqs_json : [])
            ->filter(fn ($faq) => is_array($faq) && filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->values();

        $brandNames = collect(is_array($medicine->brand_names) ? $medicine->brand_names : [])
            ->filter(fn ($brand) => is_string($brand) && trim($brand) !== '')
            ->map(fn ($brand) => trim($brand))
            ->values();

        $sections = collect([
            'overview' => $medicine->getTranslation('overview', $normalizedLocale),
            'uses' => $medicine->getTranslation('uses', $normalizedLocale),
            'benefits' => $medicine->getTranslation('benefits', $normalizedLocale),
            'dosage_information' => $medicine->getTranslation('dosage_information', $normalizedLocale),
            'mechanism' => $medicine->getTranslation('mechanism', $normalizedLocale),
            'common_side_effects' => $medicine->getTranslation('common_side_effects', $normalizedLocale),
            'serious_side_effects' => $medicine->getTranslation('serious_side_effects', $normalizedLocale),
            'drug_interactions' => $medicine->getTranslation('drug_interactions', $normalizedLocale),
            'food_interactions' => $medicine->getTranslation('food_interactions', $normalizedLocale),
            'alcohol_warning' => $medicine->getTranslation('alcohol_warning', $normalizedLocale),
            'pregnancy_warning' => $medicine->getTranslation('pregnancy_warning', $normalizedLocale),
            'breastfeeding_warning' => $medicine->getTranslation('breastfeeding_warning', $normalizedLocale),
            'kidney_warning' => $medicine->getTranslation('kidney_warning', $normalizedLocale),
            'liver_warning' => $medicine->getTranslation('liver_warning', $normalizedLocale),
            'driving_warning' => $medicine->getTranslation('driving_warning', $normalizedLocale),
            'allergy_warning' => $medicine->getTranslation('allergy_warning', $normalizedLocale),
            'precautions' => $medicine->getTranslation('precautions', $normalizedLocale),
            'contraindications' => $medicine->getTranslation('contraindications', $normalizedLocale),
            'avoid_if' => $medicine->getTranslation('avoid_if', $normalizedLocale),
            'missed_dose' => $medicine->getTranslation('missed_dose', $normalizedLocale),
            'overdose' => $medicine->getTranslation('overdose', $normalizedLocale),
            'storage' => $medicine->getTranslation('storage', $normalizedLocale),
            'expert_advice' => $medicine->getTranslation('expert_advice', $normalizedLocale),
            'when_to_contact_doctor' => $medicine->getTranslation('when_to_contact_doctor', $normalizedLocale),
        ])->filter(fn ($value) => filled((string) $value));

        return view('medicines.show', compact('medicine', 'relatedMedicines', 'relatedArticles', 'sections', 'locale', 'normalizedLocale', 'faqs', 'brandNames'));
    }

    public function report(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'report_type' => 'required|string|max:100',
            'user_message' => 'required|string|min:10|max:4000',
            'corrected_information' => 'nullable|string|max:4000',
            'reporter_name' => 'nullable|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'reporter_phone' => 'nullable|string|max:50',
        ]);

        MedicineReport::create($validated + [
            'medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name,
        ]);

        return back()->with('success', app()->getLocale() === 'hi'
            ? 'रिपोर्ट भेज दी गई है। हमारी टीम इसकी समीक्षा करेगी।'
            : 'Report submitted successfully. Our team will review it.');
    }
}
