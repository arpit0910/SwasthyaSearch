<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consultation extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'uuid',
        'patient_name',
        'doctor_id',
        'status',
        'sdp_offer',
        'sdp_answer',
        'ice_candidates_patient',
        'ice_candidates_doctor',
        'chat_messages',
    ];

    protected $casts = [
        'ice_candidates_patient' => 'array',
        'ice_candidates_doctor' => 'array',
        'chat_messages' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Consultation $consultation) {
            if (blank($consultation->uuid)) {
                $consultation->uuid = static::generateRoomCode();
            }
        });
    }

    public static function generateRoomCode(): string
    {
        do {
            $code = sprintf(
                '%s-%s-%s',
                Str::lower(Str::random(3)),
                Str::lower(Str::random(4)),
                Str::lower(Str::random(3))
            );
        } while (static::query()->where('uuid', $code)->exists());

        return $code;
    }
}
