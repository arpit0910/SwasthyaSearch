<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_hi',
        'description_en',
        'description_hi',
        'is_active',
    ];

    protected $appends = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function diseases()
    {
        return $this->hasMany(Disease::class);
    }

    public function getNameAttribute(): array
    {
        return [
            'en' => $this->name_en,
            'hi' => $this->name_hi,
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

    public function getTranslations(string $field): array
    {
        return [
            'en' => $this->{$field . '_en'} ?? null,
            'hi' => $this->{$field . '_hi'} ?? null,
        ];
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'department_doctor')->withTimestamps();
    }
}
