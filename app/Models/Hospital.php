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
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'emergency_phone',
        'is_verified',
        'accepts_ayushman',
        'accepts_janaadhaar',
        'accepts_cghs',
        'is_cashless',
        'cashless_schemes_list',
        'cashless_treatment_available',
        'accepts_ayushman_card',
        'accepts_jan_aadhaar',
        'rgahs_approved',
    ];

    protected $appends = ['name'];

    protected $casts = [
        'is_verified' => 'boolean',
        'accepts_ayushman' => 'boolean',
        'accepts_janaadhaar' => 'boolean',
        'accepts_cghs' => 'boolean',
        'is_cashless' => 'boolean',
        'cashless_schemes_list' => 'array',
        'cashless_treatment_available' => 'boolean',
        'accepts_ayushman_card' => 'boolean',
        'accepts_jan_aadhaar' => 'boolean',
        'rgahs_approved' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_hospital')
            ->withPivot('days_of_week', 'start_time', 'end_time', 'consultation_fee')
            ->withTimestamps();
    }

    public function scopeCloseTo($query, $latitude, $longitude, $radius = 50)
    {
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        $query->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($radius) {
            $query->whereRaw("{$haversine} <= ?", [$latitude, $longitude, $latitude, $radius]);
        }

        return $query->orderBy('distance');
    }

    public function scopeNearest($query, $latitude, $longitude)
    {
        return $this->scopeCloseTo($query, $latitude, $longitude);
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
