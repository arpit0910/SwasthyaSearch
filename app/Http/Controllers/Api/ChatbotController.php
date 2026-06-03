<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\BloodBank;
use App\Models\CachedMedicalQuestion;
use App\Models\ChatSession;
use App\Models\ChatbotFailedQuery;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\GeneralQuestion;
use App\Models\Faq;
use App\Models\Hospital;
use App\Models\Medicine;
use App\Services\MedicalQaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ChatbotController extends Controller
{
    public function __construct(private readonly MedicalQaService $medicalQaService) {}

    public function handleMessage(Request $request)
    {
        try {
        $activeCity = config('healthcare.active_city', 'Jaipur');
        $validated = $request->validate([
            'session_token' => 'nullable|string',
            'message' => 'nullable|string',
            'city' => 'nullable|string|max:120',
            'locale' => 'nullable|in:en,hi',
            'load_type' => 'nullable|string|in:doctors,hospitals,articles',
        ]);

        $sessionToken = $validated['session_token'] ?? Str::random(32);
        $userMessage = isset($validated['message']) ? trim($validated['message']) : '';
        $searchTokens = $this->extractSearchTokens($userMessage);
        $locale = $validated['locale'] ?? app()->getLocale();

        $chatSession = ChatSession::firstOrCreate(
            ['session_token' => $sessionToken],
            ['messages' => []]
        );

        $messages = $chatSession->messages ?? [];

        $cityOptions = [$activeCity];
        $selectedCity = $activeCity;

        // Always try GeneralQuestion module first for conversational/help queries
        // before any directory-loading or AI-powered medical flow.
        if ($userMessage !== '') {
            $generalHelpPayload = $this->findGeneralHelpResponse($userMessage, $locale, $selectedCity, $messages);
            if ($generalHelpPayload !== null) {
                $messages[] = [
                    'sender' => 'user',
                    'text' => $userMessage,
                    'city' => $selectedCity !== '' ? $selectedCity : null,
                    'locale' => $locale,
                    'timestamp' => now()->toIso8601String(),
                ];

                $messages[] = [
                    'sender' => 'bot',
                    'text' => $generalHelpPayload['text'],
                    'qa_answer' => $generalHelpPayload['qa_answer'],
                    'city' => $selectedCity !== '' ? $selectedCity : null,
                    'locale' => $locale,
                    'show_options' => true,
                    'response_mode' => 'general_help',
                    'suggest_details' => (bool) $generalHelpPayload['suggest_details'],
                    'timestamp' => now()->toIso8601String(),
                ];

                $chatSession->update(['messages' => $messages]);

                return response()->json([
                    'session_token' => $sessionToken,
                    'reply' => $generalHelpPayload['text'],
                    'city' => $selectedCity,
                    'city_options' => $cityOptions,
                    'locale' => $locale,
                    'qa_answer' => $generalHelpPayload['qa_answer'],
                    'symptom_match' => false,
                    'department_info' => null,
                    'doctors' => [],
                    'hospitals' => [],
                    'articles' => [],
                    'see_all_doctors_url' => route('doctors.index'),
                    'see_all_hospitals_url' => route('hospitals.index'),
                    'see_all_articles_url' => route('articles.index'),
                    'suggest_details' => (bool) $generalHelpPayload['suggest_details'],
                    'history' => $messages,
                ]);
            }

            $medicinePayload = $this->findMedicineResponse($userMessage, $locale);
            if ($medicinePayload !== null) {
                $messages[] = [
                    'sender' => 'user',
                    'text' => $userMessage,
                    'city' => $selectedCity !== '' ? $selectedCity : null,
                    'locale' => $locale,
                    'timestamp' => now()->toIso8601String(),
                ];

                $messages[] = [
                    'sender' => 'bot',
                    'text' => $medicinePayload['text'],
                    'city' => $selectedCity !== '' ? $selectedCity : null,
                    'locale' => $locale,
                    'show_options' => true,
                    'response_mode' => 'medicine_help',
                    'timestamp' => now()->toIso8601String(),
                ];

                $chatSession->update(['messages' => $messages]);

                return response()->json([
                    'session_token' => $sessionToken,
                    'reply' => $medicinePayload['text'],
                    'city' => $selectedCity,
                    'city_options' => $cityOptions,
                    'locale' => $locale,
                    'qa_answer' => null,
                    'symptom_match' => false,
                    'department_info' => null,
                    'doctors' => [],
                    'hospitals' => [],
                    'articles' => [],
                    'see_all_doctors_url' => route('doctors.index'),
                    'see_all_hospitals_url' => route('hospitals.index'),
                    'see_all_articles_url' => route('articles.index'),
                    'suggest_details' => false,
                    'history' => $messages,
                ]);
            }
        }

        if (false && !empty($cityOptions) && ($selectedCity === '' || !in_array($selectedCity, $cityOptions, true))) {
            $cityPrompt = $locale === 'hi'
                ? 'कृपया सूची में से अपना शहर चुनें ताकि मैं सही डॉक्टर और अस्पताल दिखा सकूं।'
                : 'Please choose your city from the list so I can show accurate doctors and hospitals.';

            $messages[] = ['sender' => 'user', 'text' => $userMessage, 'timestamp' => now()->toIso8601String()];
            $messages[] = ['sender' => 'bot', 'text' => $cityPrompt, 'needs_city' => true, 'city_options' => $cityOptions, 'timestamp' => now()->toIso8601String()];
            $chatSession->update(['messages' => $messages]);

            return response()->json([
                'session_token' => $sessionToken,
                'reply' => $cityPrompt,
                'needs_city' => true,
                'city_options' => $cityOptions,
                'history' => $messages,
            ]);
        }
        $loadType = $validated['load_type'] ?? null;
        if ($userMessage === '' && !$loadType) {
            return response()->json([
                'session_token' => $sessionToken,
                'reply' => '',
                'city' => $selectedCity,
                'city_options' => $cityOptions,
                'locale' => $locale,
                'history' => $messages,
            ]);
        }

        if ($loadType) {
            $lastUserMessageObj = collect($messages)->reverse()->first(fn($msg) => isset($msg['sender']) && $msg['sender'] === 'user' && !empty($msg['text']));
            $originalMessage = $lastUserMessageObj ? trim($lastUserMessageObj['text']) : '';
            $searchTokens = $this->extractSearchTokens($originalMessage);

            $qaAnswer = null;
            if ($originalMessage !== '') {
                $qaAnswer = $this->medicalQaService->findBestAnswer($originalMessage, $locale);
            }
            $grokDepartment = $qaAnswer ? ($qaAnswer['category'] ?? null) : null;
            $grokDepartment = is_string($grokDepartment) ? trim($grokDepartment) : $grokDepartment;

            $matchedDeptId = null;
            if (is_string($grokDepartment) && $grokDepartment !== '') {
                $cleanedGrokDept = trim($grokDepartment);
                $matchedDept = Department::where('is_active', true)
                    ->where(function ($query) use ($cleanedGrokDept) {
                        $query->where('name_en', 'LIKE', $cleanedGrokDept)
                            ->orWhere('name_hi', 'LIKE', $cleanedGrokDept)
                            ->orWhereRaw('LOWER(name_en) = ?', [strtolower($cleanedGrokDept)])
                            ->orWhereRaw('LOWER(name_hi) = ?', [strtolower($cleanedGrokDept)])
                            ->orWhere('name_en', 'LIKE', "%{$cleanedGrokDept}%")
                            ->orWhere('name_hi', 'LIKE', "%{$cleanedGrokDept}%");
                    })
                    ->first();

                if (! $matchedDept) {
                    $lowerGrokDept = strtolower($cleanedGrokDept);
                    $allDepts = Department::where('is_active', true)->get();
                    foreach ($allDepts as $dept) {
                        if (
                            stripos($lowerGrokDept, strtolower($dept->name_en)) !== false ||
                            ($dept->name_hi && stripos($lowerGrokDept, strtolower($dept->name_hi)) !== false)
                        ) {
                            $matchedDept = $dept;
                            break;
                        }
                    }
                }

                if ($matchedDept) {
                    $matchedDeptId = $matchedDept->id;
                }
            }

            if (! $matchedDeptId) {
                $allDiseases = Disease::with('department')->get();
                foreach ($allDiseases as $disease) {
                    $diseaseNameEn = trim((string) $disease->name_en);
                    $diseaseNameHi = trim((string) ($disease->name_hi ?? ''));

                    if (
                        $diseaseNameEn !== '' &&
                        (stripos($originalMessage, $diseaseNameEn) !== false || stripos($diseaseNameEn, $originalMessage) !== false)
                    ) {
                        $matchedDeptId = $disease->department_id;
                        break;
                    }
                    if (
                        $diseaseNameHi !== '' &&
                        (mb_stripos($originalMessage, $diseaseNameHi) !== false || mb_stripos($diseaseNameHi, $originalMessage) !== false)
                    ) {
                        $matchedDeptId = $disease->department_id;
                        break;
                    }
                }
            }

            if (! $matchedDeptId) {
                $allDepartments = Department::where('is_active', true)->get();
                foreach ($allDepartments as $dept) {
                    $deptNameEn = trim((string) $dept->name_en);
                    $deptNameHi = trim((string) ($dept->name_hi ?? ''));

                    if (
                        $deptNameEn !== '' &&
                        (stripos($originalMessage, $deptNameEn) !== false || stripos($deptNameEn, $originalMessage) !== false)
                    ) {
                        $matchedDeptId = $dept->id;
                        break;
                    }
                    if (
                        $deptNameHi !== '' &&
                        (mb_stripos($originalMessage, $deptNameHi) !== false || mb_stripos($deptNameHi, $originalMessage) !== false)
                    ) {
                        $matchedDeptId = $dept->id;
                        break;
                    }
                }
            }

            $botReply = '';
            $newMsg = [
                'sender' => 'bot',
                'city' => $selectedCity,
                'locale' => $locale,
                'show_options' => true,
                'timestamp' => now()->toIso8601String(),
            ];

            if ($loadType === 'doctors') {
                $doctors = collect();
                if ($matchedDeptId) {
                    $doctors = Doctor::where('department_id', $matchedDeptId)
                        ->with(['department', 'hospitals'])
                        ->where('is_verified', true)
                        ->whereHas('hospitals', fn($q) => $q->where('city', $selectedCity))
                        ->take(3)
                        ->get();
                }

                if ($doctors->isEmpty()) {
                    $doctors = Doctor::with(['department', 'hospitals'])
                        ->where('is_verified', true)
                        ->whereHas('hospitals', fn($q) => $q->where('city', $selectedCity))
                        ->where(function ($query) use ($originalMessage, $searchTokens) {
                            if (! empty($searchTokens)) {
                                foreach ($searchTokens as $token) {
                                    $query->orWhere('first_name', 'LIKE', "%{$token}%")
                                        ->orWhere('last_name', 'LIKE', "%{$token}%")
                                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$token}%"])
                                        ->orWhere('about_en', 'LIKE', "%{$token}%")
                                        ->orWhere('about_hi', 'LIKE', "%{$token}%");
                                }
                            } else {
                                $query->where('first_name', 'LIKE', "%{$originalMessage}%")
                                    ->orWhere('last_name', 'LIKE', "%{$originalMessage}%")
                                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$originalMessage}%"]);
                            }
                        })
                        ->take(3)
                        ->get();
                }

                $deptForFilter = $matchedDeptId ?: (optional($doctors->first())->department_id ?? 'All');
                $seeAllDoctorsUrl = route('doctors.index');

                $botReply = $locale === 'hi'
                    ? "I found some recommended specialist doctors in {$selectedCity}:"
                    : "I have found some recommended specialist doctors in {$selectedCity}:";

                $newMsg['text'] = $botReply;
                $newMsg['doctors'] = $doctors;
                $newMsg['see_all_doctors_url'] = $seeAllDoctorsUrl;

            } elseif ($loadType === 'hospitals') {
                $hospitals = Hospital::where('is_verified', true)
                    ->where('city', $selectedCity)
                    ->where(function ($query) use ($originalMessage, $searchTokens) {
                        if (! empty($searchTokens)) {
                            foreach ($searchTokens as $token) {
                                $query->orWhere('name_en', 'LIKE', "%{$token}%")
                                    ->orWhere('name_hi', 'LIKE', "%{$token}%")
                                    ->orWhere('address', 'LIKE', "%{$token}%")
                                    ->orWhere('type', 'LIKE', "%{$token}%");
                            }
                        } else {
                            $query->where('name_en', 'LIKE', "%{$originalMessage}%")
                                ->orWhere('name_hi', 'LIKE', "%{$originalMessage}%")
                                ->orWhere('address', 'LIKE', "%{$originalMessage}%")
                                ->orWhere('type', 'LIKE', "%{$originalMessage}%");
                        }
                    })
                    ->take(3)
                    ->get();

                if ($hospitals->isEmpty()) {
                    $hospitals = Hospital::where('is_verified', true)->where('city', $selectedCity)->latest()->take(3)->get();
                }

                $seeAllHospitalsUrl = route('hospitals.index');

                $botReply = $locale === 'hi'
                    ? "I found the following hospitals and clinics in {$selectedCity}:"
                    : "I have found the following hospitals and clinics in {$selectedCity}:";

                $newMsg['text'] = $botReply;
                $newMsg['hospitals'] = $hospitals;
                $newMsg['see_all_hospitals_url'] = $seeAllHospitalsUrl;

            } elseif ($loadType === 'articles') {
                $articles = Article::where('is_published', true)
                    ->where(function ($query) use ($originalMessage) {
                        $query->where('title_en', 'LIKE', "%{$originalMessage}%")
                            ->orWhere('title_hi', 'LIKE', "%{$originalMessage}%")
                            ->orWhere('content_en', 'LIKE', "%{$originalMessage}%")
                            ->orWhere('content_hi', 'LIKE', "%{$originalMessage}%");
                    })
                    ->take(3)
                    ->get();

                if ($articles->isEmpty()) {
                    $articles = Article::where('is_published', true)->latest()->take(3)->get();
                }

                $seeAllArticlesUrl = route('articles.index', ['category' => $grokDepartment ?: 'All']);

                $botReply = $locale === 'hi'
                    ? "Here are some health articles that might be helpful for you:"
                    : "Here are some health articles that might be helpful for you:";

                $newMsg['text'] = $botReply;
                $newMsg['articles'] = $articles;
                $newMsg['see_all_articles_url'] = $seeAllArticlesUrl;
            }

            $messages[] = $newMsg;
            $chatSession->update(['messages' => $messages]);

            return response()->json([
                'session_token' => $sessionToken,
                'reply' => $botReply,
                'city' => $selectedCity,
                'locale' => $locale,
                'history' => $messages,
            ]);
        }

        $messages[] = [
            'sender' => 'user',
            'text' => $userMessage,
            'city' => $selectedCity,
            'locale' => $locale,
            'timestamp' => now()->toIso8601String(),
        ];

        // Directory-first routing layer to reduce AI usage and return exact entity matches quickly.
        // If user is searching names/departments/providers, we should avoid AI calls.
        if ($this->shouldRouteToDirectory($userMessage, $searchTokens, $selectedCity)) {
            $directoryPayload = $this->buildDirectoryFirstResponse($originalMessage = $userMessage, $searchTokens, $selectedCity, $locale);
            if ($directoryPayload !== null) {
                $messages[] = array_merge([
                    'sender' => 'bot',
                    'city' => $selectedCity,
                    'locale' => $locale,
                    'timestamp' => now()->toIso8601String(),
                    'response_mode' => 'directory_first',
                ], $directoryPayload);

                $chatSession->update(['messages' => $messages]);

                return response()->json([
                    'session_token' => $sessionToken,
                    'reply' => $directoryPayload['text'] ?? '',
                    'city' => $selectedCity,
                    'city_options' => $cityOptions,
                    'locale' => $locale,
                    'qa_answer' => null,
                    'symptom_match' => false,
                    'department_info' => $directoryPayload['department_info'] ?? null,
                    'doctors' => $directoryPayload['doctors'] ?? [],
                    'hospitals' => $directoryPayload['hospitals'] ?? [],
                    'blood_banks' => $directoryPayload['blood_banks'] ?? [],
                    'articles' => [],
                    'see_all_doctors_url' => $directoryPayload['see_all_doctors_url'] ?? route('doctors.index'),
                    'see_all_hospitals_url' => $directoryPayload['see_all_hospitals_url'] ?? route('hospitals.index'),
                    'see_all_blood_banks_url' => $directoryPayload['see_all_blood_banks_url'] ?? route('blood_banks.index'),
                    'see_all_articles_url' => route('articles.index'),
                    'suggest_details' => false,
                    'history' => $messages,
                ]);
            }
        }

        $apiKey = config('variable.grok_key');
        $grokDepartment = null;

        $lowerMsg = mb_strtolower($userMessage);
        $isDetailRequest = false;
        $detailKeywords = [
            'detail', 'explain', 'more', 'elaborate', 'describe', 'deep dive',
            'विस्तार', 'विवरण', 'अधिक', 'समझाएं', 'और बताएं'
        ];
        foreach ($detailKeywords as $keyword) {
            if (mb_stripos($lowerMsg, $keyword) !== false) {
                $isDetailRequest = true;
                break;
            }
        }

        $originalMessage = $userMessage;
        if ($isDetailRequest) {
            $pastUserMsgs = collect($messages)
                ->filter(fn($msg) => isset($msg['sender']) && $msg['sender'] === 'user' && isset($msg['text']))
                ->filter(function($msg) use ($detailKeywords) {
                    $txt = mb_strtolower($msg['text']);
                    foreach ($detailKeywords as $keyword) {
                        if (mb_stripos($txt, $keyword) !== false) {
                            return false;
                        }
                    }
                    return true;
                });
            if ($pastUserMsgs->isNotEmpty()) {
                $originalMessage = $pastUserMsgs->last()['text'];
            }
        }
        $searchTokens = $this->extractSearchTokens($originalMessage);

        // Cache-first lookup
        $qaAnswer = null;
        if ($originalMessage !== '') {
            $qaAnswer = $this->medicalQaService->findBestAnswer($originalMessage, $locale);
        }

        if ($qaAnswer) {
            $grokDepartment = $qaAnswer['category'] ?? null;
            $grokDepartment = is_string($grokDepartment) ? trim($grokDepartment) : $grokDepartment;
        } else {
            // Cache miss: query Groq AI
            if ($apiKey) {
                $systemPrompt = "You are Swasthya Saathi AI, a professional medical and healthcare assistant. " .
                    "You must return a JSON object containing exactly these 7 keys:\n" .
                    "1. 'question_en': A concise English translation or summary of the user's symptom/query (e.g. 'Acute knee pain when climbing stairs').\n" .
                    "2. 'question_hi': A concise Hindi translation or summary of the user's symptom/query.\n" .
                    "3. 'answer_en': A very short, brief response (strictly 1 to 2 sentences max) in English giving initial medical guidance. Keep it concise so the user is not overwhelmed.\n" .
                    "4. 'answer_hi': A very short, brief response (strictly 1 to 2 sentences max) in Hindi giving initial medical guidance. Keep it concise so the user is not overwhelmed.\n" .
                    "5. 'detailed_answer_en': A comprehensive, detailed, and informative medical explanation in English. Break it down into clear paragraphs or bullet points where helpful.\n" .
                    "6. 'detailed_answer_hi': A comprehensive, detailed, and informative medical explanation in Hindi. Break it down into clear paragraphs or bullet points where helpful.\n" .
                    "7. 'department': The English name of the most appropriate medical department (e.g., 'Cardiology', 'Pediatrics', 'Neurology', 'Dermatology', 'General Medicine', 'Orthopedics', 'Gynecology', 'ENT (Otolaryngology)', 'Ophthalmology', 'Urology', etc.) corresponding to their symptoms, or null/General Medicine if no specific department is relevant.\n\n" .
                    "If the query suggests a life-threatening medical emergency (e.g. severe chest pain, difficulty breathing, sudden weakness/stroke), warn them immediately in both short and detailed answers in the selected city: {$selectedCity}.\n\n" .
                    "Do NOT wrap the JSON response in markdown blocks like ```json. Output ONLY raw valid JSON, starting with { and ending with }.";

                $grokMessages = [
                    ['role' => 'system', 'content' => $systemPrompt]
                ];

                // Include past message history, excluding the final user message which was already pushed to $messages
                $pastMessages = collect($messages)
                    ->slice(0, -1)
                    ->filter(fn($msg) => isset($msg['sender']) && isset($msg['text']) && in_array($msg['sender'], ['user', 'bot'], true))
                    ->take(-10);

                foreach ($pastMessages as $msg) {
                    $grokMessages[] = [
                        'role' => $msg['sender'] === 'user' ? 'user' : 'assistant',
                        'content' => $msg['text'],
                    ];
                }

                $grokMessages[] = [
                    'role' => 'user',
                    'content' => $originalMessage,
                ];

                $models = [
                    'llama-3.1-8b-instant',
                    'meta-llama/llama-4-scout-17b-16e-instruct'
                ];

                foreach ($models as $model) {
                    try {
                        $response = Http::withoutVerifying()
                            ->withToken($apiKey)
                            ->timeout(12)
                            ->post('https://api.groq.com/openai/v1/chat/completions', [
                                'messages' => $grokMessages,
                                'model' => $model,
                                'temperature' => 1,
                                'max_completion_tokens' => 1024,
                                'top_p' => 1,
                                'stream' => false,
                                'response_format' => ['type' => 'json_object'],
                                'stop' => null,
                            ]);

                        if ($response->successful()) {
                            $jsonContent = $response->json('choices.0.message.content');
                            $decoded = json_decode($jsonContent, true);
                            if (is_array($decoded) && isset($decoded['answer_en'], $decoded['answer_hi'])) {
                                $cachedQuestion = CachedMedicalQuestion::updateOrCreate(
                                    ['question_en' => trim($decoded['question_en'] ?? $originalMessage)],
                                    [
                                        'question_hi' => trim($decoded['question_hi'] ?? $originalMessage),
                                        'answer_en' => trim($decoded['answer_en']),
                                        'answer_hi' => trim($decoded['answer_hi']),
                                        'detailed_answer_en' => isset($decoded['detailed_answer_en']) ? trim($decoded['detailed_answer_en']) : null,
                                        'detailed_answer_hi' => isset($decoded['detailed_answer_hi']) ? trim($decoded['detailed_answer_hi']) : null,
                                        'category' => isset($decoded['department']) ? trim($decoded['department']) : null,
                                    ]
                                );

                                $qaAnswer = $this->medicalQaService->findBestAnswer($originalMessage, $locale);
                                if ($qaAnswer) {
                                    $grokDepartment = $qaAnswer['category'] ?? null;
                                    $grokDepartment = is_string($grokDepartment) ? trim($grokDepartment) : $grokDepartment;
                                } else {
                                    $qaAnswer = [
                                        'question' => $locale === 'hi' ? $cachedQuestion->question_hi : $cachedQuestion->question_en,
                                        'answer' => $locale === 'hi' ? $cachedQuestion->answer_hi : $cachedQuestion->answer_en,
                                        'category' => $cachedQuestion->category ?? 'General Medical',
                                        'source' => 'grok_ai_cached',
                                        'source_id' => $cachedQuestion->id,
                                        'source_table' => $cachedQuestion->getTable(),
                                        'confidence' => 100.0,
                                        'detailed_answer_en' => $cachedQuestion->detailed_answer_en,
                                        'detailed_answer_hi' => $cachedQuestion->detailed_answer_hi,
                                        'detailed_answer' => $locale === 'hi'
                                            ? ($cachedQuestion->detailed_answer_hi ?: $cachedQuestion->detailed_answer_en)
                                            : ($cachedQuestion->detailed_answer_en ?: $cachedQuestion->detailed_answer_hi),
                                    ];
                                    $grokDepartment = $qaAnswer['category'] ?? null;
                                    $grokDepartment = is_string($grokDepartment) ? trim($grokDepartment) : $grokDepartment;
                                }
                                break;
                            } else {
                                logger()->warning("Grok response from model {$model} was not in expected JSON format: " . $jsonContent);
                            }
                        } else {
                            logger()->error("Grok API Error with model {$model}: Code " . $response->status() . ' - ' . $response->body());
                        }
                    } catch (Exception $e) {
                        logger()->error("Grok API Exception with model {$model}: " . $e->getMessage());
                    }
                }
            }

            if (!$qaAnswer) {
                $qaAnswer = $this->medicalQaService->generateFallbackAnswer($originalMessage, $locale);
                if (!$qaAnswer) {
                    $this->storeFailedQuery(
                        sessionToken: $sessionToken,
                        city: $selectedCity,
                        locale: $locale,
                        failureType: 'no_match_and_no_ai_answer',
                        userMessage: $originalMessage,
                        errorMessage: 'No DB match and no AI/fallback answer generated.',
                        meta: [
                            'load_type' => $loadType,
                            'search_tokens' => $searchTokens,
                        ]
                    );
                }
            }
        }

        $matchedDeptId = null;
        $matchedDiseaseNameEn = null;
        $matchedDiseaseNameHi = null;
        $departmentInfo = null;

        $doctors = collect();

        // Match department using classification from Grok AI first (or cache hit)
        if (is_string($grokDepartment) && $grokDepartment !== '') {
            $cleanedGrokDept = trim($grokDepartment);
            $matchedDept = Department::where('is_active', true)
                ->where(function ($query) use ($cleanedGrokDept) {
                    $query->where('name_en', 'LIKE', $cleanedGrokDept)
                        ->orWhere('name_hi', 'LIKE', $cleanedGrokDept)
                        ->orWhereRaw('LOWER(name_en) = ?', [strtolower($cleanedGrokDept)])
                        ->orWhereRaw('LOWER(name_hi) = ?', [strtolower($cleanedGrokDept)])
                        ->orWhere('name_en', 'LIKE', "%{$cleanedGrokDept}%")
                        ->orWhere('name_hi', 'LIKE', "%{$cleanedGrokDept}%");
                })
                ->first();

            // If not found, try a looser contains match in English/Hindi
            if (! $matchedDept) {
                $lowerGrokDept = strtolower($cleanedGrokDept);
                $allDepts = Department::where('is_active', true)->get();
                foreach ($allDepts as $dept) {
                    if (
                        stripos($lowerGrokDept, strtolower($dept->name_en)) !== false ||
                        ($dept->name_hi && stripos($lowerGrokDept, strtolower($dept->name_hi)) !== false)
                    ) {
                        $matchedDept = $dept;
                        break;
                    }
                }
            }

            if ($matchedDept) {
                $matchedDeptId = $matchedDept->id;
            }
        }

        if (DB::getDriverName() === 'pgsql') {
            try {
                $stringObj = Str::of($originalMessage);
                $userEmbedding = method_exists($stringObj, 'toEmbeddings') ? $stringObj->toEmbeddings() : json_encode(array_fill(0, 1536, 0.01));
                $diseases = Disease::whereVectorSimilarTo('symptoms_embedding', $userEmbedding)->with(['department'])->get();

                if ($diseases->isNotEmpty()) {
                    $firstMatch = $diseases->first();
                    $matchedDeptId = $firstMatch->department_id;
                    $matchedDiseaseNameEn = $firstMatch->name_en;
                    $matchedDiseaseNameHi = $firstMatch->name_hi;
                }
            } catch (Exception $e) {
            }
        }

        if (! $matchedDeptId) {
            $allDiseases = Disease::with('department')->get();
            foreach ($allDiseases as $disease) {
                $diseaseNameEn = trim((string) $disease->name_en);
                $diseaseNameHi = trim((string) ($disease->name_hi ?? ''));

                if (
                    $diseaseNameEn !== '' &&
                    (stripos($originalMessage, $diseaseNameEn) !== false || stripos($diseaseNameEn, $originalMessage) !== false)
                ) {
                    $matchedDeptId = $disease->department_id;
                    $matchedDiseaseNameEn = $disease->name_en;
                    $matchedDiseaseNameHi = $disease->name_hi;
                    break;
                }
                if (
                    $diseaseNameHi !== '' &&
                    (mb_stripos($originalMessage, $diseaseNameHi) !== false || mb_stripos($diseaseNameHi, $originalMessage) !== false)
                ) {
                    $matchedDeptId = $disease->department_id;
                    $matchedDiseaseNameEn = $disease->name_en;
                    $matchedDiseaseNameHi = $disease->name_hi;
                    break;
                }
            }
        }

        if (! $matchedDeptId) {
            $allDepartments = Department::where('is_active', true)->get();
            foreach ($allDepartments as $dept) {
                $deptNameEn = trim((string) $dept->name_en);
                $deptNameHi = trim((string) ($dept->name_hi ?? ''));

                if (
                    $deptNameEn !== '' &&
                    (stripos($originalMessage, $deptNameEn) !== false || stripos($deptNameEn, $originalMessage) !== false)
                ) {
                    $matchedDeptId = $dept->id;
                    break;
                }
                if (
                    $deptNameHi !== '' &&
                    (mb_stripos($originalMessage, $deptNameHi) !== false || mb_stripos($deptNameHi, $originalMessage) !== false)
                ) {
                    $matchedDeptId = $dept->id;
                    break;
                }
            }
        }

        if ($matchedDeptId) {
            $dept = Department::find($matchedDeptId);
            $deptNameEn = $dept?->name_en ?? '';
            $deptNameHi = $dept?->name_hi ?? '';
            $deptName = $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn;

            if ($matchedDiseaseNameEn) {
                $disName = $locale === 'hi' ? ($matchedDiseaseNameHi ?: $matchedDiseaseNameEn) : $matchedDiseaseNameEn;
                $departmentInfo = $locale === 'hi'
                    ? "For '{$disName}', '{$deptName}' department is recommended."
                    : "For '{$disName}', '{$deptName}' department is recommended.";
            } else {
                $departmentInfo = $locale === 'hi'
                    ? "Based on your query, '{$deptName}' department is recommended."
                    : "Based on your query, '{$deptName}' department is recommended.";
            }
        }

        // Empty collections to load lazily on request
        $doctors = collect();
        $hospitals = collect();
        $articles = collect();

        $deptForFilter = $matchedDeptId ?: 'All';
        $seeAllDoctorsUrl = route('doctors.index');
        $seeAllHospitalsUrl = route('hospitals.index');
        $seeAllArticlesUrl = route('articles.index', ['category' => $grokDepartment ?: 'All']);
        $qaSource = (string) ($qaAnswer['source'] ?? '');
        $symptomMatch = (bool) $qaAnswer && (
            in_array($qaSource, ['cached_medical_questions', 'medical_qa_config', 'grok_ai', 'grok_ai_cached', 'general_questions', 'faq'], true)
            || str_ends_with($qaSource, '_like')
        );

        if ($qaAnswer && ($qaAnswer['source'] ?? '') === 'emergency_rule') {
            $botReply = $locale === 'hi'
                ? 'This may be an emergency. If you have chest pain, shortness of breath, fainting, or rapidly worsening symptoms, call emergency services immediately and go to the nearest emergency room.'
                : 'This may be an emergency. If you have chest pain, shortness of breath, fainting, or severe worsening symptoms, call emergency services immediately and go to the nearest emergency room.';
        } elseif ($qaAnswer) {
            if ($isDetailRequest) {
                $detailedAnswer = $this->resolveDetailedAnswerFromSource($qaAnswer, $locale);
                $botReply = (string) ($detailedAnswer ?: $qaAnswer['answer']);
            } else {
                $botReply = (string) $qaAnswer['answer'];
            }
        } elseif ($departmentInfo) {
            $botReply = $locale === 'hi'
                ? 'I analyzed your query. Here are relevant doctors and hospitals in your city:'
                : 'I analyzed your query. Here are relevant doctors and hospitals in your city:';
        } else {
            $botReply = $locale === 'hi'
                ? 'I could not find an exact match, but here are useful options in your city.'
                : 'I could not find an exact match, but here are useful options in your city.';
        }
        $suggestDetails = false;
        if ($qaAnswer && !$isDetailRequest && ($qaAnswer['source'] ?? '') !== 'emergency_rule') {
            $detailedAnswer = $this->resolveDetailedAnswerFromSource($qaAnswer, $locale);
            if (!empty($detailedAnswer)) {
                $suggestDetails = true;
            }
        }

        $messages[] = [
            'sender' => 'bot',
            'text' => $botReply,
            'city' => $selectedCity,
            'locale' => $locale,
            'qa_answer' => $qaAnswer,
            'symptom_match' => $symptomMatch,
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'articles' => $articles,
            'see_all_doctors_url' => $seeAllDoctorsUrl,
            'see_all_hospitals_url' => $seeAllHospitalsUrl,
            'see_all_articles_url' => $seeAllArticlesUrl,
            'suggest_details' => $suggestDetails,
            'show_options' => true,
            'timestamp' => now()->toIso8601String(),
        ];

        $chatSession->update(['messages' => $messages]);

        return response()->json([
            'session_token' => $sessionToken,
            'reply' => $botReply,
            'city' => $selectedCity,
            'city_options' => $cityOptions,
            'locale' => $locale,
            'qa_answer' => $qaAnswer,
            'symptom_match' => $symptomMatch,
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'articles' => $articles,
            'see_all_doctors_url' => $seeAllDoctorsUrl,
            'see_all_hospitals_url' => $seeAllHospitalsUrl,
            'see_all_articles_url' => $seeAllArticlesUrl,
            'suggest_details' => $suggestDetails,
            'history' => $messages,
        ]);
        } catch (Throwable $e) {
            report($e);
            $this->storeFailedQuery(
                sessionToken: (string) ($request->input('session_token') ?: ''),
                city: (string) ($request->input('city') ?: ''),
                locale: (string) ($request->input('locale') ?: app()->getLocale()),
                failureType: 'server_exception',
                userMessage: (string) ($request->input('message') ?: ''),
                errorMessage: $e->getMessage(),
                meta: [
                    'exception' => get_class($e),
                ]
            );

            $locale = (string) ($request->input('locale') ?: app()->getLocale());
            $fallbackReply = $locale === 'hi'
                ? 'कुछ तकनीकी समस्या आई, लेकिन मैं आपकी मदद के लिए तैयार हूँ। कृपया फिर से संदेश भेजें या "Find Doctors" विकल्प चुनें।'
                : 'A technical issue occurred, but I am ready to help. Please resend your message or choose "Find Doctors".';

            return response()->json([
                'session_token' => (string) ($request->input('session_token') ?: Str::random(32)),
                'reply' => $fallbackReply,
                'city' => (string) ($request->input('city') ?: ''),
                'locale' => $locale === 'hi' ? 'hi' : 'en',
                'show_options' => true,
                'suggest_details' => false,
                'history' => [[
                    'sender' => 'bot',
                    'text' => $fallbackReply,
                    'show_options' => true,
                    'suggest_details' => false,
                    'timestamp' => now()->toIso8601String(),
                ]],
            ], 200);
        }
    }

    public function reportClientFailure(Request $request)
    {
        $data = $request->validate([
            'session_token' => 'nullable|string|max:64',
            'city' => 'nullable|string|max:120',
            'locale' => 'nullable|in:en,hi',
            'message' => 'nullable|string',
            'failure_type' => 'nullable|string|max:64',
            'error_message' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        $this->storeFailedQuery(
            sessionToken: (string) ($data['session_token'] ?? ''),
            city: (string) ($data['city'] ?? ''),
            locale: (string) ($data['locale'] ?? app()->getLocale()),
            failureType: (string) ($data['failure_type'] ?? 'client_fetch_failure'),
            userMessage: (string) ($data['message'] ?? ''),
            errorMessage: (string) ($data['error_message'] ?? ''),
            meta: (array) ($data['meta'] ?? [])
        );

        return response()->json(['ok' => true]);
    }

    private function storeFailedQuery(
        string $sessionToken,
        string $city,
        string $locale,
        string $failureType,
        string $userMessage,
        string $errorMessage = '',
        array $meta = []
    ): void {
        try {
            ChatbotFailedQuery::create([
                'session_token' => $sessionToken !== '' ? $sessionToken : null,
                'city' => $city !== '' ? $city : null,
                'locale' => $locale !== '' ? $locale : null,
                'failure_type' => $failureType !== '' ? $failureType : 'unknown',
                'user_message' => $userMessage !== '' ? $userMessage : null,
                'error_message' => $errorMessage !== '' ? $errorMessage : null,
                'meta' => !empty($meta) ? $meta : null,
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function extractSearchTokens(string $message): array
    {
        $normalized = mb_strtolower(trim($message));
        if ($normalized === '') {
            return [];
        }

        $normalized = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $normalized) ?? $normalized;
        $parts = preg_split('/\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $stopwords = [
            'dr',
            'doctor',
            'doctors',
            'hospital',
            'hospitals',
            'clinic',
            'clinics',
            'find',
            'near',
            'nearby',
            'in',
            'at',
            'for',
            'the',
            'a',
            'an',
            'show',
            'me',
            'need',
            'please',
            'search',
            'डॉक्टर',
            'डॉ',
            'अस्पताल',
            'क्लिनिक',
            'खोजें',
            'में',
            'पास',
            'मुझे',
            'चाहिए',
            'कृपया',
        ];

        $tokens = array_values(array_unique(array_filter($parts, function ($part) use ($stopwords) {
            return mb_strlen($part) >= 2 && ! in_array($part, $stopwords, true);
        })));

        return array_slice($tokens, 0, 8);
    }

    private function shouldRouteToDirectory(string $message, array $tokens, string $city): bool
    {
        $msg = mb_strtolower(trim($message));
        if ($msg === '') {
            return false;
        }

        $directoryKeywords = [
            'find', 'search', 'near', 'nearby', 'doctor', 'hospital', 'clinic', 'blood bank', 'specialist', 'department',
            'show doctors', 'show hospitals', 'cardiologist', 'orthopedic', 'dermatologist',
            'खोज', 'डॉक्टर', 'अस्पताल', 'क्लिनिक', 'ब्लड बैंक', 'विशेषज्ञ', 'विभाग', 'पास', 'नजदीक',
        ];

        $aiMedicalIntentKeywords = [
            'why', 'cause', 'treatment', 'medicine', 'dosage', 'dose', 'diet', 'prevention', 'symptom meaning',
            'explain', 'detail', 'detailed', 'serious', 'is this dangerous',
            'क्यों', 'कारण', 'इलाज', 'दवा', 'खुराक', 'उपचार', 'समझाएं', 'विस्तार', 'गंभीर',
        ];

        foreach ($directoryKeywords as $keyword) {
            if (mb_stripos($msg, $keyword) !== false) {
                return true;
            }
        }

        foreach ($aiMedicalIntentKeywords as $keyword) {
            if (mb_stripos($msg, $keyword) !== false) {
                return false;
            }
        }

        // Natural "Dr X" style lookup.
        if (preg_match('/\bdr\.?\s+/iu', $message) === 1) {
            return true;
        }

        // Try quick entity existence checks as fallback.
        $needle = implode(' ', array_slice($tokens, 0, 3));
        if ($needle === '') {
            $needle = $message;
        }
        $needle = trim($needle);
        if ($needle === '') {
            return false;
        }

        $doctorExists = Doctor::where('is_verified', true)
            ->where(function ($q) use ($needle) {
                $q->where('first_name', 'LIKE', "%{$needle}%")
                    ->orWhere('last_name', 'LIKE', "%{$needle}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$needle}%"])
                    ->orWhere('specialization_summary', 'LIKE', "%{$needle}%");
            })
            ->whereHas('hospitals', fn($q) => $q->where('city', $city))
            ->exists();

        if ($doctorExists) {
            return true;
        }

        $hospitalOrBloodBankExists = Hospital::where('is_verified', true)
            ->where('city', $city)
            ->where(function ($q) use ($needle) {
                $q->where('name_en', 'LIKE', "%{$needle}%")
                    ->orWhere('name_hi', 'LIKE', "%{$needle}%");
            })
            ->exists()
            || BloodBank::where('is_verified', true)
            ->where('city', $city)
            ->where(function ($q) use ($needle) {
                $q->where('name_en', 'LIKE', "%{$needle}%")
                    ->orWhere('name_hi', 'LIKE', "%{$needle}%");
            })
            ->exists();

        if ($hospitalOrBloodBankExists) {
            return true;
        }

        return Department::where('is_active', true)
            ->where(function ($q) use ($needle) {
                $q->where('name_en', 'LIKE', "%{$needle}%")
                    ->orWhere('name_hi', 'LIKE', "%{$needle}%");
            })
            ->exists();
    }

    private function buildDirectoryFirstResponse(string $message, array $searchTokens, string $city, string $locale): ?array
    {
        $department = null;
        $departmentToken = trim($message);
        if (!empty($searchTokens)) {
            $departmentToken = implode(' ', array_slice($searchTokens, 0, 4));
        }

        if ($departmentToken !== '') {
            $department = Department::where('is_active', true)
                ->where(function ($q) use ($departmentToken) {
                    $q->where('name_en', 'LIKE', "%{$departmentToken}%")
                        ->orWhere('name_hi', 'LIKE', "%{$departmentToken}%");
                })
                ->first();
        }

        $doctorsQuery = Doctor::with(['department', 'hospitals'])
            ->where('is_verified', true)
            ->whereHas('hospitals', fn($q) => $q->where('city', $city));

        if ($department) {
            $doctorsQuery->where('department_id', $department->id);
        }

        $keyword = trim($message);
        if ($keyword !== '') {
            $doctorsQuery->where(function ($q) use ($keyword, $searchTokens) {
                $q->where('first_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"])
                    ->orWhere('specialization_summary', 'LIKE', "%{$keyword}%")
                    ->orWhere('about_en', 'LIKE', "%{$keyword}%")
                    ->orWhere('about_hi', 'LIKE', "%{$keyword}%");
                foreach ($searchTokens as $token) {
                    $q->orWhere('first_name', 'LIKE', "%{$token}%")
                        ->orWhere('last_name', 'LIKE', "%{$token}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$token}%"]);
                }
            });
        }
        $doctors = $doctorsQuery->take(3)->get();

        $hospitalsQuery = Hospital::where('is_verified', true)->where('city', $city);
        if ($keyword !== '') {
            $hospitalsQuery->where(function ($q) use ($keyword, $searchTokens) {
                $q->where('name_en', 'LIKE', "%{$keyword}%")
                    ->orWhere('name_hi', 'LIKE', "%{$keyword}%")
                    ->orWhere('type', 'LIKE', "%{$keyword}%")
                    ->orWhere('address', 'LIKE', "%{$keyword}%");
                foreach ($searchTokens as $token) {
                    $q->orWhere('name_en', 'LIKE', "%{$token}%")
                        ->orWhere('name_hi', 'LIKE', "%{$token}%")
                        ->orWhere('type', 'LIKE', "%{$token}%");
                }
            });
        }
        if ($department) {
            $hospitalsQuery->whereHas('doctors', fn($q) => $q->where('department_id', $department->id));
        }
        $hospitals = $hospitalsQuery->take(3)->get();

        $bloodBanksQuery = BloodBank::where('is_verified', true)->where('city', $city);
        if ($keyword !== '') {
            $bloodBanksQuery->where(function ($q) use ($keyword, $searchTokens) {
                $q->where('name_en', 'LIKE', "%{$keyword}%")
                    ->orWhere('name_hi', 'LIKE', "%{$keyword}%")
                    ->orWhere('address', 'LIKE', "%{$keyword}%");
                foreach ($searchTokens as $token) {
                    $q->orWhere('name_en', 'LIKE', "%{$token}%")
                        ->orWhere('name_hi', 'LIKE', "%{$token}%");
                }
            });
        }
        $bloodBanks = $bloodBanksQuery->take(3)->get();

        if ($doctors->isEmpty() && $hospitals->isEmpty() && $bloodBanks->isEmpty()) {
            return null;
        }

        $deptName = $department
            ? ($locale === 'hi' ? ($department->name_hi ?: $department->name_en) : $department->name_en)
            : null;

        $reply = $locale === 'hi'
            ? "आपके शहर {$city} में मैंने संबंधित परिणाम ढूंढे हैं। नीचे डॉक्टर, अस्पताल और ब्लड बैंक विकल्प देखें।"
            : "I found relevant results in {$city}. Please check the doctors, hospitals, and blood bank options below.";

        $departmentInfo = $deptName
            ? ($locale === 'hi'
                ? "आपकी खोज के लिए '{$deptName}' विभाग सबसे उपयुक्त दिख रहा है।"
                : "For your query, '{$deptName}' seems to be the most relevant department.")
            : null;

        return [
            'text' => $reply,
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'blood_banks' => $bloodBanks,
            'articles' => [],
            'symptom_match' => false,
            'qa_answer' => null,
            'show_options' => true,
            'see_all_doctors_url' => route('doctors.index'),
            'see_all_hospitals_url' => route('hospitals.index'),
            'see_all_blood_banks_url' => route('blood_banks.index'),
            'see_all_articles_url' => route('articles.index'),
            'suggest_details' => false,
        ];
    }

    private function resolveDetailedAnswerFromSource(array $qa, string $locale): ?string
    {
        $sourceTable = (string) ($qa['source_table'] ?? '');
        $sourceId = isset($qa['source_id']) ? (int) $qa['source_id'] : 0;
        $source = (string) ($qa['source'] ?? '');

        if ($sourceTable === '' || $sourceId <= 0) {
            if ($sourceTable === '' && str_contains($source, 'general_questions')) {
                $sourceTable = 'general_questions';
            } elseif ($sourceTable === '' && str_contains($source, 'cached_medical_questions')) {
                $sourceTable = 'cached_medical_questions';
            } elseif ($sourceTable === '' && str_contains($source, 'faq')) {
                $sourceTable = 'faqs';
            }
        }

        if ($sourceId > 0 && $sourceTable !== '') {
            $record = match ($sourceTable) {
                'general_questions' => GeneralQuestion::query()->find($sourceId),
                'cached_medical_questions' => CachedMedicalQuestion::query()->find($sourceId),
                'faqs' => Faq::query()->find($sourceId),
                default => null,
            };

            if ($record) {
                $detailed = $locale === 'hi'
                    ? ((string) ($record->detailed_answer_hi ?? '') ?: (string) ($record->detailed_answer_en ?? ''))
                    : ((string) ($record->detailed_answer_en ?? '') ?: (string) ($record->detailed_answer_hi ?? ''));

                if (trim($detailed) !== '') {
                    return trim($detailed);
                }

                $fallback = $locale === 'hi'
                    ? ((string) ($record->answer_hi ?? '') ?: (string) ($record->answer_en ?? ''))
                    : ((string) ($record->answer_en ?? '') ?: (string) ($record->answer_hi ?? ''));

                return trim($fallback) !== '' ? trim($fallback) : null;
            }
        }

        $inlineDetailed = $locale === 'hi'
            ? ($qa['detailed_answer_hi'] ?? $qa['detailed_answer_en'] ?? $qa['detailed_answer'] ?? null)
            : ($qa['detailed_answer_en'] ?? $qa['detailed_answer_hi'] ?? $qa['detailed_answer'] ?? null);

        if (is_string($inlineDetailed) && trim($inlineDetailed) !== '') {
            return trim($inlineDetailed);
        }

        $inlineAnswer = (string) ($qa['answer'] ?? '');
        return trim($inlineAnswer) !== '' ? trim($inlineAnswer) : null;
    }

    private function findGeneralHelpResponse(string $message, string $locale, string $selectedCity = '', array $messages = []): ?array
    {
        $normalized = $this->normalizeForQaMatch($message);
        if ($normalized === '') {
            return null;
        }

        $strictDbMatch = $this->findStrictConversationMatch($normalized, $locale);
        if ($strictDbMatch !== null) {
            return $strictDbMatch;
        }

        $isDetailRequest = collect(['detail', 'detailed', 'explain', 'more', 'deep dive', '???????', '?????'])
            ->contains(fn($k) => mb_stripos($normalized, $k) !== false);

        if ($isDetailRequest) {
            $lastGeneralHelp = collect($messages)->reverse()->first(function ($msg) {
                return ($msg['sender'] ?? null) === 'bot'
                    && ($msg['response_mode'] ?? null) === 'general_help'
                    && !empty($msg['qa_answer']);
            });

            if ($lastGeneralHelp) {
                $qa = $lastGeneralHelp['qa_answer'];
                $detailed = $this->resolveDetailedAnswerFromSource((array) $qa, $locale);

                if (!empty($detailed)) {
                    return [
                        'text' => (string) $detailed,
                        'qa_answer' => $qa,
                        'suggest_details' => false,
                    ];
                }
            }
        }

        $records = GeneralQuestion::query()->get();
        $best = null;
        $bestScore = 0;
        $tokens = $this->extractSearchTokens($normalized);

        foreach ($records as $q) {
            $qEn = $this->normalizeForQaMatch((string) $q->question_en);
            $qHi = $this->normalizeForQaMatch((string) $q->question_hi);
            $score = 0;

            if ($qEn !== '' && (str_contains($normalized, $qEn) || str_contains($qEn, $normalized))) {
                $score += 5;
            }
            if ($qHi !== '' && (str_contains($normalized, $qHi) || str_contains($qHi, $normalized))) {
                $score += 5;
            }
            foreach ($tokens as $token) {
                if (($qEn !== '' && str_contains($qEn, $token)) || ($qHi !== '' && str_contains($qHi, $token))) {
                    $score += 1;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $q;
            }
        }

        if ($best && $bestScore >= 3) {
            $answer = $locale === 'hi' ? ($best->answer_hi ?: $best->answer_en) : ($best->answer_en ?: $best->answer_hi);
            $detailed = $locale === 'hi'
                ? ($best->detailed_answer_hi ?: $best->detailed_answer_en)
                : ($best->detailed_answer_en ?: $best->detailed_answer_hi);

            return [
                'text' => (string) ($isDetailRequest && !empty($detailed) ? $detailed : $answer),
                'qa_answer' => [
                    'source_id' => $best->id,
                    'source_table' => $best->getTable(),
                    'question' => $locale === 'hi' ? ($best->question_hi ?: $best->question_en) : ($best->question_en ?: $best->question_hi),
                    'answer' => $answer,
                    'detailed_answer_en' => $best->detailed_answer_en,
                    'detailed_answer_hi' => $best->detailed_answer_hi,
                    'source' => 'general_questions',
                ],
                'suggest_details' => !empty($detailed) && !$isDetailRequest,
            ];
        }

        return null;
    }

    private function findStrictConversationMatch(string $normalizedMessage, string $locale): ?array
    {
        foreach ([
            ['records' => CachedMedicalQuestion::query()->get(), 'source' => 'cached_medical_questions'],
            ['records' => GeneralQuestion::query()->get(), 'source' => 'general_questions'],
        ] as $dataset) {
            $best = null;
            $bestScore = 0;
            $tokens = $this->extractSearchTokens($normalizedMessage);

            foreach ($dataset['records'] as $q) {
                $qEn = $this->normalizeForQaMatch((string) $q->question_en);
                $qHi = $this->normalizeForQaMatch((string) $q->question_hi);
                if ($qEn === '' && $qHi === '') {
                    continue;
                }

                $score = 0;
                if ($qEn !== '' && $normalizedMessage === $qEn) {
                    $score += 10;
                }
                if ($qHi !== '' && $normalizedMessage === $qHi) {
                    $score += 10;
                }
                if ($qEn !== '' && str_contains($qEn, $normalizedMessage) && mb_strlen($normalizedMessage) >= 3) {
                    $score += 4;
                }
                if ($qHi !== '' && str_contains($qHi, $normalizedMessage) && mb_strlen($normalizedMessage) >= 3) {
                    $score += 4;
                }

                foreach ($tokens as $token) {
                    if (mb_strlen($token) < 3) {
                        continue;
                    }
                    if (($qEn !== '' && str_contains($qEn, $token)) || ($qHi !== '' && str_contains($qHi, $token))) {
                        $score += 1;
                    }
                }

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = $q;
                }
            }

            if (! $best || $bestScore < 5) {
                continue;
            }

            $answer = $locale === 'hi' ? ($best->answer_hi ?: $best->answer_en) : ($best->answer_en ?: $best->answer_hi);
            $detailed = $locale === 'hi'
                ? ($best->detailed_answer_hi ?: $best->detailed_answer_en)
                : ($best->detailed_answer_en ?: $best->detailed_answer_hi);

            return [
                'text' => (string) $answer,
                'qa_answer' => [
                    'source_id' => $best->id,
                    'source_table' => $best->getTable(),
                    'question' => $locale === 'hi' ? ($best->question_hi ?: $best->question_en) : ($best->question_en ?: $best->question_hi),
                    'answer' => $answer,
                    'detailed_answer_en' => $best->detailed_answer_en,
                    'detailed_answer_hi' => $best->detailed_answer_hi,
                    'source' => $dataset['source'],
                ],
                'suggest_details' => !empty($detailed),
            ];
        }

        return null;
    }

    private function normalizeForQaMatch(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        return trim($text);
    }

    private function findMedicineResponse(string $message, string $locale): ?array
    {
        $normalized = trim($message);
        if ($normalized === '') {
            return null;
        }

        $explicitKeywords = ['medicine', 'tablet', 'capsule', 'dose', 'dosage', 'dolo', 'paracetamol', 'cetirizine', 'azithromycin', 'metformin', 'pantoprazole', 'दवा', 'टैबलेट'];
        $hasMedicineIntent = collect($explicitKeywords)->contains(fn ($keyword) => mb_stripos($normalized, $keyword) !== false);

        $medicine = Medicine::query()
            ->published()
            ->search($normalized)
            ->first();

        if (! $medicine || ! $hasMedicineIntent) {
            return null;
        }

        $url = route('medicines.show', $medicine->slug);
        $purpose = $medicine->getTranslation('purpose', $locale) ?: ($medicine->category ?: $medicine->generic_name ?: $medicine->name);

        return [
            'text' => $locale === 'hi'
                ? "मुझे {$medicine->name} के लिए सामान्य जानकारी मिली है। {$purpose}। मैं यह पुष्टि नहीं कर सकता कि यह आपके लिए व्यक्तिगत रूप से सुरक्षित है। कृपया डॉक्टर या फार्मासिस्ट से सलाह लें। विवरण देखें: {$url}"
                : "I found general information for {$medicine->name}. {$purpose}. I cannot confirm whether it is personally safe for you, so please consult a doctor or pharmacist. View details: {$url}",
        ];
    }
}

