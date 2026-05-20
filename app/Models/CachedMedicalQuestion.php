<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CachedMedicalQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_en',
        'question_hi',
        'answer_en',
        'answer_hi',
        'category',
    ];
}

