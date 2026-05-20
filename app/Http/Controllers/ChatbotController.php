<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ChatSession;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Services\MedicalQaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(private readonly MedicalQaService $medicalQaService)
    {
    }

    public function handleMessage(Request $request)
    {
        $validated = $request->validate([
            'session_token' => 'nullable|string',
            'message' => 'required|string',
            'city' => 'nullable|string|max:120',
            'locale' => 'nullable|in:en,hi',
        ]);

        $sessionToken = $validated['session_token'] ?? Str::random(32);
        $userMessage = trim($validated['message']);
        $locale = $validated['locale'] ?? app()->getLocale();

        $chatSession = ChatSession::firstOrCreate(
            ['session_token' => $sessionToken],
            ['messages' => []]
        );

        $messages = $chatSession->messages ?? [];

        $cityOptions = Hospital::where('is_verified', true)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->filter()
            ->values()
            ->all();

        $selectedCity = trim((string) ($validated['city'] ?? ''));
        if ($selectedCity === '') {
            $lastCityMessage = collect($messages)->reverse()->first(fn ($msg) => !empty($msg['city']));
            $selectedCity = (string) ($lastCityMessage['city'] ?? '');
        }

        if ($selectedCity === '' || !in_array($selectedCity, $cityOptions, true)) {
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

        $messages[] = [
            'sender' => 'user',
            'text' => $userMessage,
            'city' => $selectedCity,
            'locale' => $locale,
            'timestamp' => now()->toIso8601String(),
        ];

        $qaAnswer = $this->medicalQaService->findBestAnswer($userMessage, $locale);
        if (! $qaAnswer) {
            $qaAnswer = $this->medicalQaService->generateFallbackAnswer($userMessage, $locale);
        }

        $matchedDeptId = null;
        $matchedDiseaseNameEn = null;
        $matchedDiseaseNameHi = null;
        $departmentInfo = null;

        $doctors = collect();

        if (DB::getDriverName() === 'pgsql') {
            try {
                $stringObj = Str::of($userMessage);
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
                if (! empty($disease->name_en) && stripos($userMessage, $disease->name_en) !== false) {
                    $matchedDeptId = $disease->department_id;
                    $matchedDiseaseNameEn = $disease->name_en;
                    $matchedDiseaseNameHi = $disease->name_hi;
                    break;
                }
                if (! empty($disease->name_hi) && mb_stripos($userMessage, $disease->name_hi) !== false) {
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
                if (! empty($dept->name_en) && stripos($userMessage, $dept->name_en) !== false) {
                    $matchedDeptId = $dept->id;
                    break;
                }
                if (! empty($dept->name_hi) && mb_stripos($userMessage, $dept->name_hi) !== false) {
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
                    ? "'{$disName}' के लिए '{$deptName}' विभाग उपयुक्त है।"
                    : "For '{$disName}', '{$deptName}' department is recommended.";
            } else {
                $departmentInfo = $locale === 'hi'
                    ? "आपकी समस्या के आधार पर '{$deptName}' विभाग उपयुक्त है।"
                    : "Based on your query, '{$deptName}' department is recommended.";
            }

            $doctors = Doctor::where('department_id', $matchedDeptId)
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->whereHas('hospitals', fn ($q) => $q->where('city', $selectedCity))
                ->take(3)
                ->get();
        }

        if ($doctors->isEmpty()) {
            $doctors = Doctor::where(function ($query) use ($userMessage) {
                $query->where('first_name', 'LIKE', "%{$userMessage}%")
                    ->orWhere('last_name', 'LIKE', "%{$userMessage}%");
            })
            ->with(['department', 'hospitals'])
            ->where('is_verified', true)
            ->whereHas('hospitals', fn ($q) => $q->where('city', $selectedCity))
            ->take(3)
            ->get();
        }

        $hospitals = Hospital::where('is_verified', true)
            ->where('city', $selectedCity)
            ->where(function ($query) use ($userMessage) {
                $query->where('name_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('name_hi', 'LIKE', "%{$userMessage}%")
                    ->orWhere('address', 'LIKE', "%{$userMessage}%")
                    ->orWhere('type', 'LIKE', "%{$userMessage}%");
            })
            ->take(3)
            ->get();

        if ($hospitals->isEmpty()) {
            $hospitals = Hospital::where('is_verified', true)->where('city', $selectedCity)->latest()->take(3)->get();
        }

        $articles = Article::where('is_published', true)
            ->where(function ($query) use ($userMessage) {
                $query->where('title_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('title_hi', 'LIKE', "%{$userMessage}%")
                    ->orWhere('content_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('content_hi', 'LIKE', "%{$userMessage}%");
            })
            ->take(3)
            ->get();

        if ($articles->isEmpty()) {
            $articles = Article::where('is_published', true)->latest()->take(3)->get();
        }

        $deptForFilter = $matchedDeptId ?: (optional($doctors->first())->department_id ?? 'All');
        $seeAllDoctorsUrl = route('doctors.index', ['city' => $selectedCity, 'department' => $deptForFilter ?: 'All']);
        $seeAllHospitalsUrl = route('hospitals.index', ['city' => $selectedCity]);

        if ($qaAnswer && ($qaAnswer['source'] ?? '') === 'emergency_rule') {
            $botReply = $locale === 'hi'
                ? 'यह संभवतः आपातकाल हो सकता है। यदि सीने में दर्द है तो तुरंत इमरजेंसी सेवा पर कॉल करें और नजदीकी इमरजेंसी में जाएं।'
                : 'This may be an emergency. If there is chest pain, call emergency services immediately and go to the nearest emergency room.';
        } elseif ($qaAnswer) {
            $botReply = (string) $qaAnswer['answer'];
        } elseif ($departmentInfo) {
            $botReply = $locale === 'hi'
                ? 'मैंने आपके प्रश्न का विश्लेषण किया है। नीचे आपके शहर के संबंधित डॉक्टर और अस्पताल दिए गए हैं:'
                : 'I analyzed your query. Here are relevant doctors and hospitals in your city:';
        } else {
            $botReply = $locale === 'hi'
                ? 'मुझे सटीक मिलान नहीं मिला, लेकिन नीचे आपके शहर के उपयोगी विकल्प दिए गए हैं।'
                : 'I could not find an exact match, but here are useful options in your city.';
        }

        $messages[] = [
            'sender' => 'bot',
            'text' => $botReply,
            'city' => $selectedCity,
            'locale' => $locale,
            'qa_answer' => $qaAnswer,
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'articles' => $articles,
            'see_all_doctors_url' => $seeAllDoctorsUrl,
            'see_all_hospitals_url' => $seeAllHospitalsUrl,
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
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'articles' => $articles,
            'see_all_doctors_url' => $seeAllDoctorsUrl,
            'see_all_hospitals_url' => $seeAllHospitalsUrl,
            'history' => $messages,
        ]);
    }
}
