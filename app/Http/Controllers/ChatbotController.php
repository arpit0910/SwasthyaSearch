<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ChatSession;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Hospital;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function handleMessage(Request $request)
    {
        $validated = $request->validate([
            'session_token' => 'nullable|string',
            'message' => 'required|string',
        ]);

        $sessionToken = $validated['session_token'] ?? Str::random(32);
        $userMessage = trim($validated['message']);
        $locale = app()->getLocale();

        // Retrieve or create chat session
        $chatSession = ChatSession::firstOrCreate(
            ['session_token' => $sessionToken],
            ['messages' => []]
        );

        $messages = $chatSession->messages ?? [];
        $messages[] = ['sender' => 'user', 'text' => $userMessage, 'timestamp' => now()->toIso8601String()];

        // Interpret message to find relevant doctors, hospitals, articles, and department
        $matchedDeptId = null;
        $matchedDiseaseNameEn = null;
        $matchedDiseaseNameHi = null;
        $departmentInfo = null;

        $doctors = collect();
        $hospitals = collect();
        $articles = collect();

        // Strategy 1: If pgsql, try vector search
        if (DB::getDriverName() === 'pgsql') {
            try {
                $stringObj = Str::of($userMessage);
                $userEmbedding = method_exists($stringObj, 'toEmbeddings') ? $stringObj->toEmbeddings() : json_encode(array_fill(0, 1536, 0.01));
                $diseases = Disease::whereVectorSimilarTo('symptoms_embedding', $userEmbedding)
                    ->with(['department'])
                    ->get();

                if ($diseases->isNotEmpty()) {
                    $firstMatch = $diseases->first();
                    $matchedDeptId = $firstMatch->department_id;
                    $matchedDiseaseNameEn = $firstMatch->name_en;
                    $matchedDiseaseNameHi = $firstMatch->name_hi;
                }
            } catch (Exception $e) {
                // Fallback to text matching
            }
        }

        // Strategy 2: Substring matching in PHP against all Diseases (handles sentences perfectly)
        if (!$matchedDeptId) {
            $allDiseases = Disease::with('department')->get();
            foreach ($allDiseases as $disease) {
                if (!empty($disease->name_en) && stripos($userMessage, $disease->name_en) !== false) {
                    $matchedDeptId = $disease->department_id;
                    $matchedDiseaseNameEn = $disease->name_en;
                    $matchedDiseaseNameHi = $disease->name_hi;
                    break;
                }
                if (!empty($disease->name_hi) && mb_stripos($userMessage, $disease->name_hi) !== false) {
                    $matchedDeptId = $disease->department_id;
                    $matchedDiseaseNameEn = $disease->name_en;
                    $matchedDiseaseNameHi = $disease->name_hi;
                    break;
                }
            }
        }

        // Strategy 3: Substring matching in PHP against all Departments
        if (!$matchedDeptId) {
            $allDepartments = Department::where('is_active', true)->get();
            foreach ($allDepartments as $dept) {
                if (!empty($dept->name_en) && stripos($userMessage, $dept->name_en) !== false) {
                    $matchedDeptId = $dept->id;
                    break;
                }
                if (!empty($dept->name_hi) && mb_stripos($userMessage, $dept->name_hi) !== false) {
                    $matchedDeptId = $dept->id;
                    break;
                }
            }
        }

        // Strategy 4: Word-by-word LIKE search in DB (for partial keyword queries)
        if (!$matchedDeptId) {
            $words = array_filter(explode(' ', $userMessage), fn($w) => mb_strlen($w) > 3);
            foreach ($words as $word) {
                $diseaseMatch = Disease::where('name_en', 'LIKE', "%{$word}%")
                    ->orWhere('name_hi', 'LIKE', "%{$word}%")
                    ->with('department')
                    ->first();
                if ($diseaseMatch) {
                    $matchedDeptId = $diseaseMatch->department_id;
                    $matchedDiseaseNameEn = $diseaseMatch->name_en;
                    $matchedDiseaseNameHi = $diseaseMatch->name_hi;
                    break;
                }

                $deptMatch = Department::where('name_en', 'LIKE', "%{$word}%")
                    ->orWhere('name_hi', 'LIKE', "%{$word}%")
                    ->first();
                if ($deptMatch) {
                    $matchedDeptId = $deptMatch->id;
                    break;
                }
            }
        }

        // Determine department info & fetch doctors
        if ($matchedDeptId) {
            $dept = Department::find($matchedDeptId);
            $deptNameEn = $dept ? $dept->name_en : '';
            $deptNameHi = $dept ? $dept->name_hi : '';
            $deptName = $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn;

            if ($matchedDiseaseNameEn) {
                $disName = $locale === 'hi' ? ($matchedDiseaseNameHi ?: $matchedDiseaseNameEn) : $matchedDiseaseNameEn;
                $departmentInfo = $locale === 'hi'
                    ? "यदि आपको '{$disName}' की समस्या/लक्षण है, तो आपको '{$deptName}' विभाग में जाना चाहिए।"
                    : "For symptoms related to '{$disName}', you should visit the '{$deptName}' department.";
            } else {
                $departmentInfo = $locale === 'hi'
                    ? "आपके लक्षणों के आधार पर आपको '{$deptName}' विभाग में जाना चाहिए।"
                    : "Based on your inquiry, you should visit the '{$deptName}' department.";
            }

            $doctors = Doctor::where('department_id', $matchedDeptId)
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->take(5)
                ->get();
        } else {
            // Check if user is searching for a doctor's name directly
            $doctors = Doctor::where(function ($query) use ($userMessage) {
                $query->where('first_name', 'LIKE', "%{$userMessage}%")
                    ->orWhere('last_name', 'LIKE', "%{$userMessage}%");
                $words = array_filter(explode(' ', $userMessage), fn($w) => mb_strlen($w) > 2);
                foreach ($words as $word) {
                    $query->orWhere('first_name', 'LIKE', "%{$word}%")
                        ->orWhere('last_name', 'LIKE', "%{$word}%");
                }
            })
            ->with(['department', 'hospitals'])
            ->where('is_verified', true)
            ->take(5)
            ->get();
        }

        // Fetch Hospitals matching query or general top verified hospitals
        $hospitals = Hospital::where('is_verified', true)
            ->where(function ($query) use ($userMessage) {
                $query->where('name_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('name_hi', 'LIKE', "%{$userMessage}%")
                    ->orWhere('address', 'LIKE', "%{$userMessage}%")
                    ->orWhere('city', 'LIKE', "%{$userMessage}%")
                    ->orWhere('type', 'LIKE', "%{$userMessage}%");
                $words = array_filter(explode(' ', $userMessage), fn($w) => mb_strlen($w) > 3);
                foreach ($words as $word) {
                    $query->orWhere('name_en', 'LIKE', "%{$word}%")
                        ->orWhere('name_hi', 'LIKE', "%{$word}%")
                        ->orWhere('city', 'LIKE', "%{$word}%");
                }
            })
            ->take(3)
            ->get();

        if ($hospitals->isEmpty()) {
            $hospitals = Hospital::where('is_verified', true)->latest()->take(3)->get();
        }

        // Fetch Articles matching query or general latest articles
        $articles = Article::where('is_published', true)
            ->where(function ($query) use ($userMessage) {
                $query->where('title_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('title_hi', 'LIKE', "%{$userMessage}%")
                    ->orWhere('content_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('content_hi', 'LIKE', "%{$userMessage}%");
                $words = array_filter(explode(' ', $userMessage), fn($w) => mb_strlen($w) > 3);
                foreach ($words as $word) {
                    $query->orWhere('title_en', 'LIKE', "%{$word}%")
                        ->orWhere('title_hi', 'LIKE', "%{$word}%");
                }
            })
            ->take(3)
            ->get();

        if ($articles->isEmpty()) {
            $articles = Article::where('is_published', true)->latest()->take(3)->get();
        }

        // Build bot reply
        if ($departmentInfo) {
            $botReply = $locale === 'hi'
                ? "मैंने आपके लक्षणों का विश्लेषण किया है। नीचे अनुशंसित विभाग, विशेषज्ञ डॉक्टर, प्रमुख अस्पताल और संबंधित स्वास्थ्य लेख दिए गए हैं:"
                : "I have analyzed your request. Below is the recommended department, along with top doctors, hospitals, and related health articles:";
        } elseif ($doctors->isNotEmpty() || $hospitals->isNotEmpty() || $articles->isNotEmpty()) {
            $botReply = $locale === 'hi'
                ? "यहाँ आपके खोज से संबंधित डॉक्टर, अस्पताल और स्वास्थ्य लेख दिए गए हैं:"
                : "Here are the doctors, hospitals, and health articles related to your search:";
        } else {
            $botReply = $locale === 'hi'
                ? "माफ़ कीजिए, मुझे '{$userMessage}' से संबंधित कोई सटीक जानकारी नहीं मिली। कृपया किसी अन्य लक्षण या बीमारी का नाम दर्ज करें।"
                : "I'm sorry, I couldn't find exact details for '{$userMessage}'. Please try searching for another symptom or medical term.";
        }

        $messages[] = [
            'sender' => 'bot',
            'text' => $botReply,
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'articles' => $articles,
            'timestamp' => now()->toIso8601String(),
        ];

        $chatSession->update(['messages' => $messages]);

        return response()->json([
            'session_token' => $sessionToken,
            'reply' => $botReply,
            'department_info' => $departmentInfo,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'articles' => $articles,
            'history' => $messages,
        ]);
    }
}
