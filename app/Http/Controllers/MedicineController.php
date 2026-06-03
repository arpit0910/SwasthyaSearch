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
        $relatedMedicines = Medicine::query()
            ->published()
            ->whereKeyNot($medicine->id)
            ->when($medicine->category, fn ($query) => $query->where('category', $medicine->category))
            ->limit(4)
            ->get();

        $relatedArticles = Article::query()
            ->where('is_published', true)
            ->where(function ($query) use ($medicine) {
                $query->where('title_en', 'like', '%' . $medicine->name . '%')
                    ->orWhere('title_hi', 'like', '%' . $medicine->name . '%')
                    ->orWhere('content_en', 'like', '%' . ($medicine->generic_name ?: $medicine->name) . '%')
                    ->orWhere('content_hi', 'like', '%' . ($medicine->generic_name ?: $medicine->name) . '%');
            })
            ->limit(3)
            ->get();

        $sections = collect([
            'overview' => $medicine->getTranslation('overview', $locale),
            'uses' => $medicine->getTranslation('uses', $locale),
            'benefits' => $medicine->getTranslation('benefits', $locale),
            'dosage_information' => $medicine->getTranslation('dosage_information', $locale),
            'mechanism' => $medicine->getTranslation('mechanism', $locale),
            'common_side_effects' => $medicine->getTranslation('common_side_effects', $locale),
            'serious_side_effects' => $medicine->getTranslation('serious_side_effects', $locale),
            'drug_interactions' => $medicine->getTranslation('drug_interactions', $locale),
            'food_interactions' => $medicine->getTranslation('food_interactions', $locale),
            'alcohol_warning' => $medicine->getTranslation('alcohol_warning', $locale),
            'pregnancy_warning' => $medicine->getTranslation('pregnancy_warning', $locale),
            'breastfeeding_warning' => $medicine->getTranslation('breastfeeding_warning', $locale),
            'kidney_warning' => $medicine->getTranslation('kidney_warning', $locale),
            'liver_warning' => $medicine->getTranslation('liver_warning', $locale),
            'driving_warning' => $medicine->getTranslation('driving_warning', $locale),
            'allergy_warning' => $medicine->getTranslation('allergy_warning', $locale),
            'precautions' => $medicine->getTranslation('precautions', $locale),
            'contraindications' => $medicine->getTranslation('contraindications', $locale),
            'avoid_if' => $medicine->getTranslation('avoid_if', $locale),
            'missed_dose' => $medicine->getTranslation('missed_dose', $locale),
            'overdose' => $medicine->getTranslation('overdose', $locale),
            'storage' => $medicine->getTranslation('storage', $locale),
            'expert_advice' => $medicine->getTranslation('expert_advice', $locale),
            'when_to_contact_doctor' => $medicine->getTranslation('when_to_contact_doctor', $locale),
        ])->filter(fn ($value) => filled((string) $value));

        return view('medicines.show', compact('medicine', 'relatedMedicines', 'relatedArticles', 'sections', 'locale'));
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
