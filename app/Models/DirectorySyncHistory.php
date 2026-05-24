<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectorySyncHistory extends Model
{
    protected $fillable = [
        'city',
        'status',
        'force_fallback',
        'fetched_hospitals',
        'fetched_doctors',
        'fetched_blood_banks',
        'db_hospitals_before',
        'db_doctors_before',
        'db_blood_banks_before',
        'db_hospitals_after',
        'db_doctors_after',
        'db_blood_banks_after',
        'delta_hospitals',
        'delta_doctors',
        'delta_blood_banks',
        'message',
        'report_payload',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'force_fallback' => 'boolean',
        'report_payload' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}

