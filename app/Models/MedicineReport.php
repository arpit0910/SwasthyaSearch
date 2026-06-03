<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'medicine_name',
        'report_type',
        'user_message',
        'corrected_information',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'status',
        'admin_notes',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
