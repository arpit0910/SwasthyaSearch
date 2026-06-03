<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $quizzes = Quiz::query()->published()->orderBy('title_en')->paginate(12);

        return view('quizzes.index', compact('quizzes', 'locale'));
    }

    public function show(Quiz $quiz)
    {
        abort_unless($quiz->is_published, 404);

        $locale = app()->getLocale();

        return view('quizzes.show', compact('quiz', 'locale'));
    }

    public function result(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->is_published, 404);

        $questions = $quiz->questions_json ?? [];
        $answers = $request->input('answers', []);
        $score = 0;

        foreach ($questions as $index => $question) {
            $selected = isset($answers[$index]) ? (int) $answers[$index] : null;
            $options = $question['options'] ?? [];
            if ($selected !== null && isset($options[$selected])) {
                $score += (int) ($options[$selected]['score'] ?? 0);
            }
        }

        $result = collect($quiz->result_ranges_json ?? [])->first(function ($range) use ($score) {
            return $score >= (int) ($range['min'] ?? 0) && $score <= (int) ($range['max'] ?? 0);
        });

        return view('quizzes.result', [
            'quiz' => $quiz,
            'score' => $score,
            'result' => $result,
            'locale' => app()->getLocale(),
        ]);
    }
}
