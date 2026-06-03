<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Symptom;
use App\Models\SymptomTestSubmission;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DiseaseSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SymptomTestController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $symptoms = $this->buildSymptomCatalog();

        return view('pages.symptom-test', [
            'symptoms' => $symptoms,
            'locale' => $locale,
        ]);
    }

    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|in:male,female,other,unknown',
            'symptoms' => 'nullable|array|min:0|max:20',
            'symptoms.*' => 'nullable|string|max:120',
            'symptom_text' => 'nullable|string|max:1000',
        ]);

        $locale = app()->getLocale();
        $age = (int) $validated['age'];
        $gender = (string) $validated['gender'];
        $symptomText = (string) ($validated['symptom_text'] ?? '');

        $selectedSymptoms = $this->resolveSymptoms(
            collect($validated['symptoms'] ?? []),
            $symptomText
        );

        if ($selectedSymptoms->isEmpty()) {
            return response()->json([
                'message' => $locale === 'hi'
                    ? 'कृपया कम से कम एक लक्षण चुनें या लिखें।'
                    : 'Please choose or type at least one symptom.',
            ], 422);
        }

        $storedDiseases = Disease::query()
            ->with(['department', 'symptoms'])
            ->get();

        if ($storedDiseases->isNotEmpty()) {
            return $this->analyzeStoredDiseases($request, $storedDiseases, $selectedSymptoms, $age, $gender, $symptomText, $locale);
        }

        return $this->analyzeFallbackDiseases($request, $selectedSymptoms, $age, $gender, $symptomText, $locale);
    }

    private function analyzeStoredDiseases(
        Request $request,
        Collection $storedDiseases,
        Collection $selectedSymptoms,
        int $age,
        string $gender,
        string $symptomText,
        string $locale
    ): JsonResponse {
        $scoredDiseases = $storedDiseases
            ->map(function (Disease $disease) use ($selectedSymptoms, $age, $gender) {
                $diseaseSymptoms = $disease->symptoms->keyBy(fn (Symptom $symptom) => $symptom->id);
                $matchedSymptoms = $diseaseSymptoms
                    ->filter(fn (Symptom $symptom) => $selectedSymptoms->contains('id', $symptom->id))
                    ->values();

                if ($matchedSymptoms->isEmpty()) {
                    return null;
                }

                $matchedCount = $matchedSymptoms->count();
                $totalDiseaseSymptoms = max($diseaseSymptoms->count(), 1);
                $coverage = $matchedCount / $totalDiseaseSymptoms;
                $score = ($matchedCount * 35) + ($coverage * 40);
                $score += $this->getAgeSignal($age, $disease);
                $score += $this->getGenderSignal($gender, $disease);

                return [
                    'disease' => $disease,
                    'score' => round($score, 2),
                    'coverage' => round($coverage * 100, 1),
                    'matched_symptoms' => $matchedSymptoms,
                    'remaining_symptoms' => $diseaseSymptoms
                        ->reject(fn (Symptom $symptom) => $selectedSymptoms->contains('id', $symptom->id))
                        ->values(),
                ];
            })
            ->filter()
            ->sortByDesc('score')
            ->values();

        if ($scoredDiseases->isEmpty()) {
            return response()->json([
                'message' => $locale === 'hi'
                    ? 'इन लक्षणों से कोई सटीक मिलान नहीं मिला। कृपया कुछ और लक्षण जोड़ें।'
                    : 'No close match was found. Please add a few more symptoms and try again.',
            ], 200);
        }

        $topDiseases = $scoredDiseases->take(5)->values();
        $topDisease = $topDiseases->first();
        $followUpSymptoms = $this->buildStoredFollowUpSymptoms($topDiseases, $selectedSymptoms, $locale);

        $this->storeStoredSubmission($request, $age, $gender, $symptomText, $selectedSymptoms, $topDisease, $topDiseases, $followUpSymptoms, $locale);

        return response()->json([
            'message' => $locale === 'hi'
                ? 'विश्लेषण पूरा हुआ। नीचे संभावित रोग और अगले लक्षण देखें।'
                : 'Analysis complete. Review the likely conditions and next symptoms below.',
            'selected_symptoms' => $selectedSymptoms->map(fn ($symptom) => [
                'id' => $symptom['id'],
                'name' => $symptom['name'],
            ])->values(),
            'likely_conditions' => $topDiseases->map(function (array $row) {
                /** @var Disease $disease */
                $disease = $row['disease'];

                return [
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
                    'score' => $row['score'],
                    'coverage' => $row['coverage'],
                    'matched_symptoms' => $row['matched_symptoms']->map(fn (Symptom $symptom) => [
                        'id' => $symptom->id,
                        'name' => [
                            'en' => $symptom->name_en,
                            'hi' => $symptom->name_hi ?: $symptom->name_en,
                        ],
                    ])->values(),
                    'remaining_symptoms' => $row['remaining_symptoms']->take(6)->map(fn (Symptom $symptom) => [
                        'id' => $symptom->id,
                        'name' => [
                            'en' => $symptom->name_en,
                            'hi' => $symptom->name_hi ?: $symptom->name_en,
                        ],
                    ])->values(),
                ];
            })->values(),
            'next_symptoms' => $followUpSymptoms,
            'recommended_department' => $topDisease['disease']->department ? [
                'id' => $topDisease['disease']->department->id,
                'name' => [
                    'en' => $topDisease['disease']->department->name_en,
                    'hi' => $topDisease['disease']->department->name_hi,
                ],
            ] : null,
            'disclaimer' => $locale === 'hi'
                ? 'यह केवल सूचना और अगली पूछताछ के लिए है, यह अंतिम निदान नहीं है।'
                : 'This is for informational screening only and is not a final diagnosis.',
        ]);
    }

    private function analyzeFallbackDiseases(
        Request $request,
        Collection $selectedSymptoms,
        int $age,
        string $gender,
        string $symptomText,
        string $locale
    ): JsonResponse {
        $catalog = $this->fallbackDiseaseCatalog();

        $scoredDiseases = $catalog
            ->map(function (array $disease) use ($selectedSymptoms, $age, $gender) {
                $diseaseSymptoms = collect($disease['symptoms'])->keyBy('id');
                $matchedSymptoms = $diseaseSymptoms
                    ->filter(fn (array $symptom) => $selectedSymptoms->contains('id', $symptom['id']))
                    ->values();

                if ($matchedSymptoms->isEmpty()) {
                    return null;
                }

                $matchedCount = $matchedSymptoms->count();
                $totalDiseaseSymptoms = max($diseaseSymptoms->count(), 1);
                $coverage = $matchedCount / $totalDiseaseSymptoms;
                $score = ($matchedCount * 35) + ($coverage * 40);
                $score += $this->getFallbackAgeSignal($age, $disease);
                $score += $this->getFallbackGenderSignal($gender, $disease);

                return [
                    'disease' => $disease,
                    'score' => round($score, 2),
                    'coverage' => round($coverage * 100, 1),
                    'matched_symptoms' => $matchedSymptoms,
                    'remaining_symptoms' => $diseaseSymptoms
                        ->reject(fn (array $symptom) => $selectedSymptoms->contains('id', $symptom['id']))
                        ->values(),
                ];
            })
            ->filter()
            ->sortByDesc('score')
            ->values();

        if ($scoredDiseases->isEmpty()) {
            return response()->json([
                'message' => $locale === 'hi'
                    ? 'इन लक्षणों से कोई सटीक मिलान नहीं मिला। कृपया कुछ और लक्षण जोड़ें।'
                    : 'No close match was found. Please add a few more symptoms and try again.',
            ], 200);
        }

        $topDiseases = $scoredDiseases->take(5)->values();
        $topDisease = $topDiseases->first();
        $followUpSymptoms = $this->buildFallbackFollowUpSymptoms($topDiseases, $selectedSymptoms, $locale);

        SymptomTestSubmission::create([
            'session_token' => $request->session()->getId(),
            'age' => $age,
            'gender' => $gender,
            'symptom_text' => $symptomText !== '' ? $symptomText : null,
            'selected_symptoms' => $selectedSymptoms->map(fn ($symptom) => $symptom['name']['en'])->values()->all(),
            'likely_conditions' => $topDiseases->values()->all(),
            'next_symptoms' => $followUpSymptoms,
            'recommended_department_id' => null,
            'top_disease_id' => null,
            'top_score' => $topDisease['score'] ?? null,
            'locale' => $locale,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 512, ''),
        ]);

        return response()->json([
            'message' => $locale === 'hi'
                ? 'विश्लेषण पूरा हुआ। नीचे संभावित रोग और अगले लक्षण देखें।'
                : 'Analysis complete. Review the likely conditions and next symptoms below.',
            'selected_symptoms' => $selectedSymptoms->map(fn ($symptom) => [
                'id' => $symptom['id'],
                'name' => $symptom['name'],
            ])->values(),
            'likely_conditions' => $topDiseases->map(fn (array $row) => [
                'id' => $row['disease']['id'],
                'name' => $row['disease']['name'],
                'department' => $row['disease']['department'],
                'score' => $row['score'],
                'coverage' => $row['coverage'],
                'matched_symptoms' => $row['matched_symptoms']->map(fn (array $symptom) => [
                    'id' => $symptom['id'],
                    'name' => $symptom['name'],
                ])->values(),
                'remaining_symptoms' => $row['remaining_symptoms']->take(6)->map(fn (array $symptom) => [
                    'id' => $symptom['id'],
                    'name' => $symptom['name'],
                ])->values(),
            ])->values(),
            'next_symptoms' => $followUpSymptoms,
            'recommended_department' => $topDisease['disease']['department'],
            'disclaimer' => $locale === 'hi'
                ? 'यह केवल सूचना और अगली पूछताछ के लिए है, यह अंतिम निदान नहीं है।'
                : 'This is for informational screening only and is not a final diagnosis.',
        ]);
    }

    private function storeStoredSubmission(
        Request $request,
        int $age,
        string $gender,
        string $symptomText,
        Collection $selectedSymptoms,
        array $topDisease,
        Collection $topDiseases,
        array $followUpSymptoms,
        string $locale
    ): void {
        SymptomTestSubmission::create([
            'session_token' => $request->session()->getId(),
            'age' => $age,
            'gender' => $gender,
            'symptom_text' => $symptomText !== '' ? $symptomText : null,
            'selected_symptoms' => $selectedSymptoms->map(fn ($symptom) => $symptom['name']['en'])->values()->all(),
            'likely_conditions' => $topDiseases->map(function (array $row) {
                /** @var Disease $disease */
                $disease = $row['disease'];

                return [
                    'id' => $disease->id,
                    'name_en' => $disease->name_en,
                    'name_hi' => $disease->name_hi,
                    'department_id' => $disease->department_id,
                    'department_name_en' => $disease->department?->name_en,
                    'department_name_hi' => $disease->department?->name_hi,
                    'score' => $row['score'],
                    'coverage' => $row['coverage'],
                    'matched_symptoms' => $row['matched_symptoms']->map(fn (Symptom $symptom) => [
                        'id' => $symptom->id,
                        'name_en' => $symptom->name_en,
                        'name_hi' => $symptom->name_hi ?: $symptom->name_en,
                    ])->values()->all(),
                ];
            })->values()->all(),
            'next_symptoms' => $followUpSymptoms,
            'recommended_department_id' => $topDisease['disease']->department_id,
            'top_disease_id' => $topDisease['disease']->id,
            'top_score' => $topDisease['score'],
            'locale' => $locale,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 512, ''),
        ]);
    }

    private function buildSymptomCatalog(): array
    {
        $symptoms = Symptom::query()
            ->withCount('diseases')
            ->orderByDesc('diseases_count')
            ->orderBy('name_en')
            ->get()
            ->map(fn (Symptom $symptom) => [
                'id' => $symptom->id,
                'name' => [
                    'en' => $symptom->name_en,
                    'hi' => $symptom->name_hi ?: $symptom->name_en,
                ],
                'diseases_count' => $symptom->diseases_count,
            ])
            ->values();

        if ($symptoms->isNotEmpty()) {
            return $symptoms->take(60)->all();
        }

        return $this->fallbackSymptomCatalog()
            ->sortByDesc('diseases_count')
            ->take(60)
            ->values()
            ->all();
    }

    private function resolveSymptoms(Collection $symptomInputs, string $symptomText): Collection
    {
        $storedSymptoms = Symptom::query()->get();

        if ($storedSymptoms->isEmpty()) {
            return $this->resolveFromCatalog(
                $this->fallbackSymptomCatalog()->map(fn (array $symptom) => [
                    'id' => $symptom['id'],
                    'name' => $symptom['name'],
                ]),
                $symptomInputs,
                $symptomText
            );
        }

        return $this->resolveFromCatalog(
            $storedSymptoms->map(fn (Symptom $symptom) => [
                'id' => $symptom->id,
                'name' => [
                    'en' => $symptom->name_en,
                    'hi' => $symptom->name_hi ?: $symptom->name_en,
                ],
            ]),
            $symptomInputs,
            $symptomText
        );
    }

    private function resolveFromCatalog(Collection $catalog, Collection $symptomInputs, string $symptomText): Collection
    {
        $lookup = $catalog->keyBy(fn (array $symptom) => mb_strtolower(trim($symptom['name']['en'])));
        $resolved = collect();

        foreach ($symptomInputs as $symptomInput) {
            $symptomName = trim((string) $symptomInput);
            if ($symptomName === '') {
                continue;
            }

            $normalizedName = mb_strtolower($symptomName);
            $match = $lookup->get($normalizedName)
                ?? $catalog->first(function (array $symptom) use ($normalizedName) {
                    return str_contains(mb_strtolower($symptom['name']['en']), $normalizedName)
                        || str_contains(mb_strtolower($symptom['name']['hi']), $normalizedName);
                });

            if ($match) {
                $resolved->push($match);
            }
        }

        if (trim($symptomText) !== '') {
            $normalizedText = $this->normalizeText($symptomText);

            foreach ($catalog as $symptom) {
                $englishName = $this->normalizeText($symptom['name']['en']);
                $hindiName = $this->normalizeText($symptom['name']['hi']);

                if (
                    ($englishName !== '' && Str::contains($normalizedText, $englishName))
                    || ($hindiName !== '' && Str::contains($normalizedText, $hindiName))
                ) {
                    $resolved->push($symptom);
                }
            }
        }

        return $resolved->unique('id')->values();
    }

    private function normalizeText(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    private function getAgeSignal(int $age, Disease $disease): float
    {
        $haystack = mb_strtolower(implode(' ', array_filter([
            $disease->name_en,
            $disease->name_hi,
            $disease->department?->name_en,
            $disease->department?->name_hi,
        ])));

        return $this->calculateDemographicSignal($age, $haystack, null);
    }

    private function getGenderSignal(string $gender, Disease $disease): float
    {
        $haystack = mb_strtolower(implode(' ', array_filter([
            $disease->name_en,
            $disease->name_hi,
            $disease->department?->name_en,
            $disease->department?->name_hi,
        ])));

        return $this->calculateDemographicSignal(null, $haystack, $gender);
    }

    private function getFallbackAgeSignal(int $age, array $disease): float
    {
        $haystack = mb_strtolower(implode(' ', array_filter([
            $disease['name']['en'] ?? null,
            $disease['name']['hi'] ?? null,
            $disease['department']['name']['en'] ?? null,
            $disease['department']['name']['hi'] ?? null,
        ])));

        return $this->calculateDemographicSignal($age, $haystack, null);
    }

    private function getFallbackGenderSignal(string $gender, array $disease): float
    {
        $haystack = mb_strtolower(implode(' ', array_filter([
            $disease['name']['en'] ?? null,
            $disease['name']['hi'] ?? null,
            $disease['department']['name']['en'] ?? null,
            $disease['department']['name']['hi'] ?? null,
        ])));

        return $this->calculateDemographicSignal(null, $haystack, $gender);
    }

    private function calculateDemographicSignal(?int $age, string $haystack, ?string $gender): float
    {
        $signal = 0.0;

        if ($age !== null) {
            if ($age < 18 && Str::contains($haystack, ['pediatric', 'children', 'child', 'neonatal', 'growth'])) {
                $signal += 18;
            }

            if ($age >= 60 && Str::contains($haystack, ['geriatrics', 'dementia', 'parkinson', 'stroke', 'cardiac', 'heart', 'kidney'])) {
                $signal += 10;
            }

            if ($age >= 18 && $age <= 45 && Str::contains($haystack, ['obstetrics', 'gynecology', 'pregnancy', 'menstrual', 'ovarian', 'uterine'])) {
                $signal += 10;
            }
        }

        if ($gender === 'female' && Str::contains($haystack, ['pregnancy', 'menstrual', 'ovarian', 'uterine', 'vaginal', 'cervical', 'breast', 'gynecology'])) {
            $signal += 14;
        }

        if ($gender === 'male' && Str::contains($haystack, ['prostate', 'testicular', 'erectile', 'hydrocele', 'male infertility', 'andrology'])) {
            $signal += 14;
        }

        return $signal;
    }

    private function buildStoredFollowUpSymptoms(Collection $topDiseases, Collection $selectedSymptoms, string $locale): array
    {
        $symptomHits = collect();

        foreach ($topDiseases as $row) {
            /** @var Disease $disease */
            $disease = $row['disease'];

            foreach ($disease->symptoms as $symptom) {
                if ($selectedSymptoms->contains('id', $symptom->id)) {
                    continue;
                }

                $entry = $symptomHits->firstWhere('id', $symptom->id);

                if ($entry) {
                    $entry['count']++;
                    $entry['diseases'][] = $disease->name_en;
                    $symptomHits = $symptomHits->reject(fn ($item) => $item['id'] === $symptom->id)->values();
                    $symptomHits->push($entry);
                } else {
                    $symptomHits->push([
                        'id' => $symptom->id,
                        'name' => [
                            'en' => $symptom->name_en,
                            'hi' => $symptom->name_hi ?: $symptom->name_en,
                        ],
                        'count' => 1,
                        'diseases' => [$disease->name_en],
                    ]);
                }
            }
        }

        return $this->formatFollowUpSymptoms($symptomHits, $locale);
    }

    private function buildFallbackFollowUpSymptoms(Collection $topDiseases, Collection $selectedSymptoms, string $locale): array
    {
        $symptomHits = collect();

        foreach ($topDiseases as $row) {
            foreach ($row['disease']['symptoms'] as $symptom) {
                if ($selectedSymptoms->contains('id', $symptom['id'])) {
                    continue;
                }

                $entry = $symptomHits->firstWhere('id', $symptom['id']);

                if ($entry) {
                    $entry['count']++;
                    $entry['diseases'][] = $row['disease']['name']['en'];
                    $symptomHits = $symptomHits->reject(fn ($item) => $item['id'] === $symptom['id'])->values();
                    $symptomHits->push($entry);
                } else {
                    $symptomHits->push([
                        'id' => $symptom['id'],
                        'name' => $symptom['name'],
                        'count' => 1,
                        'diseases' => [$row['disease']['name']['en']],
                    ]);
                }
            }
        }

        return $this->formatFollowUpSymptoms($symptomHits, $locale);
    }

    private function formatFollowUpSymptoms(Collection $symptomHits, string $locale): array
    {
        return $symptomHits
            ->sortByDesc('count')
            ->take(8)
            ->values()
            ->map(function (array $entry) use ($locale) {
                return [
                    'id' => $entry['id'],
                    'name' => $entry['name'],
                    'count' => $entry['count'],
                    'label' => $locale === 'hi' ? $entry['name']['hi'] : $entry['name']['en'],
                    'supporting_conditions' => array_values(array_unique(array_slice($entry['diseases'], 0, 3))),
                ];
            })
            ->all();
    }

    private function fallbackSymptomCatalog(): Collection
    {
        static $catalog = null;

        if ($catalog !== null) {
            return $catalog;
        }

        $symptomCounts = [];

        foreach (DiseaseSeeder::diseasesByDepartment() as $diseases) {
            foreach ($diseases as $diseaseName) {
                foreach (DiseaseSeeder::symptomsForDisease($diseaseName) as $symptomName) {
                    $key = mb_strtolower($symptomName);

                    if (!isset($symptomCounts[$key])) {
                        $symptomCounts[$key] = [
                            'key' => $key,
                            'name' => [
                                'en' => $symptomName,
                                'hi' => DiseaseSeeder::hindiNameFor($symptomName),
                            ],
                            'diseases_count' => 0,
                        ];
                    }

                    $symptomCounts[$key]['diseases_count']++;
                }
            }
        }

        $catalog = collect(array_values($symptomCounts))
            ->sortBy(fn (array $symptom) => [$symptom['diseases_count'] * -1, $symptom['name']['en']])
            ->values()
            ->map(function (array $symptom, int $index) {
                return [
                    'id' => $index + 1,
                    'name' => $symptom['name'],
                    'diseases_count' => $symptom['diseases_count'],
                ];
            });

        return $catalog;
    }

    private function fallbackDiseaseCatalog(): Collection
    {
        $symptomsByName = $this->fallbackSymptomCatalog()
            ->keyBy(fn (array $symptom) => mb_strtolower($symptom['name']['en']));

        return collect(DiseaseSeeder::diseasesByDepartment())
            ->flatMap(function (array $diseases, string $departmentName) use ($symptomsByName) {
                return collect($diseases)->map(function (string $diseaseName) use ($departmentName, $symptomsByName) {
                    $symptoms = collect(DiseaseSeeder::symptomsForDisease($diseaseName))
                        ->map(fn (string $symptomName) => $symptomsByName->get(mb_strtolower($symptomName)))
                        ->filter()
                        ->values()
                        ->map(fn (array $symptom) => [
                            'id' => $symptom['id'],
                            'name' => $symptom['name'],
                        ])
                        ->all();

                    return [
                        'id' => 'fallback-' . Str::slug($departmentName . '-' . $diseaseName),
                        'name' => [
                            'en' => $diseaseName,
                            'hi' => DiseaseSeeder::hindiNameFor($diseaseName),
                        ],
                        'department' => [
                            'id' => null,
                            'name' => [
                                'en' => $departmentName,
                                'hi' => DepartmentSeeder::hindiNameFor($departmentName),
                            ],
                        ],
                        'symptoms' => $symptoms,
                    ];
                });
            })
            ->values();
    }
}
