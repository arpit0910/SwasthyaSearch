<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_en',
        'title_hi',
        'excerpt_en',
        'excerpt_hi',
        'content_en',
        'content_hi',
        'category',
        'author_name',
        'is_published',
    ];

    protected $appends = ['title', 'excerpt', 'content'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function getTitleAttribute(): array
    {
        return [
            'en' => $this->title_en,
            'hi' => $this->title_hi,
        ];
    }

    public function getExcerptAttribute(): array
    {
        return [
            'en' => $this->excerpt_en,
            'hi' => $this->excerpt_hi,
        ];
    }

    public function getContentAttribute(): array
    {
        return [
            'en' => $this->content_en,
            'hi' => $this->content_hi,
        ];
    }

    public function setTitleAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['title_en'] = $value['en'] ?? null;
            $this->attributes['title_hi'] = $value['hi'] ?? null;
        }
    }

    public function setExcerptAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['excerpt_en'] = $value['en'] ?? null;
            $this->attributes['excerpt_hi'] = $value['hi'] ?? null;
        }
    }

    public function setContentAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['content_en'] = $value['en'] ?? null;
            $this->attributes['content_hi'] = $value['hi'] ?? null;
        }
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
