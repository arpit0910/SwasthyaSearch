<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomTestSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'age',
        'gender',
        'symptom_text',
        'selected_symptoms',
        'likely_conditions',
        'next_symptoms',
        'recommended_department_id',
        'top_disease_id',
        'top_score',
        'locale',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'selected_symptoms' => 'array',
        'likely_conditions' => 'array',
        'next_symptoms' => 'array',
        'top_score' => 'decimal:2',
    ];

    public function recommendedDepartment()
    {
        return $this->belongsTo(Department::class, 'recommended_department_id');
    }

    public function topDisease()
    {
        return $this->belongsTo(Disease::class, 'top_disease_id');
    }
}
