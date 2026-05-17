<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $session_token
 * @property array|null $messages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChatSession firstOrCreate(array $attributes = [], array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ChatSession where($column, $operator = null, $value = null, $boolean = 'and')
 */
class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array',
    ];
}
