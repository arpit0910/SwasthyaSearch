<?php

namespace App\Services;

use App\Models\Disease;
use App\Models\CachedMedicalQuestion;
use App\Models\Faq;
use App\Models\GeneralQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MedicalQaService
{
    /**
     * @return array{question:string,answer:string,category:string,source:string,source_id:int|null,source_table:string|null,confidence:float,detailed_answer_en:?string,detailed_answer_hi:?string,detailed_answer:?string}|null
     */
    public function findBestAnswer(string $message, string $locale = 'en'): ?array
    {
        $normalizedMessage = $this->normalize($message);
        if ($normalizedMessage === '') {
            return null;
        }

        $emergencyAnswer = $this->matchEmergency($normalizedMessage, $locale);
        if ($emergencyAnswer) {
            return $emergencyAnswer;
        }

        $likeMatch = $this->findLikeMatch($message, $locale);
        if ($likeMatch) {
            return $likeMatch;
        }

        $entries = $this->buildKnowledgeEntries();
        if ($entries->isEmpty()) {
            return null;
        }

        $best = null;
        $bestScore = 0.0;

        foreach ($entries as $entry) {
            $question = $this->normalize((string) ($entry['question_en'] ?? ''));
            $questionHi = $this->normalize((string) ($entry['question_hi'] ?? ''));
            $keywords = collect($entry['keywords'] ?? [])->map(fn ($k) => $this->normalize((string) $k))->filter();

            $score = $this->scoreMatch($normalizedMessage, $question, $questionHi, $keywords->all());
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $entry;
            }
        }

        if (!$best || $bestScore < 45) {
            $fallback = $this->generateFallbackAnswer($message, $locale);
            if ($fallback) {
                return [
                    'question' => $fallback['question'],
                    'answer' => $fallback['answer'],
                    'category' => $fallback['category'],
                    'source' => $fallback['source'],
                    'source_id' => null,
                    'source_table' => null,
                    'confidence' => $fallback['confidence'],
                    'detailed_answer_en' => null,
                    'detailed_answer_hi' => null,
                    'detailed_answer' => null,
                ];
            }

            return null;
        }

        $answer = $locale === 'hi'
            ? ($best['answer_hi'] ?: $best['answer_en'])
            : ($best['answer_en'] ?: $best['answer_hi']);

        $question = $locale === 'hi'
            ? ($best['question_hi'] ?: $best['question_en'])
            : ($best['question_en'] ?: $best['question_hi']);

        $detailedAnswerEn = isset($best['detailed_answer_en']) ? trim((string) $best['detailed_answer_en']) : '';
        $detailedAnswerHi = isset($best['detailed_answer_hi']) ? trim((string) $best['detailed_answer_hi']) : '';
        $detailedAnswer = $locale === 'hi'
            ? ($detailedAnswerHi !== '' ? $detailedAnswerHi : ($detailedAnswerEn !== '' ? $detailedAnswerEn : null))
            : ($detailedAnswerEn !== '' ? $detailedAnswerEn : ($detailedAnswerHi !== '' ? $detailedAnswerHi : null));

        return [
            'question' => (string) $question,
            'answer' => (string) $answer,
            'category' => (string) ($best['category'] ?? 'General Medical'),
            'source' => (string) ($best['source'] ?? 'faq_dataset'),
            'source_id' => isset($best['source_id']) ? (int) $best['source_id'] : null,
            'source_table' => isset($best['source_table']) ? (string) $best['source_table'] : null,
            'confidence' => round($bestScore, 2),
            'detailed_answer_en' => $detailedAnswerEn !== '' ? $detailedAnswerEn : null,
            'detailed_answer_hi' => $detailedAnswerHi !== '' ? $detailedAnswerHi : null,
            'detailed_answer' => $detailedAnswer,
        ];
    }

    /**
     * Direct %LIKE%-style matching to handle short symptom/disease prompts
     * such as "HIV", "AIDS", and slash/variant forms like "HIV/AIDS".
     *
     * @return array{question:string,answer:string,category:string,source:string,source_id:int|null,source_table:string|null,confidence:float,detailed_answer_en:?string,detailed_answer_hi:?string,detailed_answer:?string}|null
     */
    private function findLikeMatch(string $message, string $locale): ?array
    {
        $raw = trim($message);
        if ($raw === '') {
            return null;
        }

        $terms = $this->expandSearchTerms($raw);
        if (empty($terms)) {
            return null;
        }

        // Multi-source LIKE-style match:
        // CachedMedicalQuestion + GeneralQuestion + Faq (+ configured entries)
        $entries = $this->buildKnowledgeEntries();
        if ($entries->isEmpty()) {
            return null;
        }

        $normalizedMessage = $this->normalize($message);
        $best = null;
        $bestScore = -1.0;

        foreach ($entries as $entry) {
            $entryQuestionEn = (string) ($entry['question_en'] ?? '');
            $entryQuestionHi = (string) ($entry['question_hi'] ?? '');
            $entryAnswerEn = (string) ($entry['answer_en'] ?? '');
            $entryAnswerHi = (string) ($entry['answer_hi'] ?? '');
            $entryDetailedEn = (string) ($entry['detailed_answer_en'] ?? '');
            $entryDetailedHi = (string) ($entry['detailed_answer_hi'] ?? '');
            $entryCategory = (string) ($entry['category'] ?? '');

            $containsAnyTerm = false;
            foreach ($terms as $term) {
                $termNorm = $this->normalize($term);
                if ($termNorm === '') {
                    continue;
                }

                $haystack = $this->normalize(
                    trim($entryQuestionEn . ' ' . $entryQuestionHi . ' ' . $entryAnswerEn . ' ' . $entryAnswerHi . ' ' . $entryDetailedEn . ' ' . $entryDetailedHi . ' ' . $entryCategory)
                );

                if ($haystack !== '' && str_contains($haystack, $termNorm)) {
                    $containsAnyTerm = true;
                    break;
                }
            }

            if (! $containsAnyTerm) {
                continue;
            }

            $score = $this->scoreMatch(
                $normalizedMessage,
                $this->normalize($entryQuestionEn),
                $this->normalize($entryQuestionHi),
                []
            );

            foreach ($terms as $term) {
                $termNorm = $this->normalize($term);
                if ($termNorm !== '' && (
                    str_contains($this->normalize($entryQuestionEn), $termNorm) ||
                    str_contains($this->normalize($entryQuestionHi), $termNorm) ||
                    str_contains($this->normalize($entryAnswerEn), $termNorm) ||
                    str_contains($this->normalize($entryAnswerHi), $termNorm) ||
                    str_contains($this->normalize($entryDetailedEn), $termNorm) ||
                    str_contains($this->normalize($entryDetailedHi), $termNorm) ||
                    str_contains($this->normalize($entryCategory), $termNorm)
                )) {
                    $score += 12;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $entry;
            }
        }

        if (! $best) {
            return null;
        }

        // Guardrail: avoid false positives from broad LIKE matches.
        if ($bestScore < 50) {
            return null;
        }

        $answer = $locale === 'hi'
            ? (($best['answer_hi'] ?? '') ?: ($best['answer_en'] ?? ''))
            : (($best['answer_en'] ?? '') ?: ($best['answer_hi'] ?? ''));

        $question = $locale === 'hi'
            ? (($best['question_hi'] ?? '') ?: ($best['question_en'] ?? ''))
            : (($best['question_en'] ?? '') ?: ($best['question_hi'] ?? ''));

        $detailedAnswerEn = trim((string) ($best['detailed_answer_en'] ?? ''));
        $detailedAnswerHi = trim((string) ($best['detailed_answer_hi'] ?? ''));
        $detailedAnswer = $locale === 'hi'
            ? ($detailedAnswerHi !== '' ? $detailedAnswerHi : ($detailedAnswerEn !== '' ? $detailedAnswerEn : null))
            : ($detailedAnswerEn !== '' ? $detailedAnswerEn : ($detailedAnswerHi !== '' ? $detailedAnswerHi : null));

        return [
            'question' => (string) $question,
            'answer' => (string) $answer,
            'category' => (string) ($best['category'] ?? 'General Medical'),
            'source' => (string) (($best['source'] ?? 'db_like') . '_like'),
            'source_id' => isset($best['source_id']) ? (int) $best['source_id'] : null,
            'source_table' => isset($best['source_table']) ? (string) $best['source_table'] : null,
            'confidence' => round(min(100, max(60, $bestScore)), 2),
            'detailed_answer_en' => $detailedAnswerEn !== '' ? $detailedAnswerEn : null,
            'detailed_answer_hi' => $detailedAnswerHi !== '' ? $detailedAnswerHi : null,
            'detailed_answer' => $detailedAnswer,
        ];
    }

    /**
     * Create an AI-style fallback answer using Disease + Department when FAQ match is weak.
     *
     * @return array{question:string,answer:string,category:string,source:string,confidence:float}|null
     */
    public function generateFallbackAnswer(string $message, string $locale = 'en'): ?array
    {
        $normalizedMessage = $this->normalize($message);
        if ($normalizedMessage === '') {
            return null;
        }

        $disease = Disease::query()->with('department:id,name_en,name_hi')->get(['id', 'name_en', 'name_hi', 'department_id'])
            ->first(function (Disease $item) use ($normalizedMessage) {
                $nameEn = $this->normalize((string) $item->name_en);
                $nameHi = $this->normalize((string) ($item->name_hi ?? ''));

                return ($nameEn !== '' && str_contains($normalizedMessage, $nameEn))
                    || ($nameHi !== '' && str_contains($normalizedMessage, $nameHi));
            });

        if (! $disease) {
            return null;
        }

        $isHindi = $locale === 'hi';
        $diseaseName = $isHindi ? ($disease->name_hi ?: $disease->name_en) : $disease->name_en;
        $departmentEn = $disease->department?->name_en ?: 'General Medicine';
        $departmentHi = $disease->department?->name_hi ?: 'सामान्य चिकित्सा';
        $departmentName = $isHindi ? $departmentHi : $departmentEn;

        $symptomSignals = ['symptom', 'symptoms', 'sign', 'signs', 'लक्षण', 'संकेत'];
        $treatmentSignals = ['treat', 'treatment', 'cure', 'manage', 'इलाज', 'उपचार', 'दवा', 'नियंत्रण'];

        $intent = 'definition';
        foreach ($treatmentSignals as $signal) {
            if (str_contains($normalizedMessage, $this->normalize($signal))) {
                $intent = 'treatment';
                break;
            }
        }

        if ($intent === 'definition') {
            foreach ($symptomSignals as $signal) {
                if (str_contains($normalizedMessage, $this->normalize($signal))) {
                    $intent = 'symptoms';
                    break;
                }
            }
        }

        if ($isHindi) {
            $question = match ($intent) {
                'symptoms' => "{$diseaseName} के लक्षण क्या हैं?",
                'treatment' => "{$diseaseName} का इलाज कैसे किया जाता है?",
                default => "{$diseaseName} क्या है?",
            };

            $answer = match ($intent) {
                'symptoms' => "{$diseaseName} के लक्षण व्यक्ति और बीमारी की गंभीरता के अनुसार अलग हो सकते हैं। सही मूल्यांकन के लिए {$departmentName} विभाग में विशेषज्ञ से जांच कराना उचित है।",
                'treatment' => "{$diseaseName} का उपचार आमतौर पर {$departmentName} विभाग के विशेषज्ञ द्वारा रोग की अवस्था के आधार पर तय किया जाता है। इसमें दवाएं, जीवनशैली में बदलाव और आवश्यकता अनुसार प्रक्रियाएं शामिल हो सकती हैं।",
                default => "{$diseaseName} एक चिकित्सीय स्थिति है जिसका प्रबंधन {$departmentName} विभाग के अंतर्गत किया जाता है। सही निदान और व्यक्तिगत उपचार योजना के लिए विशेषज्ञ सलाह लें।",
            };
        } else {
            $question = match ($intent) {
                'symptoms' => "What are the symptoms of {$diseaseName}?",
                'treatment' => "How is {$diseaseName} treated?",
                default => "What is {$diseaseName}?",
            };

            $answer = match ($intent) {
                'symptoms' => "Symptoms of {$diseaseName} can vary depending on severity and individual health status. For accurate assessment, consult a specialist in the {$departmentName} department.",
                'treatment' => "Treatment for {$diseaseName} is usually planned by the {$departmentName} department based on stage and symptoms. It may include medicines, lifestyle changes, and procedures when needed.",
                default => "{$diseaseName} is a medical condition managed under the {$departmentName} department. Please seek specialist consultation for diagnosis and a personalized treatment plan.",
            };
        }

        return [
            'question' => $question,
            'answer' => $answer,
            'category' => $departmentEn,
            'source' => 'disease_ai_fallback',
            'confidence' => 78.0,
        ];
    }    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function buildKnowledgeEntries(): Collection
    {
        $dbFaqs = CachedMedicalQuestion::query()
            ->get()
            ->map(function (CachedMedicalQuestion $qa) {
                return [
                    'source_id' => $qa->id,
                    'source_table' => $qa->getTable(),
                    'question_en' => $qa->question_en,
                    'question_hi' => $qa->question_hi,
                    'answer_en' => $qa->answer_en,
                    'answer_hi' => $qa->answer_hi,
                    'detailed_answer_en' => $qa->detailed_answer_en,
                    'detailed_answer_hi' => $qa->detailed_answer_hi,
                    'category' => $qa->category ?? 'General Medical',
                    'keywords' => [],
                    'source' => 'cached_medical_questions',
                ];
            });

        $generalQuestions = GeneralQuestion::query()
            ->get()
            ->map(function (GeneralQuestion $qa) {
                return [
                    'source_id' => $qa->id,
                    'source_table' => $qa->getTable(),
                    'question_en' => $qa->question_en,
                    'question_hi' => $qa->question_hi,
                    'answer_en' => $qa->answer_en,
                    'answer_hi' => $qa->answer_hi,
                    'detailed_answer_en' => $qa->detailed_answer_en,
                    'detailed_answer_hi' => $qa->detailed_answer_hi,
                    'category' => 'General Help',
                    'keywords' => [],
                    'source' => 'general_questions',
                ];
            });

        $faqs = Faq::query()
            ->get()
            ->map(function (Faq $qa) {
                return [
                    'source_id' => $qa->id,
                    'source_table' => $qa->getTable(),
                    'question_en' => $qa->question_en,
                    'question_hi' => $qa->question_hi,
                    'answer_en' => $qa->answer_en,
                    'answer_hi' => $qa->answer_hi,
                    'detailed_answer_en' => null,
                    'detailed_answer_hi' => null,
                    'category' => $qa->category ?? 'FAQ',
                    'keywords' => [],
                    'source' => 'faq',
                ];
            });

        $configured = collect(config('medical_qa.entries', []))
            ->map(function (array $entry) {
                $entry['source'] = 'medical_qa_config';
                $entry['source_id'] = null;
                $entry['source_table'] = null;
                return $entry;
            });

        return $dbFaqs
            ->concat($generalQuestions)
            ->concat($faqs)
            ->concat($configured);
    }

    /**
     * @param  string[]  $keywords
     */
    private function scoreMatch(string $message, string $questionEn, string $questionHi, array $keywords): float
    {
        $score = 0.0;
        $question = trim($questionEn . ' ' . $questionHi);

        if ($question !== '') {
            similar_text($message, $question, $similarityPercent);
            $score = max($score, $similarityPercent);
        }

        if ($questionEn !== '' && str_contains($message, $questionEn)) {
            $score += 20;
        }
        if ($questionHi !== '' && str_contains($message, $questionHi)) {
            $score += 20;
        }

        $messageTokens = $this->tokenize($message);
        $questionTokens = $this->tokenize($question);
        $overlap = count(array_intersect($messageTokens, $questionTokens));
        if (!empty($questionTokens)) {
            $score += ($overlap / max(1, count($questionTokens))) * 30;
        }

        foreach ($keywords as $keyword) {
            if ($keyword !== '' && str_contains($message, $keyword)) {
                $score += 15;
            }
        }

        return min(100, $score);
    }

    /**
     * @return array{question:string,answer:string,category:string,source:string,confidence:float}|null
     */
    private function matchEmergency(string $message, string $locale): ?array
    {
        $emergencyKeywords = config('medical_qa.emergency_keywords', []);
        foreach ($emergencyKeywords as $keyword) {
            if ($keyword !== '' && str_contains($message, $this->normalize((string) $keyword))) {
                $answer = $locale === 'hi'
                    ? (string) config('medical_qa.emergency_reply_hi')
                    : (string) config('medical_qa.emergency_reply_en');

                return [
                    'question' => $locale === 'hi' ? 'What should I do in an emergency?' : 'What should I do in an emergency?',
                    'answer' => $answer,
                    'category' => 'Emergency',
                    'source' => 'emergency_rule',
                    'confidence' => 100.0,
                ];
            }
        }

        return null;
    }

    private function normalize(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        return trim($text);
    }

    /**
     * @return string[]
     */
    private function expandSearchTerms(string $message): array
    {
        $raw = trim($message);
        $normalized = $this->normalize($raw);
        $tokens = $this->tokenize($normalized);

        $terms = collect([$raw, $normalized])
            ->merge($tokens)
            ->filter(fn ($term) => trim((string) $term) !== '')
            ->values();

        $aliases = [
            'hiv' => ['aids', 'hiv aids', 'hiv/aids'],
            'aids' => ['hiv', 'hiv aids', 'hiv/aids'],
            'hiv aids' => ['hiv', 'aids', 'hiv/aids'],
        ];

        foreach ($terms as $term) {
            $key = $this->normalize((string) $term);
            if (isset($aliases[$key])) {
                $terms = $terms->merge($aliases[$key]);
            }
        }

        return $terms
            ->map(fn ($term) => trim((string) $term))
            ->filter(fn ($term) => $term !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return string[]
     */
    private function tokenize(string $text): array
    {
        return array_values(array_filter(explode(' ', $text), fn ($token) => mb_strlen($token) > 2));
    }
}

