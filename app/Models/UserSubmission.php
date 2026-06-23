<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'name',
        'phone',
        'city',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
