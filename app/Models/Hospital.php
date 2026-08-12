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
        'landmark',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'country_code_1',
        'country_code_2',
        'phone_1',
        'phone_2',
        'is_verified',
        'accepts_ayushman',
        'accepts_janaadhaar',
        'accepts_cghs',
        'accepts_esic',
        'is_cashless',
        'cashless_schemes_list',
        'cashless_treatment_available',
        'accepts_ayushman_card',
        'accepts_jan_aadhaar',
        'rgahs_approved',
    ];

    protected $appends = ['name', 'display_address', 'map_directions_url'];

    protected $casts = [
        'is_verified' => 'boolean',
        'accepts_ayushman' => 'boolean',
        'accepts_janaadhaar' => 'boolean',
        'accepts_cghs' => 'boolean',
        'accepts_esic' => 'boolean',
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

    public function getDisplayAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
        ], fn($value) => !empty(trim((string)$value)));

        $address = implode(', ', $parts);
        if (!empty($this->pincode)) {
            $address .= ($address !== '' ? ' - ' : '') . $this->pincode;
        }

        return $address;
    }

    public function getMapDirectionsUrlAttribute(): ?string
    {
        $destination = $this->resolveMapDestination();

        if ($destination === null) {
            return null;
        }

        return 'https://www.google.com/maps/dir/?' . http_build_query([
            'api' => 1,
            'destination' => $destination,
        ]);
    }

    public function getEmergencyPhoneAttribute(): ?string
    {
        return $this->phone_1;
    }

    public function setEmergencyPhoneAttribute($value): void
    {
        $this->attributes['phone_1'] = $value;
    }

    public function getEmergencyPhone1Attribute(): ?string
    {
        return $this->phone_1;
    }

    public function getEmergencyPhone2Attribute(): ?string
    {
        return $this->phone_2;
    }

    public function getEmergencyCountryCodeAttribute(): ?string
    {
        return $this->country_code_1;
    }

    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name_en'] = $value['en'] ?? null;
            $this->attributes['name_hi'] = $value['hi'] ?? null;
        }
    }

    public function setAddressAttribute($value): void
    {
        // Prevent using denormalized display address as canonical source.
        $this->attributes['address'] = null;
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

    private function resolveMapDestination(): ?string
    {
        if (!empty($this->latitude) && !empty($this->longitude)) {
            return trim($this->latitude . ',' . $this->longitude);
        }

        $parts = array_filter([
            $this->name_en,
            $this->address_line1,
            $this->address_line2,
            $this->landmark,
            $this->city,
            $this->state,
            $this->pincode,
        ], fn ($value) => filled(trim((string) $value)));

        if (empty($parts)) {
            return null;
        }

        return implode(', ', array_unique($parts));
    }
}
