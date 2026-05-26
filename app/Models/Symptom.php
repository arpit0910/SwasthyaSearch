<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_hi',
    ];

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_symptom')->withTimestamps();
    }
}
