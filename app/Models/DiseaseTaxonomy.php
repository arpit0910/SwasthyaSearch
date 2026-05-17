<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class DiseaseTaxonomy extends Model
{
    use HasFactory, Searchable;

    protected $table = 'disease_taxonomy';

    protected $fillable = ['disease_name_en', 'disease_name_hi', 'specialty_id'];

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['specialty_en'] = $this->specialty?->name_en;
        $array['specialty_hi'] = $this->specialty?->name_hi;
        return $array;
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
