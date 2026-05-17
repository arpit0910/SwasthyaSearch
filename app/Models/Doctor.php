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
        'phone',
        'website',
        'date_of_birth',
        'gender',
        'languages_spoken',
        'consultation_fee',
        'specialization_summary',
        'awards_recognitions',
        'membership_fellowships',
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

    public function getAboutAttribute(): array
    {
        return [
            'en' => $this->about_en,
            'hi' => $this->about_hi,
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
