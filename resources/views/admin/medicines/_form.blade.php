@php
    $medicine = $medicine ?? null;
    $faqLines =
        $medicine && is_array($medicine->faqs_json ?? null)
            ? collect($medicine->faqs_json)
                ->map(fn($faq) => trim(($faq['question'] ?? '') . ' | ' . ($faq['answer'] ?? '')))
                ->implode("\n")
            : '';
    $sourceLines =
        $medicine && is_array($medicine->source_references_json ?? null)
            ? collect($medicine->source_references_json)->implode("\n")
            : '';
    $brandLines =
        $medicine && is_array($medicine->brand_names_json ?? null)
            ? collect($medicine->brand_names_json)->implode("\n")
            : '';
@endphp

<div class="card-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Medicine Name</label>
            <input type="text" name="name" class="form-control" required
                value="{{ old('name', $medicine->name ?? '') }}" placeholder="e.g. Paracetamol">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $medicine->slug ?? '') }}"
                placeholder="leave blank to auto-generate">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Generic Name</label>
            <input type="text" name="generic_name" class="form-control"
                value="{{ old('generic_name', $medicine->generic_name ?? '') }}" placeholder="e.g. Acetaminophen">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Composition</label>
            <input type="text" name="composition" class="form-control"
                value="{{ old('composition', $medicine->composition ?? '') }}" placeholder="e.g. Paracetamol IP">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Strength</label>
            <input type="text" name="strength" class="form-control"
                value="{{ old('strength', $medicine->strength ?? '') }}" placeholder="e.g. 500 mg / 650 mg">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Medicine Type</label>
            <input type="text" name="medicine_type" class="form-control"
                value="{{ old('medicine_type', $medicine->medicine_type ?? '') }}" placeholder="Tablet / Syrup">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Category</label>
            <input type="text" name="category" class="form-control"
                value="{{ old('category', $medicine->category ?? '') }}" placeholder="e.g. Pain relief and fever">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Review Status</label>
            <select name="review_status" class="form-select" required>
                @foreach (\App\Models\Medicine::REVIEW_STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('review_status', $medicine->review_status ?? 'draft') === $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Brand Names</label>
            <textarea name="brand_names" class="form-control" rows="3" placeholder="One brand per line">{{ old('brand_names', $brandLines) }}</textarea>
        </div>
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="prescription_required" value="1"
                            id="prescription_required" @checked(old('prescription_required', $medicine->prescription_required ?? false))>
                        <label class="form-check-label" for="prescription_required">Prescription required</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="medically_reviewed" value="1"
                            id="medically_reviewed" @checked(old('medically_reviewed', $medicine->medically_reviewed ?? false))>
                        <label class="form-check-label" for="medically_reviewed">Medically reviewed</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_published" value="1"
                            id="is_published" @checked(old('is_published', $medicine->is_published ?? false))>
                        <label class="form-check-label" for="is_published">Published</label>
                    </div>
                </div>
            </div>
        </div>

        @foreach ([
        'purpose' => 'Purpose',
        'overview' => 'Overview',
        'uses' => 'Uses',
        'benefits' => 'Benefits',
        'dosage_information' => 'General Dosage Information',
        'mechanism' => 'How It Works',
        'common_side_effects' => 'Common Side Effects',
        'serious_side_effects' => 'Serious Side Effects',
        'drug_interactions' => 'Drug Interactions',
        'food_interactions' => 'Food Interactions',
        'alcohol_warning' => 'Alcohol Warning',
        'pregnancy_warning' => 'Pregnancy Warning',
        'breastfeeding_warning' => 'Breastfeeding Warning',
        'kidney_warning' => 'Kidney Warning',
        'liver_warning' => 'Liver Warning',
        'driving_warning' => 'Driving Warning',
        'allergy_warning' => 'Allergy Warning',
        'precautions' => 'Precautions',
        'contraindications' => 'Contraindications',
        'avoid_if' => 'Who Should Avoid It',
        'missed_dose' => 'Missed Dose',
        'overdose' => 'Overdose',
        'storage' => 'Storage',
        'expert_advice' => 'Expert Advice',
        'when_to_contact_doctor' => 'When To Contact Doctor',
    ] as $field => $label)
            <div class="col-md-6">
                <label class="form-label fw-semibold">{{ $label }} (English)</label>
                <textarea name="{{ $field }}_en" class="form-control" rows="3"
                    placeholder="Enter {{ strtolower($label) }} details in English...">{{ old($field . '_en', $medicine?->{$field . '_en'} ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">{{ $label }} (Hindi)</label>
                <textarea name="{{ $field }}_hi" class="form-control" rows="3"
                    placeholder="Enter {{ strtolower($label) }} details in Hindi...">{{ old($field . '_hi', $medicine?->{$field . '_hi'} ?? '') }}</textarea>
            </div>
        @endforeach

        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Title (English)</label>
            <input type="text" name="meta_title_en" class="form-control"
                value="{{ old('meta_title_en', $medicine->meta_title_en ?? '') }}"
                placeholder="e.g. Paracetamol: Uses, Side Effects">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Title (Hindi)</label>
            <input type="text" name="meta_title_hi" class="form-control"
                value="{{ old('meta_title_hi', $medicine->meta_title_hi ?? '') }}"
                placeholder="e.g. पैरासिटामोल: उपयोग, दुष्प्रभाव">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Description (English)</label>
            <textarea name="meta_description_en" class="form-control" rows="2"
                placeholder="Brief educational summary in English...">{{ old('meta_description_en', $medicine->meta_description_en ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Description (Hindi)</label>
            <textarea name="meta_description_hi" class="form-control" rows="2"
                placeholder="Brief educational summary in Hindi...">{{ old('meta_description_hi', $medicine->meta_description_hi ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Reviewed By</label>
            <input type="text" name="reviewed_by" class="form-control"
                value="{{ old('reviewed_by', $medicine->reviewed_by ?? '') }}" placeholder="e.g. Dr. Rajesh Patel">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Last Reviewed At</label>
            <input type="date" name="last_reviewed_at" class="form-control"
                value="{{ old('last_reviewed_at', optional($medicine->last_reviewed_at ?? null)->toDateString()) }}">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">FAQs</label>
            <textarea name="faqs" class="form-control" rows="4" placeholder="Question | Answer">{{ old('faqs', $faqLines) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Source References</label>
            <textarea name="source_references" class="form-control" rows="3" placeholder="One source per line">{{ old('source_references', $sourceLines) }}</textarea>
        </div>
    </div>
</div>
