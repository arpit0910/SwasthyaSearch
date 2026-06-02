<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\SymptomTestSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SymptomTestController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $symptoms = $this->buildSymptomCatalog($locale);

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

        $selectedSymptoms = $this->resolveSymptoms(
            collect($validated['symptoms'] ?? []),
            (string) ($validated['symptom_text'] ?? '')
        );

        if ($selectedSymptoms->isEmpty()) {
            return response()->json([
                'message' => $locale === 'hi'
                    ? 'कृपया कम से कम एक लक्षण चुनें या लिखें।'
                    : 'Please choose or type at least one symptom.',
            ], 422);
        }

        $allDiseases = Disease::query()
            ->with(['department', 'symptoms'])
            ->get();

        $scoredDiseases = $allDiseases
            ->map(function (Disease $disease) use ($selectedSymptoms, $age, $gender) {
                $diseaseSymptoms = $disease->symptoms->keyBy(fn (Symptom $symptom) => $symptom->id);
                $matchedSymptoms = $diseaseSymptoms->filter(function (Symptom $symptom) use ($selectedSymptoms) {
                    return $selectedSymptoms->contains('id', $symptom->id);
                })->values();

                if ($matchedSymptoms->isEmpty()) {
                    return null;
                }

                $matchedCount = $matchedSymptoms->count();
                $totalDiseaseSymptoms = max($diseaseSymptoms->count(), 1);
                $coverage = $matchedCount / $totalDiseaseSymptoms;
                $score = ($matchedCount * 35) + ($coverage * 40);
                $score += $this->getAgeSignal($age, $disease);
                $score += $this->getGenderSignal($gender, $disease);

                $remainingSymptoms = $diseaseSymptoms
                    ->reject(fn (Symptom $symptom) => $selectedSymptoms->contains('id', $symptom->id))
                    ->values();

                return [
                    'disease' => $disease,
                    'score' => round($score, 2),
                    'matched_symptoms' => $matchedSymptoms,
                    'remaining_symptoms' => $remainingSymptoms,
                    'coverage' => round($coverage * 100, 1),
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

        $followUpSymptoms = $this->buildFollowUpSymptoms($topDiseases, $selectedSymptoms, $locale);

        $this->storeSubmission(
            request: $request,
            age: $age,
            gender: $gender,
            symptomText: (string) ($validated['symptom_text'] ?? ''),
            selectedSymptoms: $selectedSymptoms,
            topDisease: $topDisease['disease'] ?? null,
            topScore: $topDisease['score'] ?? null,
            likelyConditions: $topDiseases,
            nextSymptoms: $followUpSymptoms,
            locale: $locale
        );

        return response()->json([
            'message' => $locale === 'hi'
                ? 'विश्लेषण पूरा हुआ। नीचे संभावित रोग और अगले लक्षण देखें।'
                : 'Analysis complete. Review the likely conditions and next symptoms below.',
            'selected_symptoms' => $selectedSymptoms->map(fn ($symptom) => [
                'id' => $symptom['id'],
                'name' => $symptom['name'],
            ])->values(),
            'likely_conditions' => $topDiseases->map(function (array $row) use ($locale) {
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
                            'hi' => $symptom->name_hi,
                        ],
                    ])->values(),
                    'remaining_symptoms' => $row['remaining_symptoms']->take(6)->map(fn (Symptom $symptom) => [
                        'id' => $symptom->id,
                        'name' => [
                            'en' => $symptom->name_en,
                            'hi' => $symptom->name_hi,
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

    private function storeSubmission(
        Request $request,
        int $age,
        string $gender,
        string $symptomText,
        Collection $selectedSymptoms,
        ?Disease $topDisease,
        ?float $topScore,
        Collection $likelyConditions,
        array $nextSymptoms,
        string $locale
    ): void {
        SymptomTestSubmission::create([
            'session_token' => $request->session()->getId(),
            'age' => $age,
            'gender' => $gender,
            'symptom_text' => $symptomText !== '' ? $symptomText : null,
            'selected_symptoms' => $selectedSymptoms->values()->all(),
            'likely_conditions' => $likelyConditions->map(function (array $row) {
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
                        'name_hi' => $symptom->name_hi,
                    ])->values()->all(),
                ];
            })->values()->all(),
            'next_symptoms' => $nextSymptoms,
            'recommended_department_id' => $topDisease?->department_id,
            'top_disease_id' => $topDisease?->id,
            'top_score' => $topScore,
            'locale' => $locale,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 512, ''),
        ]);
    }

    private function buildSymptomCatalog(string $locale): array
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
            ->all();

        if (!empty($symptoms)) {
            return array_slice($symptoms, 0, 60);
        }

        return collect($this->fallbackSymptoms())->map(fn (array $symptom, int $index) => [
            'id' => $index + 1,
            'name' => $symptom,
            'diseases_count' => 0,
        ])->all();
    }

    private function fallbackSymptoms(): array
    {
        return [
            ['en' => 'Fever', 'hi' => 'बुखार'],
            ['en' => 'Cough', 'hi' => 'खांसी'],
            ['en' => 'Headache', 'hi' => 'सिरदर्द'],
            ['en' => 'Fatigue', 'hi' => 'थकान'],
            ['en' => 'Sore Throat', 'hi' => 'गले में दर्द'],
            ['en' => 'Shortness of Breath', 'hi' => 'सांस फूलना'],
            ['en' => 'Chest Pain', 'hi' => 'छाती में दर्द'],
            ['en' => 'Stomach Pain', 'hi' => 'पेट दर्द'],
            ['en' => 'Nausea', 'hi' => 'मतली'],
            ['en' => 'Vomiting', 'hi' => 'उल्टी'],
            ['en' => 'Diarrhea', 'hi' => 'दस्त'],
            ['en' => 'Dizziness', 'hi' => 'चक्कर आना'],
            ['en' => 'Joint Pain', 'hi' => 'जोड़ों का दर्द'],
            ['en' => 'Skin Rash', 'hi' => 'त्वचा पर चकत्ते'],
            ['en' => 'Runny Nose', 'hi' => 'नाक बहना'],
            ['en' => 'Burning Urination', 'hi' => 'पेशाब में जलन'],
            ['en' => 'Loss of Appetite', 'hi' => 'भूख न लगना'],
            ['en' => 'Palpitations', 'hi' => 'दिल की धड़कन तेज होना'],
        ];
    }

    private function resolveSymptoms(Collection $symptomInputs, string $symptomText): Collection
    {
        $symptomLookup = Symptom::query()->get()->keyBy(function (Symptom $symptom) {
            return mb_strtolower(trim($symptom->name_en));
        });

        $resolved = collect();

        foreach ($symptomInputs as $symptomInput) {
            $symptomName = trim((string) $symptomInput);
            if ($symptomName === '') {
                continue;
            }

            $match = $symptomLookup->get(mb_strtolower($symptomName));
            if (! $match) {
                $match = $symptomLookup->first(function (Symptom $symptom) use ($symptomName) {
                    return mb_stripos($symptom->name_en, $symptomName) !== false
                        || ($symptom->name_hi && mb_stripos($symptom->name_hi, $symptomName) !== false);
                });
            }

            if ($match) {
                $resolved->push([
                    'id' => $match->id,
                    'name' => [
                        'en' => $match->name_en,
                        'hi' => $match->name_hi ?: $match->name_en,
                    ],
                ]);
            }
        }

        if (trim($symptomText) !== '') {
            $normalizedText = $this->normalizeText($symptomText);

            foreach ($symptomLookup as $symptom) {
                $englishName = $this->normalizeText($symptom->name_en);
                $hindiName = $this->normalizeText((string) $symptom->name_hi);

                if (
                    $englishName !== '' && Str::contains($normalizedText, $englishName)
                    || ($hindiName !== '' && Str::contains($normalizedText, $hindiName))
                ) {
                    $resolved->push([
                        'id' => $symptom->id,
                        'name' => [
                            'en' => $symptom->name_en,
                            'hi' => $symptom->name_hi ?: $symptom->name_en,
                        ],
                    ]);
                }
            }
        }

        return $resolved
            ->unique('id')
            ->values();
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

        $signal = 0.0;

        if ($age < 18 && Str::contains($haystack, ['pediatric', 'children', 'child', 'neonatal', 'growth'])) {
            $signal += 18;
        }

        if ($age >= 60 && Str::contains($haystack, ['geriatrics', 'dementia', 'parkinson', 'stroke', 'cardiac', 'heart', 'kidney'])) {
            $signal += 10;
        }

        if ($age >= 18 && $age <= 45 && Str::contains($haystack, ['obstetrics', 'gynecology', 'pregnancy', 'menstrual', 'ovarian', 'uterine'])) {
            $signal += 10;
        }

        return $signal;
    }

    private function getGenderSignal(string $gender, Disease $disease): float
    {
        $haystack = mb_strtolower(implode(' ', array_filter([
            $disease->name_en,
            $disease->name_hi,
            $disease->department?->name_en,
            $disease->department?->name_hi,
        ])));

        $signal = 0.0;

        if ($gender === 'female' && Str::contains($haystack, ['pregnancy', 'menstrual', 'ovarian', 'uterine', 'vaginal', 'cervical', 'breast', 'gynecology'])) {
            $signal += 14;
        }

        if ($gender === 'male' && Str::contains($haystack, ['prostate', 'testicular', 'erectile', 'hydrocele', 'male infertility', 'andrology'])) {
            $signal += 14;
        }

        return $signal;
    }

    private function buildFollowUpSymptoms(Collection $topDiseases, Collection $selectedSymptoms, string $locale): array
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
}
