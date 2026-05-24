<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_hi',
        'address_en',
        'address_hi',
        'landmark',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'country_code',
        'phone',
        'emergency_country_code',
        'emergency_phone',
        'email',
        'website',
        'is_verified',
        'source_name',
        'source_url',
        'source_confidence_score',
        'source_verification',
        'source_last_seen_at',
        'source_metadata',
        'is_24_7',
        'is_government',
        'component_facility',
        'apheresis_facility',
        'available_blood_groups',
        'last_updated_stock_at',
    ];

    protected $appends = ['name', 'address'];

    protected $casts = [
        'is_verified' => 'boolean',
        'source_confidence_score' => 'integer',
        'source_last_seen_at' => 'datetime',
        'source_metadata' => 'array',
        'is_24_7' => 'boolean',
        'is_government' => 'boolean',
        'component_facility' => 'boolean',
        'apheresis_facility' => 'boolean',
        'available_blood_groups' => 'array',
        'last_updated_stock_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function getNameAttribute(): array
    {
        return [
            'en' => $this->name_en,
            'hi' => $this->name_hi,
        ];
    }

    public function getAddressAttribute(): array
    {
        return [
            'en' => $this->address_en ?: '',
            'hi' => $this->address_hi ?: '',
        ];
    }

    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name_en'] = $value['en'] ?? null;
            $this->attributes['name_hi'] = $value['hi'] ?? null;
        }
    }

    public function setAddressAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['address_en'] = $value['en'] ?? null;
            $this->attributes['address_hi'] = $value['hi'] ?? null;
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
}
