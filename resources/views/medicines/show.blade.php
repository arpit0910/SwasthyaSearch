@extends('layouts.public')

@php
    $pageLocale = $normalizedLocale ?? ($locale === 'hi' ? 'hi' : 'en');
    $faqItems = isset($faqs) && $faqs instanceof \Illuminate\Support\Collection ? $faqs : collect(is_array($faqs ?? null) ? $faqs : []);
    $brandItems = isset($brandNames) && $brandNames instanceof \Illuminate\Support\Collection ? $brandNames : collect(is_array($brandNames ?? null) ? $brandNames : []);
    $metaTitle = $pageLocale === 'hi'
        ? ($medicine->meta_title_hi ?: ($medicine->name . ' की जानकारी, उपयोग, सावधानियां और दुष्प्रभाव | Arogio'))
        : ($medicine->meta_title_en ?: ($medicine->name . ': Uses, Side Effects, Dosage Info & Precautions | Arogio'));
    $metaDescription = $pageLocale === 'hi'
        ? ($medicine->meta_description_hi ?: ($medicine->name . ' के उपयोग, सामान्य सावधानियां, दुष्प्रभाव और शैक्षणिक दवा जानकारी देखें।'))
        : ($medicine->meta_description_en ?: ('Read educational information about ' . $medicine->name . ', including uses, side effects, precautions, and safety guidance.'));
    $medicineDescription = \App\Support\Seo::cleanText($metaDescription, 160);
@endphp

@section('title', $medicine->name . ' - Arogio')
@section('meta_title', $metaTitle)
@section('meta_description', $medicineDescription)
@section('meta_keywords', \App\Support\Seo::keywords([$medicine->name, 'medicine information', 'uses', 'side effects', 'precautions', 'Arogio']))
@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'MedicalWebPage',
    'name' => $medicine->name,
    'headline' => $metaTitle,
    'description' => $medicineDescription,
    'url' => route('medicines.show', $medicine->slug),
    'inLanguage' => $pageLocale === 'hi' ? 'hi-IN' : 'en-IN',
    'about' => [
        '@type' => 'Drug',
        'name' => $medicine->name,
        'alternateName' => $medicine->generic_name,
        'activeIngredient' => $medicine->composition,
        'isAvailableGenerically' => filled($medicine->generic_name),
    ],
    'mainEntity' => [
        '@type' => 'Drug',
        'name' => $medicine->name,
        'alternateName' => $medicine->generic_name,
        'activeIngredient' => $medicine->composition,
        'description' => $medicineDescription,
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if($faqItems->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqItems
        ->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => \App\Support\Seo::cleanText($faq['answer'], 0),
            ],
        ])
        ->values()
        ->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endsection

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-8">
        <div class="space-y-6">
            <div class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $locale === 'hi' ? 'दवा जानकारी' : 'Medicine Information' }}</p>
                        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-950 dark:text-white">{{ $medicine->name }}</h1>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $medicine->generic_name ?: ($locale === 'hi' ? 'जेनेरिक नाम उपलब्ध नहीं' : 'Generic name not available') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $medicine->prescription_required ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200' }}">
                            {{ $medicine->prescription_required ? ($locale === 'hi' ? 'प्रिस्क्रिप्शन आवश्यक' : 'Prescription required') : ($locale === 'hi' ? 'शैक्षणिक जानकारी' : 'Educational information') }}
                        </span>
                        @if($medicine->category)
                            <span class="rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-bold text-slate-700 dark:text-slate-200">{{ $medicine->category }}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-950/30 p-4">
                    <h2 class="font-bold text-amber-900 dark:text-amber-100">{{ $locale === 'hi' ? 'दवा सुरक्षा अस्वीकरण' : 'Medicine Safety Disclaimer' }}</h2>
                    <p class="mt-2 text-sm text-amber-900/90 dark:text-amber-100/90">{{ $locale === 'hi' ? 'यह जानकारी केवल शैक्षणिक उद्देश्य के लिए है और स्व-निदान या स्व-उपचार के लिए उपयोग नहीं की जानी चाहिए। किसी भी दवा को शुरू, बंद या बदलने से पहले योग्य डॉक्टर या फार्मासिस्ट से सलाह लें। ओवरडोज, गंभीर एलर्जी, सांस लेने में कठिनाई, चेहरे/होठों में सूजन, बेहोशी या गंभीर दुष्प्रभाव होने पर तुरंत आपातकालीन सहायता लें।' : 'This information is for educational purposes only and should not be used for self-diagnosis or self-treatment. Do not start, stop, or change any medicine without consulting a qualified doctor or pharmacist. In case of overdose, severe allergic reaction, breathing difficulty, swelling of the face/lips, unconsciousness, or severe side effects, seek emergency medical help immediately.' }}</p>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @if($medicine->composition)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                            <div class="text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'कंपोजिशन' : 'Composition' }}</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $medicine->composition }}</div>
                        </div>
                    @endif
                    @if($medicine->strength)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                            <div class="text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'स्ट्रेंथ' : 'Strength' }}</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $medicine->strength }}</div>
                        </div>
                    @endif
                    @if($medicine->medicine_type)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                            <div class="text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'दवा प्रकार' : 'Medicine Type' }}</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $medicine->medicine_type }}</div>
                        </div>
                    @endif
                    @if($brandItems->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                            <div class="text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'ब्रांड नाम' : 'Brand Names' }}</div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($brandItems as $brand)
                                    <span class="rounded-full border border-cyan-200 dark:border-cyan-900/60 bg-cyan-50 dark:bg-cyan-950/40 px-3 py-1 text-xs font-semibold text-cyan-700 dark:text-cyan-200">{{ $brand }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @php
            $sectionTitles = [
                'en' => [
                    'overview' => 'General Overview',
                    'uses' => 'Reported Uses',
                    'benefits' => 'General Characteristics',
                    'dosage_information' => 'General Dosing Information (Non-Prescriptive)',
                    'mechanism' => 'General Mechanism of Action',
                    'common_side_effects' => 'Reported Common Side Effects',
                    'serious_side_effects' => 'Reported Serious Side Effects (Seek Medical Care)',
                    'drug_interactions' => 'General Drug Interactions',
                    'food_interactions' => 'General Food Interactions',
                    'alcohol_warning' => 'Alcohol Interaction Information',
                    'pregnancy_warning' => 'Pregnancy Warnings',
                    'breastfeeding_warning' => 'Breastfeeding Warnings',
                    'kidney_warning' => 'Kidney Health Warnings',
                    'liver_warning' => 'Liver Health Warnings',
                    'driving_warning' => 'Driving Safety Warnings',
                    'allergy_warning' => 'Allergy Warnings',
                    'precautions' => 'General Precautions',
                    'contraindications' => 'General Contraindications',
                    'avoid_if' => 'Conditions to Avoid',
                    'missed_dose' => 'General Missed Dose Guidelines',
                    'overdose' => 'Emergency Overdose Guidelines',
                    'storage' => 'Storage Guidelines',
                    'expert_advice' => 'General Educational Notes',
                    'when_to_contact_doctor' => 'When to Consult a Medical Professional',
                ],
                'hi' => [
                    'overview' => 'सामान्य अवलोकन',
                    'uses' => 'संभावित उपयोग',
                    'benefits' => 'सामान्य विशेषताएं',
                    'dosage_information' => 'सामान्य खुराक जानकारी (गैर-पर्चे वाली)',
                    'mechanism' => 'काम करने की सामान्य प्रणाली',
                    'common_side_effects' => 'संभावित सामान्य दुष्प्रभाव',
                    'serious_side_effects' => 'संभावित गंभीर दुष्प्रभाव (चिकित्सीय सहायता लें)',
                    'drug_interactions' => 'दवाओं के साथ सामान्य परस्पर क्रिया',
                    'food_interactions' => 'भोजन के साथ सामान्य परस्पर क्रिया',
                    'alcohol_warning' => 'शराब के साथ सामान्य चेतावनी',
                    'pregnancy_warning' => 'गर्भावस्था के दौरान चेतावनी',
                    'breastfeeding_warning' => 'स्तनपान के दौरान चेतावनी',
                    'kidney_warning' => 'किडनी से जुड़ी सामान्य चेतावनी',
                    'liver_warning' => 'लिवर से जुड़ी सामान्य चेतावनी',
                    'driving_warning' => 'वाहन चलाने के संबंध में चेतावनी',
                    'allergy_warning' => 'एलर्जी की चेतावनी',
                    'precautions' => 'सामान्य सावधानियां',
                    'contraindications' => 'संभावित निषेध (Contraindications)',
                    'avoid_if' => 'किन स्थितियों में सेवन से बचें',
                    'missed_dose' => 'खुराक छूटने पर सामान्य दिशा-निर्देश',
                    'overdose' => 'आपातकालीन ओवरडोज जानकारी',
                    'storage' => 'भंडारण के सामान्य नियम',
                    'expert_advice' => 'सामान्य शैक्षणिक टिप्पणी',
                    'when_to_contact_doctor' => 'डॉक्टर से कब संपर्क करें',
                ]
            ];

            $tabKeys = [
                'overview' => ['overview', 'uses', 'benefits', 'mechanism', 'storage', 'expert_advice'],
                'dosing' => ['dosage_information', 'missed_dose', 'overdose', 'common_side_effects', 'serious_side_effects', 'when_to_contact_doctor'],
                'warnings' => ['pregnancy_warning', 'breastfeeding_warning', 'kidney_warning', 'liver_warning', 'driving_warning', 'allergy_warning', 'precautions', 'contraindications', 'avoid_if'],
                'interactions' => ['drug_interactions', 'food_interactions', 'alcohol_warning'],
            ];

            $tabContents = [];
            $activeTab = null;
            foreach ($tabKeys as $tab => $keys) {
                $tabContents[$tab] = [];
                foreach ($keys as $key) {
                    if (isset($sections[$key]) && filled($sections[$key])) {
                        $tabContents[$tab][$key] = $sections[$key];
                    }
                }
                if ($activeTab === null && count($tabContents[$tab]) > 0) {
                    $activeTab = $tab;
                }
            }
            @endphp

            <div class="border-b border-slate-200 dark:border-slate-800">
                <nav class="-mb-px flex space-x-6 overflow-x-auto pb-1" aria-label="Tabs">
                    @if(count($tabContents['overview']) > 0)
                        <button type="button" onclick="switchMedicineTab('overview')" id="med-tab-btn-overview" class="med-tab-btn whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm {{ $activeTab === 'overview' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300' }}">
                            {{ $locale === 'hi' ? 'अवलोकन' : 'Overview' }}
                        </button>
                    @endif
                    @if(count($tabContents['dosing']) > 0)
                        <button type="button" onclick="switchMedicineTab('dosing')" id="med-tab-btn-dosing" class="med-tab-btn whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm {{ $activeTab === 'dosing' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300' }}">
                            {{ $locale === 'hi' ? 'खुराक और दुष्प्रभाव' : 'Dosing & Side Effects' }}
                        </button>
                    @endif
                    @if(count($tabContents['warnings']) > 0)
                        <button type="button" onclick="switchMedicineTab('warnings')" id="med-tab-btn-warnings" class="med-tab-btn whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm {{ $activeTab === 'warnings' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300' }}">
                            {{ $locale === 'hi' ? 'चेतावनी और सावधानियां' : 'Warnings & Precautions' }}
                        </button>
                    @endif
                    @if(count($tabContents['interactions']) > 0)
                        <button type="button" onclick="switchMedicineTab('interactions')" id="med-tab-btn-interactions" class="med-tab-btn whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm {{ $activeTab === 'interactions' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300' }}">
                            {{ $locale === 'hi' ? 'परस्पर क्रिया' : 'Interactions' }}
                        </button>
                    @endif
                </nav>
            </div>

            <div class="mt-6 space-y-6">
                <!-- Overview Pane -->
                <div id="med-tab-pane-overview" class="med-tab-pane space-y-6 {{ $activeTab === 'overview' ? '' : 'hidden' }}">
                    @foreach($tabContents['overview'] as $key => $content)
                        <article class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-2.5 mb-4">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                                <h3 class="text-xl font-extrabold text-slate-950 dark:text-white">
                                    {{ $sectionTitles[$pageLocale][$key] ?? str($key)->replace('_', ' ')->title() }}
                                </h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-7 whitespace-pre-line">{{ $content }}</p>
                        </article>
                    @endforeach
                </div>

                <!-- Dosing Pane -->
                <div id="med-tab-pane-dosing" class="med-tab-pane space-y-6 {{ $activeTab === 'dosing' ? '' : 'hidden' }}">
                    @foreach($tabContents['dosing'] as $key => $content)
                        @php
                            $isSerious = ($key === 'serious_side_effects' || $key === 'overdose' || $key === 'when_to_contact_doctor');
                        @endphp
                        <article class="rounded-[1.75rem] border {{ $isSerious ? 'border-rose-200 dark:border-rose-950/80 bg-rose-50/10 dark:bg-rose-950/5' : 'border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90' }} shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-2.5 mb-4">
                                @if($isSerious)
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                                @else
                                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                                @endif
                                <h3 class="text-xl font-extrabold {{ $isSerious ? 'text-rose-950 dark:text-rose-200' : 'text-slate-950 dark:text-white' }}">
                                    {{ $sectionTitles[$pageLocale][$key] ?? str($key)->replace('_', ' ')->title() }}
                                </h3>
                            </div>
                            <p class="{{ $isSerious ? 'text-rose-900/90 dark:text-rose-200/95' : 'text-slate-600 dark:text-slate-300' }} text-sm sm:text-base leading-7 whitespace-pre-line">{{ $content }}</p>
                            @if($key === 'dosage_information')
                                <div class="mt-4 rounded-2xl border border-rose-200 dark:border-rose-900/50 bg-rose-50/90 dark:bg-rose-950/30 p-4 text-sm text-rose-900 dark:text-rose-100 flex gap-2.5">
                                    <i data-lucide="info" class="w-5 h-5 text-rose-600 shrink-0 mt-0.5"></i>
                                    <span>{{ $locale === 'hi' ? 'खुराक उम्र, वजन, मेडिकल स्थिति, अन्य दवाओं, गर्भावस्था, किडनी/लिवर की स्थिति और डॉक्टर की सलाह पर निर्भर करती है। बिना डॉक्टर या फार्मासिस्ट से पूछे दवा की खुराक शुरू, बंद या बदलें नहीं।' : 'Dosage depends on age, weight, medical condition, other medicines, pregnancy status, liver/kidney health, and doctor advice. Do not start, stop, or change any dose without consulting a qualified doctor or pharmacist.' }}</span>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                <!-- Warnings Pane -->
                <div id="med-tab-pane-warnings" class="med-tab-pane space-y-6 {{ $activeTab === 'warnings' ? '' : 'hidden' }}">
                    @foreach($tabContents['warnings'] as $key => $content)
                        @php
                            $isAllergy = ($key === 'allergy_warning' || $key === 'contraindications' || $key === 'avoid_if');
                        @endphp
                        <article class="rounded-[1.75rem] border {{ $isAllergy ? 'border-red-200 dark:border-red-950/80 bg-red-50/10 dark:bg-red-950/5' : 'border-amber-200 dark:border-amber-900/60 bg-amber-50/10 dark:bg-amber-950/5' }} shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-2.5 mb-4">
                                <i data-lucide="{{ $isAllergy ? 'octagon-alert' : 'alert-circle' }}" class="w-5 h-5 {{ $isAllergy ? 'text-red-600' : 'text-amber-600' }} shrink-0"></i>
                                <h3 class="text-xl font-extrabold {{ $isAllergy ? 'text-red-950 dark:text-red-200' : 'text-amber-950 dark:text-amber-200' }}">
                                    {{ $sectionTitles[$pageLocale][$key] ?? str($key)->replace('_', ' ')->title() }}
                                </h3>
                            </div>
                            <p class="{{ $isAllergy ? 'text-red-900/90 dark:text-red-200/95' : 'text-amber-900/90 dark:text-amber-200/95' }} text-sm sm:text-base leading-7 whitespace-pre-line">{{ $content }}</p>
                        </article>
                    @endforeach
                </div>

                <!-- Interactions Pane -->
                <div id="med-tab-pane-interactions" class="med-tab-pane space-y-6 {{ $activeTab === 'interactions' ? '' : 'hidden' }}">
                    @foreach($tabContents['interactions'] as $key => $content)
                        <article class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-2.5 mb-4">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                <h3 class="text-xl font-extrabold text-slate-950 dark:text-white">
                                    {{ $sectionTitles[$pageLocale][$key] ?? str($key)->replace('_', ' ')->title() }}
                                </h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-7 whitespace-pre-line">{{ $content }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            @if($faqItems->isNotEmpty())
                <section class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6">
                    <h2 class="text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'अक्सर पूछे जाने वाले सवाल' : 'Frequently Asked Questions' }}</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($faqItems as $faq)
                            <details class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                                <summary class="cursor-pointer font-semibold text-slate-900 dark:text-slate-100">{{ $faq['question'] ?? '' }}</summary>
                                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $faq['answer'] ?? '' }}</p>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="space-y-6 xl:sticky xl:top-28 xl:self-start">
            <section class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'अभी क्या करें' : 'What You Can Do Now' }}</h2>
                <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <li>{{ $locale === 'hi' ? 'दवा लेने से पहले लेबल और सक्रिय घटक जांचें।' : 'Check the label and active ingredient before taking any medicine.' }}</li>
                    <li>{{ $locale === 'hi' ? 'यदि आप अन्य दवाएं ले रहे हैं, तो डॉक्टर या फार्मासिस्ट से इंटरैक्शन के बारे में पूछें।' : 'Ask a doctor or pharmacist about interactions if you already take other medicines.' }}</li>
                    <li>{{ $locale === 'hi' ? 'गंभीर लक्षण, ओवरडोज या एलर्जी में तुरंत मदद लें।' : 'Seek urgent help for overdose, severe symptoms, or allergic reactions.' }}</li>
                </ul>
                <div class="mt-5 flex flex-col gap-3">
                    <a href="{{ route('doctors.index') }}" class="rounded-2xl bg-teal-600 hover:bg-teal-700 px-4 py-3 text-sm font-bold text-white text-center">{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Find Doctors' }}</a>
                    <a href="{{ route('hospitals.index') }}" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100 text-center">{{ $locale === 'hi' ? 'अस्पताल देखें' : 'View Hospitals' }}</a>
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'गलत जानकारी रिपोर्ट करें' : 'Report Incorrect Information' }}</h2>
                <form class="mt-4 space-y-3" method="POST" action="{{ route('medicines.report', $medicine->slug) }}">
                    @csrf
                    <select name="report_type" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm">
                        @foreach([
                            'Wrong use',
                            'Wrong dosage information',
                            'Wrong side effect',
                            'Wrong interaction',
                            'Wrong warning',
                            'Wrong composition',
                            'Translation issue',
                            'Other',
                        ] as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <textarea name="user_message" rows="4" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm" placeholder="{{ $locale === 'hi' ? 'क्या गलत लगता है?' : 'What seems incorrect?' }}" required></textarea>
                    <textarea name="corrected_information" rows="3" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm" placeholder="{{ $locale === 'hi' ? 'यदि सही जानकारी पता हो तो लिखें' : 'Correct information if known' }}"></textarea>
                    <input type="text" name="reporter_name" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm" placeholder="{{ $locale === 'hi' ? 'नाम (वैकल्पिक)' : 'Name (optional)' }}">
                    <input type="email" name="reporter_email" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm" placeholder="{{ $locale === 'hi' ? 'ईमेल (वैकल्पिक)' : 'Email (optional)' }}">
                    <button class="w-full rounded-2xl bg-slate-950 dark:bg-cyan-600 hover:bg-slate-800 dark:hover:bg-cyan-500 px-4 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'रिपोर्ट भेजें' : 'Submit Report' }}</button>
                </form>
            </section>

            @if($relatedMedicines->count())
                <section class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'संबंधित दवाएं' : 'Related Medicines' }}</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($relatedMedicines as $relatedMedicine)
                            <a href="{{ route('medicines.show', $relatedMedicine->slug) }}" class="block rounded-2xl border border-slate-200 dark:border-slate-800 p-4 hover:border-teal-300 dark:hover:border-teal-700">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $relatedMedicine->name }}</div>
                                <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $relatedMedicine->generic_name ?: $relatedMedicine->category }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($relatedArticles->count())
                <section class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'संबंधित लेख' : 'Related Articles' }}</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($relatedArticles as $article)
                            <a href="{{ route('articles.show', $article) }}" class="block rounded-2xl border border-slate-200 dark:border-slate-800 p-4 hover:border-teal-300 dark:hover:border-teal-700">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? ($article->title_hi ?: $article->title_en) : ($article->title_en ?: $article->title_hi) }}</div>
                                <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($locale === 'hi' ? ($article->excerpt_hi ?: $article->excerpt_en) : ($article->excerpt_en ?: $article->excerpt_hi), 90) }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </section>
</main>

@push('scripts')
<script>
    function switchMedicineTab(tabId) {
        // Hide all tab panes
        document.querySelectorAll('.med-tab-pane').forEach(pane => {
            pane.classList.add('hidden');
        });
        // Show target tab pane
        const targetPane = document.getElementById('med-tab-pane-' + tabId);
        if (targetPane) targetPane.classList.remove('hidden');

        // Reset all tab button styles
        document.querySelectorAll('.med-tab-btn').forEach(btn => {
            btn.classList.remove('border-teal-500', 'text-teal-600', 'dark:text-teal-400');
            btn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400');
        });
        // Set active tab button styles
        const activeBtn = document.getElementById('med-tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400');
            activeBtn.classList.add('border-teal-500', 'text-teal-600', 'dark:text-teal-400');
        }

        // Refresh Lucide icons in case new icons are rendered
        if (window.refreshLucideIcons) window.refreshLucideIcons();
    }
</script>
@endpush

@endsection
