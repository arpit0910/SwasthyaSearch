<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotFailedQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'city',
        'locale',
        'failure_type',
        'user_message',
        'error_message',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}

