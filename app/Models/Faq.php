<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_en',
        'question_hi',
        'answer_en',
        'answer_hi',
        'category',
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

    public function getTranslations(string $field): array
    {
        return [
            'en' => $this->{$field . '_en'} ?? null,
            'hi' => $this->{$field . '_hi'} ?? null,
        ];
    }
}
