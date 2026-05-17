<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Doctor;
use Exception;
use Illuminate\Http\Request;
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

        // Interpret message to find relevant doctors/departments
        $matchedDeptId = null;
        $matchedDiseaseName = null;

        try {
            /** @var mixed $stringObj */
            $stringObj = Str::of($userMessage);
            $userEmbedding = method_exists($stringObj, 'toEmbeddings') ? $stringObj->toEmbeddings() : json_encode(array_fill(0, 1536, 0.01));
            $diseases = Disease::whereVectorSimilarTo('symptoms_embedding', $userEmbedding)
                ->with(['department'])
                ->get();

            if ($diseases->isNotEmpty()) {
                $firstMatch = $diseases->first();
                $matchedDeptId = $firstMatch->department_id;
                $matchedDiseaseName = $locale === 'hi' ? $firstMatch->name_hi : $firstMatch->name_en;
            }
        } catch (Exception $e) {
            $diseaseMatch = Disease::where(function ($query) use ($userMessage) {
                $query->where('name_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('name_hi', 'LIKE', "%{$userMessage}%");
            })->with('department')->first();
            if ($diseaseMatch) {
                $matchedDeptId = $diseaseMatch->department_id;
                $matchedDiseaseName = $locale === 'hi' ? $diseaseMatch->name_hi : $diseaseMatch->name_en;
            }
        }

        if (!$matchedDeptId) {
            $deptMatch = Department::where(function ($query) use ($userMessage) {
                $query->where('name_en', 'LIKE', "%{$userMessage}%")
                    ->orWhere('name_hi', 'LIKE', "%{$userMessage}%");
            })->first();
            if ($deptMatch) {
                $matchedDeptId = $deptMatch->id;
            }
        }

        if ($matchedDeptId) {
            $doctors = Doctor::where('department_id', $matchedDeptId)
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->get();
            $dept = Department::find($matchedDeptId);
            $deptName = $dept ? ($locale === 'hi' ? $dept->name_hi : $dept->name_en) : '';

            if ($matchedDiseaseName) {
                $botReply = $locale === 'hi'
                    ? "मुझे समझ आया कि आप '{$matchedDiseaseName}' से संबंधित डॉक्टर ढूंढ रहे हैं। यह '{$deptName}' विभाग के अंतर्गत आता है। यहाँ कुछ बेहतरीन डॉक्टर हैं:"
                    : "I understand you are looking for help with '{$matchedDiseaseName}'. This falls under the '{$deptName}' department. Here are some verified specialists:";
            } else {
                $botReply = $locale === 'hi'
                    ? "यहाँ '{$deptName}' विभाग के हमारे विशेषज्ञ डॉक्टर हैं:"
                    : "Here are our verified specialists in the '{$deptName}' department:";
            }
        } else {
            $doctors = Doctor::where('first_name', 'LIKE', "%{$userMessage}%")
                ->orWhere('last_name', 'LIKE', "%{$userMessage}%")
                ->with(['department', 'hospitals'])
                ->where('is_verified', true)
                ->get();

            if ($doctors->isNotEmpty()) {
                $botReply = $locale === 'hi'
                    ? "आपके खोज के आधार पर मुझे ये डॉक्टर मिले हैं:"
                    : "Here are the doctors matching your query:";
            } else {
                $botReply = $locale === 'hi'
                    ? "माफ़ कीजिए, मुझे '{$userMessage}' से संबंधित कोई related डॉक्टर या विभाग नहीं मिला। कृपया किसी अन्य लक्षण या डॉक्टर का नाम दर्ज करें।"
                    : "I'm sorry, I couldn't find any doctors or departments matching '{$userMessage}'. Please try searching for another symptom or specialty.";
            }
        }

        $messages[] = [
            'sender' => 'bot',
            'text' => $botReply,
            'doctors' => $doctors,
            'timestamp' => now()->toIso8601String(),
        ];

        $chatSession->update(['messages' => $messages]);

        return response()->json([
            'session_token' => $sessionToken,
            'reply' => $botReply,
            'doctors' => $doctors,
            'history' => $messages,
        ]);
    }
}
