<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_hi',
        'type',
        'address',
        'city',
        'latitude',
        'longitude',
        'emergency_phone',
        'is_verified',
    ];

    protected $appends = ['name'];

    protected $casts = [
        'is_verified' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_hospital')
            ->withPivot('days_of_week', 'start_time', 'end_time', 'consultation_fee')
            ->withTimestamps();
    }

    public function getNameAttribute(): array
    {
        return [
            'en' => $this->name_en,
            'hi' => $this->name_hi,
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
