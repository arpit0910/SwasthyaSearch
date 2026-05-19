<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Doctor extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'first_name',
        'last_name',
        'department_id',
        'registration_number',
        'medical_council',
        'education_degrees',
        'experience_years',
        'about_en',
        'about_hi',
        'is_verified',
        'email',
        'country_code',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'website',
        'date_of_birth',
        'gender',
        'languages_spoken',
        'consultation_fee',
        'specialization_summary',
        'awards_recognitions',
        'membership_fellowships',
        'cashless_treatment_available',
        'accepts_ayushman_card',
        'accepts_jan_aadhaar',
        'rgahs_approved',
    ];

    protected $appends = ['about'];

    protected $casts = [
        'education_degrees' => 'array',
        'languages_spoken' => 'array',
        'awards_recognitions' => 'array',
        'membership_fellowships' => 'array',
        'is_verified' => 'boolean',
        'experience_years' => 'integer',
        'consultation_fee' => 'decimal:2',
        'date_of_birth' => 'date',
        'cashless_treatment_available' => 'boolean',
        'accepts_ayushman_card' => 'boolean',
        'accepts_jan_aadhaar' => 'boolean',
        'rgahs_approved' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_doctor')->withTimestamps();
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'doctor_hospital')
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

    public function getAboutAttribute(): array
    {
        return [
            'en' => $this->about_en,
            'hi' => $this->about_hi,
        ];
    }

    public function setAboutAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['about_en'] = $value['en'] ?? null;
            $this->attributes['about_hi'] = $value['hi'] ?? null;
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
