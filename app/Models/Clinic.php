<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_hi',
        'address_en',
        'address_hi',
        'lat',
        'lng',
        'direct_contact_number',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_clinic')->withPivot('days_of_week', 'start_time', 'end_time')->withTimestamps();
    }
}
