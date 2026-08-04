<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\Client\PendingRequest;
use Throwable;

class ChatbotController extends Controller
{
    public function __construct(private readonly MedicalQaService $medicalQaService) {}

    public function handleMessage(Request $request)
    {
        try {
            logger()->info('Chatbot query received', [
                'message' => $request->input('message'),
                'city' => $request->input('city'),
                'locale' => $request->input('locale'),
                'session_token' => $request->input('session_token'),
            ]);
            $activeCity = config('healthcare.active_city', 'Jaipur');
            $sessionToken = trim((string) $request->input('session_token', ''));
            if ($sessionToken === '') {
                $sessionToken = Str::random(32);
            }

            $userMessage = trim((string) $request->input('message', ''));
            $inputCity = trim((string) $request->input('city', ''));
            $locale = strtolower(trim((string) $request->input('locale', app()->getLocale())));
            $locale = in_array($locale, ['en', 'hi'], true) ? $locale : 'en';
            $loadType = strtolower(trim((string) $request->input('load_type', '')));
            $loadType = in_array($loadType, ['doctors', 'hospitals', 'articles'], true) ? $loadType : null;

            if (mb_strlen($sessionToken) > 64) {
                $sessionToken = Str::limit($sessionToken, 64, '');
            }
            if (mb_strlen($inputCity) > 120) {
                $inputCity = Str::limit($inputCity, 120, '');
            }

            logger()->info('1. Inputs parsed', ['session_token' => $sessionToken]);

            $searchTokens = $this->extractSearchTokens($userMessage);

            logger()->info('2. Querying/Creating ChatSession in DB', ['session_token' => $sessionToken]);

            $chatSession = $this->findOrCreateChatSessionSafely($sessionToken);

            logger()->info('3. ChatSession retrieved/created successfully');

            $messages = $this->normalizeChatHistory($chatSession?->messages);

            logger()->info('4. Chat history normalized');

            $cityOptions = [$activeCity];
            $selectedCity = $activeCity;

            // Always try GeneralQuestion module first for conversational/help queries
            // before any directory-loading or AI-powered medical flow.
            if ($userMessage !== '') {
                $generalHelpPayload = $this->safeFindGeneralHelpResponse($userMessage, $locale, $selectedCity, $messages);
                if ($generalHelpPayload !== null) {
                    logger()->info('Chatbot matched general help query.');
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
                        'show_options' => false,
                        'response_mode' => 'general_help',
                        'suggest_details' => (bool) $generalHelpPayload['suggest_details'],
                        'timestamp' => now()->toIso8601String(),
                    ];

                    $this->persistChatSessionSafely($chatSession, $messages);

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

                $medicinePayload = $this->safeFindMedicineResponse($userMessage, $locale);
                if ($medicinePayload !== null) {
                    logger()->info('Chatbot matched medicine query.');
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
                        'show_options' => false,
                        'response_mode' => 'medicine_help',
                        'medicine_info' => $medicinePayload['medicine_info'],
                        'timestamp' => now()->toIso8601String(),
                    ];

                    $this->persistChatSessionSafely($chatSession, $messages);

                    return response()->json([
                        'session_token' => $sessionToken,
                        'reply' => $medicinePayload['text'],
                        'medicine_info' => $medicinePayload['medicine_info'],
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
                $this->persistChatSessionSafely($chatSession, $messages);

                return response()->json([
                    'session_token' => $sessionToken,
                    'reply' => $cityPrompt,
                    'needs_city' => true,
                    'city_options' => $cityOptions,
                    'history' => $messages,
                ]);
            }
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
                    $qaAnswer = $this->safeFindBestAnswer($originalMessage, $locale);
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
                    'show_options' => false,
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
                    $this->persistChatSessionSafely($chatSession, $messages);

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

            logger()->info('5. User message appended to history array');

            // Directory-first routing layer to reduce AI usage and return exact entity matches quickly.
            // If user is searching names/departments/providers, we should avoid AI calls.
            logger()->info('6. Checking if query should route to directory');
            if ($this->shouldRouteToDirectory($userMessage, $searchTokens, $selectedCity)) {
                logger()->info('Chatbot routing query directly to directory.');
                $directoryPayload = $this->buildDirectoryFirstResponse($originalMessage = $userMessage, $searchTokens, $selectedCity, $locale);
                if ($directoryPayload !== null) {
                    $messages[] = array_merge([
                        'sender' => 'bot',
                        'city' => $selectedCity,
                        'locale' => $locale,
                        'timestamp' => now()->toIso8601String(),
                        'response_mode' => 'directory_first',
                    ], $directoryPayload);

                    $this->persistChatSessionSafely($chatSession, $messages);

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

            logger()->info('7. Directory routing check completed (did not route)');

            $geminiApiKey = (string) config('variable.gemini_key', '');
            $geminiModel = $this->normalizeGeminiModel((string) config('variable.gemini_model', 'gemini-2.5-flash'));
            $apiKey = (string) config('variable.groq_key', config('variable.grok_key', ''));
            $grokDepartment = null;

            logger()->info('8. Configuration keys loaded', [
                'has_gemini_key' => $geminiApiKey !== '',
                'gemini_model' => $geminiModel,
                'has_groq_key' => $apiKey !== '',
            ]);

            $lowerMsg = mb_strtolower($userMessage);
            $isDetailRequest = false;
            $detailKeywords = [
                'detail',
                'explain',
                'more',
                'elaborate',
                'describe',
                'deep dive',
                'विस्तार',
                'विवरण',
                'अधिक',
                'समझाएं',
                'और बताएं'
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
                    ->filter(function ($msg) use ($detailKeywords) {
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

            $qaAnswer = null;
            $symptomMatch = false;
            $botReply = '';

            // Try Gemini first for the primary conversational response.
            logger()->info('Attempting Gemini query...', [
                'model' => $geminiModel,
                'has_api_key' => $geminiApiKey !== '',
            ]);
            if ($geminiApiKey !== '' && $originalMessage !== '') {
                $geminiPayload = $this->queryGemini(
                    apiKey: $geminiApiKey,
                    model: $geminiModel,
                    originalMessage: $originalMessage,
                    locale: $locale,
                    messages: $messages,
                );

                if ($geminiPayload !== null) {
                    logger()->info('Gemini query succeeded.');
                    $replyText = $this->normalizeAiText($geminiPayload['reply'] ?? null);
                    $detailedReply = $this->normalizeAiText($geminiPayload['detailed_reply'] ?? null);
                    $department = $this->normalizeAiText($geminiPayload['department'] ?? null, 'General Medical');

                    if ($replyText !== '') {
                        try {
                            CachedMedicalQuestion::updateOrCreate(
                                ['question_en' => $locale === 'en' ? $originalMessage : $originalMessage],
                                [
                                    'question_hi' => $originalMessage,
                                    'answer_en' => $locale === 'en' ? $replyText : '',
                                    'answer_hi' => $locale === 'hi' ? $replyText : '',
                                    'detailed_answer_en' => $locale === 'en' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                                    'detailed_answer_hi' => $locale === 'hi' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                                    'category' => $department !== '' ? $department : 'General Medical',
                                ]
                            );
                        } catch (Throwable $cacheException) {
                            logger()->warning('Unable to cache chatbot Gemini answer: ' . $cacheException->getMessage());
                        }

                        $qaAnswer = [
                            'question' => $originalMessage,
                            'answer' => $replyText,
                            'category' => $department !== '' ? $department : 'General Medical',
                            'source' => 'gemini_ai',
                            'source_id' => null,
                            'source_table' => null,
                            'confidence' => 100.0,
                            'detailed_answer_en' => $locale === 'en' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                            'detailed_answer_hi' => $locale === 'hi' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                            'detailed_answer' => $detailedReply !== '' ? $detailedReply : null,
                        ];

                        $botReply = $replyText;
                        $symptomMatch = (bool) ($geminiPayload['symptom_match'] ?? false);
                        $grokDepartment = $department !== '' ? $department : null;
                        $isEmergency = (bool) ($geminiPayload['emergency'] ?? false);

                        if ($isEmergency) {
                            $qaAnswer['source'] = 'emergency_rule';
                            $botReply = $locale === 'hi'
                                ? 'यह एक आपातकालीन स्थिति हो सकती है। यदि आपको छाती में दर्द, सांस लेने में तकलीफ, या बेहोशी महसूस हो रही है, तो तुरंत आपातकालीन सेवाओं को कॉल करें और नजदीकी आपातकालीन कक्ष में जाएं।'
                                : 'This may be an emergency. If you have chest pain, shortness of breath, or fainting, call emergency services immediately and go to the nearest emergency room.';
                            $qaAnswer['answer'] = $botReply;
                        }
                    }
                } else {
                    logger()->warning('Gemini query returned null payload.');
                }
            }

            // Fallback to Groq if Gemini is unavailable or did not return a usable answer.
            if (!$qaAnswer && $apiKey !== '' && $originalMessage !== '') {
                logger()->info('Gemini failed or skipped. Falling back to Groq query...', [
                    'has_groq_key' => $apiKey !== '',
                ]);
                $systemPrompt = "You are Jeeva, a professional, warm, empathetic, and knowledgeable medical and healthcare assistant. " .
                    "Your goal is to have a natural, helpful conversation with the user and provide initial medical guidance based on their questions, symptoms, or concerns.\n\n" .
                    "You MUST return a JSON object containing exactly these 5 keys:\n" .
                    "1. 'reply': A warm, caring, and human-like response in the language of the user's query (" . ($locale === 'hi' ? 'Hindi' : 'English') . "). Answer their query directly. Avoid sounding like a machine; use friendly, compassionate phrasing. Limit the reply to 2-3 sentences. You can ask a natural follow-up question if appropriate to keep the conversation going.\n" .
                    "2. 'detailed_reply': A comprehensive, detailed, and structured explanation in " . ($locale === 'hi' ? 'Hindi' : 'English') . " (using markdown formatting like bullet points and bold text) that explains symptoms, home care, precautions, or medical descriptions. Set to null if the query is a simple greeting or general help request (not a medical topic).\n" .
                    "3. 'department': The English name of the most appropriate medical department from this list: ['Cardiology', 'Pediatrics', 'Neurology', 'Dermatology', 'General Medicine', 'Orthopedics', 'Gynecology', 'ENT (Otolaryngology)', 'Ophthalmology', 'Urology', 'Dentistry', 'Psychiatry'] that matches the symptoms described, or null if no medical department is relevant.\n" .
                    "4. 'symptom_match': A boolean (true if the user is describing medical symptoms or asking health/medical questions, false otherwise).\n" .
                    "5. 'emergency': A boolean (true if the symptoms suggest a life-threatening medical emergency like severe chest pain, extreme breathlessness, sudden speech loss, etc.).\n\n" .
                    "Do NOT wrap the JSON response in markdown blocks like ```json. Output ONLY raw valid JSON, starting with { and ending with }.";

                $grokMessages = [
                    ['role' => 'system', 'content' => $systemPrompt]
                ];

                // Retrieve database RAG context
                $context = $this->medicalQaService->retrieveRelevantContext($originalMessage, $locale);
                if ($context !== '') {
                    $grokMessages[0]['content'] .= "\n\n---\nRETRIEVED KNOWLEDGE BASE CONTEXT (Use this as reference if relevant to the query):\n" . $context . "\n---";
                }

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
                    'llama-3.3-70b-versatile',
                    'llama-3.1-8b-instant'
                ];

                foreach ($models as $model) {
                    try {
                        logger()->info("Sending POST request to Groq API with model: {$model}");
                        $response = $this->chatbotHttpClient()->retry(2, 250)
                            ->connectTimeout(8)
                            ->withToken($apiKey)
                            ->timeout(12)
                            ->post('https://api.groq.com/openai/v1/chat/completions', [
                                'messages' => $grokMessages,
                                'model' => $model,
                                'temperature' => 0.7,
                                'max_completion_tokens' => 1024,
                                'top_p' => 1,
                                'stream' => false,
                                'response_format' => ['type' => 'json_object'],
                                'stop' => null,
                            ]);

                        if ($response->successful()) {
                            logger()->info("Groq query succeeded with model: {$model}");
                            $jsonContent = $response->json('choices.0.message.content');
                            $decoded = $this->decodeAiJsonResponse($jsonContent);
                            if (is_array($decoded) && isset($decoded['reply'])) {
                                $replyText = $this->normalizeAiText($decoded['reply'] ?? null);
                                $detailedReply = $this->normalizeAiText($decoded['detailed_reply'] ?? null);
                                $department = $this->normalizeAiText($decoded['department'] ?? null, 'General Medical');
                                $questionEn = trim($locale === 'en'
                                    ? $originalMessage
                                    : $this->normalizeAiText($decoded['question_en'] ?? null, $originalMessage));
                                $questionHi = trim($locale === 'hi'
                                    ? $originalMessage
                                    : $this->normalizeAiText($decoded['question_hi'] ?? null, $originalMessage));

                                if ($replyText === '') {
                                    throw new Exception('Groq response missing usable reply text.');
                                }

                                // Cache failures should not block the live answer.
                                try {
                                    CachedMedicalQuestion::updateOrCreate(
                                        ['question_en' => $questionEn !== '' ? $questionEn : $originalMessage],
                                        [
                                            'question_hi' => $questionHi !== '' ? $questionHi : $originalMessage,
                                            'answer_en' => $locale === 'en' ? $replyText : '',
                                            'answer_hi' => $locale === 'hi' ? $replyText : '',
                                            'detailed_answer_en' => $locale === 'en' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                                            'detailed_answer_hi' => $locale === 'hi' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                                            'category' => $department !== '' ? $department : 'General Medical',
                                        ]
                                    );
                                } catch (Throwable $cacheException) {
                                    logger()->warning('Unable to cache chatbot AI answer: ' . $cacheException->getMessage());
                                }

                                $qaAnswer = [
                                    'question' => $originalMessage,
                                    'answer' => $replyText,
                                    'category' => $department !== '' ? $department : 'General Medical',
                                    'source' => 'jeeva_ai',
                                    'source_id' => null,
                                    'source_table' => null,
                                    'confidence' => 100.0,
                                    'detailed_answer_en' => $locale === 'en' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                                    'detailed_answer_hi' => $locale === 'hi' ? ($detailedReply !== '' ? $detailedReply : null) : null,
                                    'detailed_answer' => $detailedReply !== '' ? $detailedReply : null,
                                ];

                                $botReply = $replyText;
                                $symptomMatch = (bool) ($decoded['symptom_match'] ?? false);
                                $grokDepartment = $department !== '' ? $department : null;
                                $isEmergency = (bool) ($decoded['emergency'] ?? false);

                                if ($isEmergency) {
                                    $qaAnswer['source'] = 'emergency_rule';
                                    $botReply = $locale === 'hi'
                                        ? 'यह एक आपातकालीन स्थिति हो सकती है। यदि आपको छाती में दर्द, सांस लेने में तकलीफ, या बेहोशी महसूस हो रही है, तो तुरंत आपातकालीन सेवाओं को कॉल करें और नजदीकी आपातकालीन कक्ष में जाएं।'
                                        : 'This may be an emergency. If you have chest pain, shortness of breath, or fainting, call emergency services immediately and go to the nearest emergency room.';
                                    $qaAnswer['answer'] = $botReply;
                                }
                                break;
                            }
                        } else {
                            logger()->warning('Groq chatbot request failed.', [
                                'model' => $model,
                                'status' => $response->status(),
                                'body' => Str::limit($response->body(), 1000),
                            ]);
                        }
                    } catch (Exception $e) {
                        logger()->error("Groq call exception with model {$model}: " . $e->getMessage(), [
                            'exception' => get_class($e),
                            'trace' => Str::limit($e->getTraceAsString(), 1000),
                        ]);
                    }
                }
            }

            // Fallback to static DB match if Groq call failed or key is missing
            if (!$qaAnswer && $originalMessage !== '') {
                logger()->info('AI calls failed or skipped. Falling back to static DB match.');
                $qaAnswer = $this->safeFindBestAnswer($originalMessage, $locale);
                if ($qaAnswer) {
                    logger()->info('Static DB match succeeded.', ['source' => $qaAnswer['source'] ?? 'unknown']);
                    $botReply = $qaAnswer['answer'];
                    $grokDepartment = $qaAnswer['category'] ?? null;
                    $qaSource = (string) ($qaAnswer['source'] ?? '');
                    $symptomMatch = (bool) $qaAnswer && (
                        in_array($qaSource, ['cached_medical_questions', 'medical_qa_config', 'grok_ai', 'grok_ai_cached', 'general_questions', 'faq'], true)
                        || str_ends_with($qaSource, '_like')
                    );
                }
            }

            if (!$qaAnswer) {
                logger()->info('Static DB match failed. Falling back to generated fallback answer.');
                $qaAnswer = $this->safeGenerateFallbackAnswer($originalMessage, $locale);
                if ($qaAnswer) {
                    logger()->info('Generated fallback answer succeeded.');
                    $botReply = $qaAnswer['answer'];
                    $grokDepartment = $qaAnswer['category'] ?? null;
                    $symptomMatch = true;
                }
            }

            $matchedDeptId = null;
            $matchedDiseaseNameEn = null;
            $matchedDiseaseNameHi = null;
            $departmentInfo = null;

            $doctors = collect();
            $hospitals = collect();
            $articles = collect();

            // Match department using classification from Grok AI (or fallback match)
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
                        ? "आपकी खोज '{$disName}' के लिए '{$deptName}' विभाग अनुशंसित है।"
                        : "For '{$disName}', '{$deptName}' department is recommended.";
                } else {
                    $departmentInfo = $locale === 'hi'
                        ? "आपकी खोज के आधार पर '{$deptName}' विभाग अनुशंसित है।"
                        : "Based on your query, '{$deptName}' department is recommended.";
                }
            }

            $deptForFilter = $matchedDeptId ?: 'All';
            $seeAllDoctorsUrl = route('doctors.index');
            $seeAllHospitalsUrl = route('hospitals.index');
            $seeAllArticlesUrl = route('articles.index', ['category' => $grokDepartment ?: 'All']);

            if (!$botReply) {
                if ($qaAnswer) {
                    if ($isDetailRequest) {
                        $detailedAnswer = $locale === 'hi'
                            ? ($qaAnswer['detailed_answer_hi'] ?? $qaAnswer['detailed_answer_en'] ?? $qaAnswer['detailed_answer'] ?? null)
                            : ($qaAnswer['detailed_answer_en'] ?? $qaAnswer['detailed_answer_hi'] ?? $qaAnswer['detailed_answer'] ?? null);
                        $botReply = (string) ($detailedAnswer ?: $qaAnswer['answer']);
                    } else {
                        $botReply = (string) $qaAnswer['answer'];
                    }
                } elseif ($departmentInfo) {
                    $botReply = $locale === 'hi'
                        ? 'मैंने आपकी खोज का विश्लेषण किया है। आपके शहर में संबंधित डॉक्टर और अस्पताल नीचे दिए गए हैं:'
                        : 'I analyzed your query. Here are relevant doctors and hospitals in your city:';
                } else {
                    $botReply = $locale === 'hi'
                        ? 'मुझे कोई सटीक परिणाम नहीं मिला, लेकिन आपके शहर में कुछ उपयोगी विकल्प नीचे दिए गए हैं।'
                        : 'I could not find an exact match, but here are useful options in your city.';
                }
            }

            $suggestDetails = false;
            if ($qaAnswer && !$isDetailRequest && ($qaAnswer['source'] ?? '') !== 'emergency_rule') {
                $detailedAnswer = $locale === 'hi'
                    ? ($qaAnswer['detailed_answer_hi'] ?? $qaAnswer['detailed_answer_en'] ?? $qaAnswer['detailed_answer'] ?? null)
                    : ($qaAnswer['detailed_answer_en'] ?? $qaAnswer['detailed_answer_hi'] ?? $qaAnswer['detailed_answer'] ?? null);
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

            logger()->info('Chatbot query processed successfully. Returning reply.', [
                'reply' => Str::limit($botReply, 100),
                'has_qa_answer' => $qaAnswer !== null,
            ]);
            $this->persistChatSessionSafely($chatSession, $messages);

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
            logger()->critical('Chatbot controller handleMessage crashed with exception: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => Str::limit($e->getTraceAsString(), 3000),
            ]);
            try {
                report($e);
            } catch (Throwable $_) {
            }

            try {
                $this->storeFailedQuery(
                    sessionToken: (string) ($request->input('session_token') ?: ''),
                    city: (string) ($request->input('city') ?: ''),
                    locale: (string) ($request->input('locale') ?: app()->getLocale()),
                    failureType: 'server_exception',
                    userMessage: (string) ($request->input('message') ?: ''),
                    errorMessage: $e->getMessage(),
                    meta: [
                        'exception' => get_class($e),
                        'trace_message' => Str::limit($e->getTraceAsString(), 4000),
                    ]
                );
            } catch (Throwable $_) {
            }

            $locale = (string) ($request->input('locale') ?: app()->getLocale());
            $fallbackReply = $locale === 'hi'
                ? 'एक तकनीकी समस्या आई है। कृपया एक क्षण में अपना संदेश पुनः भेजें।'
                : 'A technical issue occurred. Please resend your message in a moment.';

            return response()->json([
                'session_token' => (string) ($request->input('session_token') ?: Str::random(32)),
                'reply' => $fallbackReply,
                'city' => (string) ($request->input('city') ?: ''),
                'locale' => $locale === 'hi' ? 'hi' : 'en',
                'show_options' => false,
                'suggest_details' => false,
                'history' => [[
                    'sender' => 'bot',
                    'text' => $fallbackReply,
                    'show_options' => false,
                    'suggest_details' => false,
                    'timestamp' => now()->toIso8601String(),
                ]],
            ], 200);
        }
    }

    public function reportClientFailure(Request $request)
    {
        $locale = strtolower(trim((string) $request->input('locale', app()->getLocale())));
        $locale = in_array($locale, ['en', 'hi'], true) ? $locale : 'en';
        $meta = $request->input('meta');
        $meta = is_array($meta) ? $meta : [];

        $this->storeFailedQuery(
            sessionToken: Str::limit(trim((string) $request->input('session_token', '')), 64, ''),
            city: Str::limit(trim((string) $request->input('city', '')), 120, ''),
            locale: $locale,
            failureType: Str::limit(trim((string) $request->input('failure_type', 'client_fetch_failure')), 64, ''),
            userMessage: (string) $request->input('message', ''),
            errorMessage: (string) $request->input('error_message', ''),
            meta: $meta
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

    private function findOrCreateChatSessionSafely(string $sessionToken): ?ChatSession
    {
        try {
            if (!Schema::hasTable('chat_sessions')) {
                logger()->warning('chat_sessions table is missing. Chatbot will continue without persistence.');

                return null;
            }

            return ChatSession::firstOrCreate(
                ['session_token' => $sessionToken],
                ['messages' => []]
            );
        } catch (Throwable $e) {
            logger()->error('Unable to initialize chatbot session storage: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return null;
        }
    }

    private function persistChatSessionSafely(?ChatSession $chatSession, array $messages): void
    {
        if (!$chatSession) {
            return;
        }

        try {
            $chatSession->update(['messages' => $messages]);
        } catch (Throwable $e) {
            logger()->warning('Unable to persist chatbot session history: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'session_token' => $chatSession->session_token ?? null,
            ]);
        }
    }

    private function safeFindGeneralHelpResponse(string $message, string $locale, string $selectedCity = '', array $messages = []): ?array
    {
        try {
            return $this->findGeneralHelpResponse($message, $locale, $selectedCity, $messages);
        } catch (Throwable $e) {
            logger()->warning('General help chatbot lookup failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return null;
        }
    }

    private function safeFindMedicineResponse(string $message, string $locale): ?array
    {
        try {
            return $this->findMedicineResponse($message, $locale);
        } catch (Throwable $e) {
            logger()->warning('Medicine chatbot lookup failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return null;
        }
    }

    private function safeFindBestAnswer(string $message, string $locale): ?array
    {
        try {
            return $this->medicalQaService->findBestAnswer($message, $locale);
        } catch (Throwable $e) {
            logger()->warning('Medical QA lookup failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return null;
        }
    }

    private function safeGenerateFallbackAnswer(string $message, string $locale): ?array
    {
        try {
            return $this->medicalQaService->generateFallbackAnswer($message, $locale);
        } catch (Throwable $e) {
            logger()->warning('Medical QA generated fallback failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return null;
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
            'find',
            'search',
            'near',
            'nearby',
            'doctor',
            'hospital',
            'clinic',
            'blood bank',
            'specialist',
            'department',
            'show doctors',
            'show hospitals',
            'cardiologist',
            'orthopedic',
            'dermatologist',
            'खोज',
            'डॉक्टर',
            'अस्पताल',
            'क्लिनिक',
            'ब्लड बैंक',
            'विशेषज्ञ',
            'विभाग',
            'पास',
            'नजदीक',
        ];

        $aiMedicalIntentKeywords = [
            'why',
            'cause',
            'treatment',
            'medicine',
            'dosage',
            'dose',
            'diet',
            'prevention',
            'symptom meaning',
            'explain',
            'detail',
            'detailed',
            'serious',
            'is this dangerous',
            'क्यों',
            'कारण',
            'इलाज',
            'दवा',
            'खुराक',
            'उपचार',
            'समझाएं',
            'विस्तार',
            'गंभीर',
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

        $onboardingResponse = $this->resolveOnboardingResponse($normalized, $locale);
        if ($onboardingResponse !== null) {
            return $onboardingResponse;
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

        $tokens = $this->extractSearchTokens($normalized);
        $stopWords = [
            'have',
            'having',
            'feel',
            'feeling',
            'with',
            'need',
            'help',
            'show',
            'find',
            'doctors',
            'hospitals',
            'please',
            'what',
            'where',
            'when',
            'who',
            'about',
            'some',
            'many',
            'very',
            'severe',
            'mild',
            'pain',
            'ache',
            'दर्द'
        ];
        $filteredTokens = array_filter($tokens, function ($token) use ($stopWords) {
            return mb_strlen($token) >= 3 && !in_array(mb_strtolower($token), $stopWords, true);
        });

        $query = GeneralQuestion::query();
        $query->where(function ($q) use ($normalized, $filteredTokens) {
            $q->where('question_en', 'LIKE', "%{$normalized}%")
                ->orWhere('question_hi', 'LIKE', "%{$normalized}%");

            foreach ($filteredTokens as $token) {
                $q->orWhere('question_en', 'LIKE', "%{$token}%")
                    ->orWhere('question_hi', 'LIKE', "%{$token}%");
            }
        });

        $records = $query->limit(150)->get();
        $best = null;
        $bestScore = 0;

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

    private function resolveOnboardingResponse(string $normalizedMessage, string $locale): ?array
    {
        $isHindi = $locale === 'hi';

        $onboardingPhrases = [
            'start',
            'start chat',
            'start chatbot',
            'begin',
            'what to do',
            'how to start',
            'how do i start',
            'what should i do',
            'how to use',
            'where do i start',
            'kya karu',
            'kya karna chahiye',
            'shuru kaise karu',
        ];

        foreach ($onboardingPhrases as $phrase) {
            if ($normalizedMessage === $phrase) {
                return [
                    'text' => $isHindi
                        ? 'आप मुखपृष्ठ पर अपने लक्षण सामान्य भाषा में लिखकर शुरू कर सकते हैं। हमारा स्मार्ट symptom test आपको समझने में मदद करेगा कि आगे क्या करना है। बेहतर मार्गदर्शन के लिए step-by-step symptom check खोलें और अपनी जानकारी धीरे-धीरे भरें।'
                        : 'You can start by entering your symptoms in plain language on the homepage, and our AI will provide a smart assessment and clear advice on next steps. Try using the "Simple Analysis" or "Advanced Check" tool for personalized guidance.',
                    'qa_answer' => [
                        'question' => $isHindi ? 'What to do' : 'What to do',
                        'answer' => $isHindi
                            ? 'आप मुखपृष्ठ पर अपने लक्षण सामान्य भाषा में लिखकर शुरू कर सकते हैं।'
                            : 'You can start by entering your symptoms in plain language on the homepage, and our AI will provide a smart assessment and clear advice on next steps.',
                        'source' => 'chatbot_onboarding',
                    ],
                    'suggest_details' => false,
                ];
            }
        }

        return null;
    }

    private function findStrictConversationMatch(string $normalizedMessage, string $locale): ?array
    {
        $datasets = [
            ['query' => CachedMedicalQuestion::query(), 'source' => 'cached_medical_questions'],
            ['query' => GeneralQuestion::query(), 'source' => 'general_questions'],
        ];

        $stopWords = [
            'have',
            'having',
            'feel',
            'feeling',
            'with',
            'need',
            'help',
            'show',
            'find',
            'doctors',
            'hospitals',
            'please',
            'what',
            'where',
            'when',
            'who',
            'about',
            'some',
            'many',
            'very',
            'severe',
            'mild',
            'pain',
            'ache',
            'दर्द'
        ];

        foreach ($datasets as $dataset) {
            $best = null;
            $bestScore = 0;
            $tokens = $this->extractSearchTokens($normalizedMessage);

            // Filter out common generic/stop words to prevent loading massive record sets for common words like "pain"
            $filteredTokens = array_filter($tokens, function ($token) use ($stopWords) {
                return mb_strlen($token) >= 3 && !in_array(mb_strtolower($token), $stopWords, true);
            });

            // Filter records fetched from database to avoid Out-Of-Memory errors
            $query = $dataset['query'];
            $query->where(function ($q) use ($normalizedMessage, $filteredTokens) {
                $q->where('question_en', 'LIKE', "%{$normalizedMessage}%")
                    ->orWhere('question_hi', 'LIKE', "%{$normalizedMessage}%");

                foreach ($filteredTokens as $token) {
                    $q->orWhere('question_en', 'LIKE', "%{$token}%")
                        ->orWhere('question_hi', 'LIKE', "%{$token}%");
                }
            });

            // Double protection: Limit to 150 records to absolutely guarantee no OOM crash
            $records = $query->limit(150)->get();

            foreach ($records as $q) {
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

    private function normalizeAiText(mixed $value, string $fallback = ''): string
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_numeric($value) || is_bool($value)) {
            return trim((string) $value);
        }

        if (is_array($value)) {
            $flattened = $this->flattenAiValue($value);

            return $flattened !== '' ? $flattened : trim($fallback);
        }

        return trim($fallback);
    }

    private function flattenAiValue(array $value): string
    {
        $parts = [];

        array_walk_recursive($value, function (mixed $item) use (&$parts): void {
            if (is_string($item)) {
                $item = trim($item);
                if ($item !== '') {
                    $parts[] = $item;
                }
            } elseif (is_numeric($item) || is_bool($item)) {
                $parts[] = (string) $item;
            }
        });

        return trim(implode("\n", $parts));
    }

    private function normalizeGeminiModel(string $model): string
    {
        $model = mb_strtolower(trim($model));

        if ($model === '') {
            return 'gemini-3.5-flash';
        }

        return $model;
    }

    private function normalizeChatHistory(mixed $messages): array
    {
        if (! is_array($messages)) {
            return [];
        }

        $normalized = [];

        foreach ($messages as $message) {
            $entry = $this->normalizeChatHistoryEntry($message);
            if ($entry !== null) {
                $normalized[] = $entry;
            }
        }

        return array_values($normalized);
    }

    private function normalizeChatHistoryEntry(mixed $message): ?array
    {
        if (! is_array($message)) {
            return null;
        }

        $sender = strtolower(trim((string) ($message['sender'] ?? '')));
        if (! in_array($sender, ['user', 'bot'], true)) {
            return null;
        }

        $normalized = [
            'sender' => $sender,
            'text' => $this->normalizeAiText($message['text'] ?? ''),
        ];

        foreach (
            [
                'city',
                'locale',
                'timestamp',
                'response_mode',
                'load_type',
                'department_info',
                'see_all_doctors_url',
                'see_all_hospitals_url',
                'see_all_blood_banks_url',
                'see_all_articles_url',
            ] as $key
        ) {
            if (array_key_exists($key, $message)) {
                $normalized[$key] = $this->normalizeAiText($message[$key] ?? '');
            }
        }

        foreach (['show_options', 'suggest_details', 'symptom_match', 'needs_city'] as $key) {
            if (array_key_exists($key, $message)) {
                $normalized[$key] = (bool) $message[$key];
            }
        }

        foreach (['qa_answer', 'medicine_info'] as $key) {
            if (isset($message[$key]) && is_array($message[$key])) {
                $normalized[$key] = $message[$key];
            }
        }

        foreach (['city_options', 'doctors', 'hospitals', 'blood_banks', 'articles'] as $key) {
            if (isset($message[$key])) {
                $normalized[$key] = $this->normalizeChatHistoryList($message[$key]);
            }
        }

        return $normalized;
    }

    private function normalizeChatHistoryList(mixed $items): array
    {
        if ($items instanceof \Illuminate\Support\Collection) {
            $items = $items->values()->all();
        }

        if (! is_array($items)) {
            return [];
        }

        return array_values($items);
    }

    private function queryGemini(
        string $apiKey,
        string $model,
        string $originalMessage,
        string $locale,
        array $messages = []
    ): ?array {
        $systemPrompt = "You are Jeeva, a professional, warm, empathetic, and knowledgeable medical and healthcare assistant. " .
            "Provide safe initial guidance based on the user's symptoms or health question.\n\n" .
            "Return only a JSON object with these keys:\n" .
            "- reply: short, warm, direct answer in " . ($locale === 'hi' ? 'Hindi' : 'English') . ".\n" .
            "- detailed_reply: detailed markdown-friendly explanation in " . ($locale === 'hi' ? 'Hindi' : 'English') . ", or empty string if not needed.\n" .
            "- department: best matching medical department in English, or empty string.\n" .
            "- symptom_match: boolean.\n" .
            "- emergency: boolean.\n" .
            "Do not add code fences or extra text.";

        $context = $this->medicalQaService->retrieveRelevantContext($originalMessage, $locale);
        $historyLines = collect($messages)
            ->slice(0, -1)
            ->filter(fn($msg) => isset($msg['sender'], $msg['text']) && in_array($msg['sender'], ['user', 'bot'], true))
            ->take(-10)
            ->map(function ($msg) {
                $role = $msg['sender'] === 'user' ? 'User' : 'Assistant';
                return $role . ': ' . $msg['text'];
            })
            ->implode("\n");

        $input = $systemPrompt;
        if ($context !== '') {
            $input .= "\n\nKnowledge base context:\n" . $context;
        }
        if ($historyLines !== '') {
            $input .= "\n\nRecent conversation:\n" . $historyLines;
        }
        $input .= "\n\nCurrent user message:\n" . $originalMessage;

        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $input]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'reply' => ['type' => 'STRING'],
                        'detailed_reply' => ['type' => 'STRING'],
                        'department' => ['type' => 'STRING'],
                        'symptom_match' => ['type' => 'BOOLEAN'],
                        'emergency' => ['type' => 'BOOLEAN'],
                    ],
                    'required' => ['reply', 'symptom_match', 'emergency'],
                ],
            ],
        ];

        try {
            logger()->info('Sending POST request to Gemini generateContent API...', [
                'model' => $model,
                'has_key' => $apiKey !== '',
            ]);
            $response = $this->chatbotHttpClient()->retry(2, 250)
                ->connectTimeout(8)
                ->timeout(20)
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent', $body);

            if (! $response->successful()) {
                logger()->warning('Gemini chatbot request failed.', [
                    'model' => $model,
                    'status' => $response->status(),
                    'body' => Str::limit($response->body(), 1000),
                ]);

                return null;
            }

            $text = $this->extractGeminiOutputText($response->json());
            if ($text === '') {
                logger()->warning('Gemini chatbot response missing model output text.', [
                    'model' => $model,
                    'body' => Str::limit($response->body(), 1000),
                ]);

                return null;
            }

            $decoded = $this->decodeAiJsonResponse($text);

            return is_array($decoded) ? $decoded : null;
        } catch (Throwable $e) {
            logger()->error('Gemini chatbot exception caught in queryGemini: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => Str::limit($e->getTraceAsString(), 1000),
            ]);

            return null;
        }
    }

    private function extractGeminiOutputText(array $payload): string
    {
        $directPaths = [
            'output.0.content.0.text',
            'response.output.0.content.0.text',
            'candidates.0.content.parts.0.text',
        ];

        foreach ($directPaths as $path) {
            $text = trim((string) data_get($payload, $path, ''));
            if ($text !== '') {
                return $text;
            }
        }

        $steps = $payload['steps'] ?? [];

        foreach ($steps as $step) {
            if (($step['type'] ?? null) !== 'model_output') {
                continue;
            }

            foreach (($step['content'] ?? []) as $content) {
                $text = trim((string) ($content['text'] ?? ''));
                if ($text !== '') {
                    return $text;
                }
            }
        }

        return '';
    }

    private function chatbotHttpClient(): PendingRequest
    {
        return Http::withOptions([
            'verify' => $this->resolveChatbotSslVerification(),
        ]);
    }

    private function resolveChatbotSslVerification(): bool|string
    {
        if ((bool) config('variable.chatbot_disable_ssl_verify', false)) {
            logger()->warning('Chatbot SSL verification has been disabled by configuration.');

            return false;
        }

        if (app()->environment('local')) {
            return false;
        }

        $configuredBundlePath = trim((string) config('variable.chatbot_ca_bundle_path', ''));
        if ($configuredBundlePath !== '') {
            if (is_file($configuredBundlePath) && is_readable($configuredBundlePath)) {
                return $configuredBundlePath;
            }

            logger()->warning('Configured chatbot CA bundle path is missing or unreadable.', [
                'path' => $configuredBundlePath,
            ]);
        }

        $defaultBundlePath = storage_path('app/cacert.pem');
        if (is_file($defaultBundlePath) && is_readable($defaultBundlePath)) {
            return $defaultBundlePath;
        }

        logger()->info('Chatbot CA bundle not found. Falling back to system CA trust store.');

        return true;
    }

    private function decodeAiJsonResponse(mixed $content): ?array
    {
        if (is_array($content)) {
            return $content;
        }

        if (! is_string($content)) {
            return null;
        }

        $trimmed = trim($content);
        if ($trimmed === '') {
            return null;
        }

        $decoded = json_decode($trimmed, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/```(?:json)?\s*(\{.*\})\s*```/is', $trimmed, $matches) === 1) {
            $decoded = json_decode(trim($matches[1]), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $jsonStart = strpos($trimmed, '{');
        $jsonEnd = strrpos($trimmed, '}');
        if ($jsonStart === false || $jsonEnd === false || $jsonEnd <= $jsonStart) {
            return null;
        }

        $candidate = substr($trimmed, $jsonStart, $jsonEnd - $jsonStart + 1);
        $decoded = json_decode($candidate, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function findMedicineResponse(string $message, string $locale): ?array
    {
        $normalized = trim($message);
        if ($normalized === '') {
            return null;
        }

        $explicitKeywords = ['medicine', 'tablet', 'capsule', 'dose', 'dosage', 'dolo', 'paracetamol', 'cetirizine', 'azithromycin', 'metformin', 'pantoprazole', 'दवा', 'टैबलेट'];
        $hasMedicineIntent = collect($explicitKeywords)->contains(fn($keyword) => mb_stripos($normalized, $keyword) !== false);

        // Strip common helper words for cleaner database search matching
        $searchQuery = $normalized;
        $helperWords = ['medicine', 'tablet', 'capsule', 'dose', 'dosage', 'tablets', 'capsules', 'doses', 'दवा', 'टैबलेट', 'दवाइयाँ', 'दवाई'];
        foreach ($helperWords as $word) {
            $searchQuery = preg_replace('/\b' . preg_quote($word, '/') . '\b/iu', '', $searchQuery) ?? $searchQuery;
            if (in_array($word, ['दवा', 'टैबलेट', 'दवाइयाँ', 'दवाई'], true)) {
                $searchQuery = str_replace($word, '', $searchQuery);
            }
        }
        $searchQuery = trim(preg_replace('/\s+/', ' ', $searchQuery));

        $medicine = Medicine::query()
            ->published()
            ->search($searchQuery !== '' ? $searchQuery : $normalized)
            ->first();

        if (! $medicine || ! $hasMedicineIntent) {
            return null;
        }

        $url = route('medicines.show', $medicine->slug);
        $purpose = $medicine->getTranslation('purpose', $locale) ?: ($medicine->category ?: $medicine->generic_name ?: $medicine->name);

        return [
            'text' => $locale === 'hi'
                ? "मुझे {$medicine->name} के लिए सामान्य जानकारी मिली है। {$purpose}। मैं यह पुष्टि नहीं कर सकता कि यह आपके लिए व्यक्तिगत रूप से सुरक्षित है। कृपया डॉक्टर या फार्मासिस्ट से सलाह लें।"
                : "I found general information for {$medicine->name}. {$purpose}. I cannot confirm whether it is personally safe for you, so please consult a doctor or pharmacist.",
            'medicine_info' => [
                'name' => $medicine->name,
                'url' => $url,
            ],
        ];
    }
}
