<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_en',
        'title_hi',
        'slug',
        'category',
        'description_en',
        'description_hi',
        'intro_en',
        'intro_hi',
        'questions_json',
        'result_ranges_json',
        'disclaimer_en',
        'disclaimer_hi',
        'meta_title_en',
        'meta_title_hi',
        'meta_description_en',
        'meta_description_hi',
        'is_published',
    ];

    protected $casts = [
        'questions_json' => 'array',
        'result_ranges_json' => 'array',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $quiz) {
            if (blank($quiz->slug) && filled($quiz->title_en)) {
                $quiz->slug = Str::slug($quiz->title_en);
            }
        });
    }

    public function getTitleAttribute(): array
    {
        return [
            'en' => $this->title_en,
            'hi' => $this->title_hi,
        ];
    }

    public function getDescriptionAttribute(): array
    {
        return [
            'en' => $this->description_en,
            'hi' => $this->description_hi,
        ];
    }

    public function getTranslation(string $field, string $locale): ?string
    {
        return $this->{$field . '_' . $locale} ?? null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
