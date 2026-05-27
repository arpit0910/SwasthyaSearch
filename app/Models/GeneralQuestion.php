<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_en',
        'question_hi',
        'answer_en',
        'answer_hi',
        'detailed_answer_en',
        'detailed_answer_hi',
    ];

    protected $appends = ['question', 'answer'];

    public function getQuestionAttribute(): array
    {
        return [
            'en' => $this->question_en,
            'hi' => $this->question_hi,
        ];
    }

    public function getAnswerAttribute(): array
    {
        return [
            'en' => $this->answer_en,
            'hi' => $this->answer_hi,
        ];
    }

    public function getTranslation(string $field, string $locale): ?string
    {
        return $this->{$field . '_' . $locale} ?? null;
    }
}
