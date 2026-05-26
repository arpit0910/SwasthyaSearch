<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Disease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_hi',
        'symptoms_embedding',
        'department_id',
    ];

    protected $appends = ['name'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'disease_symptom')->withTimestamps();
    }

    public function getNameAttribute(): array
    {
        return [
            'en' => $this->name_en,
            'hi' => $this->name_hi,
        ];
    }

    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name_en'] = $value['en'] ?? null;
            $this->attributes['name_hi'] = $value['hi'] ?? null;
        }
    }

    public function getTranslation(string $field, string $locale): ?string
    {
        return $this->{$field . '_' . $locale} ?? null;
    }

    public function getTranslations(string $field): array
    {
        return [
            'en' => $this->{$field . '_en'} ?? null,
            'hi' => $this->{$field . '_hi'} ?? null,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($disease) {
            if (empty($disease->symptoms_embedding) && method_exists(Str::class, 'of')) {
                try {
                    $stringObj = Str::of($disease->name_en . ' ' . $disease->name_hi);
                    if (method_exists($stringObj, 'toEmbeddings')) {
                        $disease->symptoms_embedding = $stringObj->toEmbeddings();
                    } else {
                        $disease->symptoms_embedding = json_encode(array_fill(0, 1536, 0.01));
                    }
                } catch (Exception $e) {
                    $disease->symptoms_embedding = json_encode(array_fill(0, 1536, 0.01));
                }
            }
        });
    }

    public function scopeWhereVectorSimilarTo($query, $column, $embedding)
    {
        if (DB::getDriverName() === 'pgsql') {
            return $query->orderByRaw("$column <=> ?", [$embedding]);
        }
        return $query;
    }
}
