<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    public const REVIEW_STATUSES = [
        'draft',
        'ai_generated_draft',
        'needs_review',
        'reviewed',
        'published',
        'rejected',
        'archived',
    ];

    protected $fillable = [
        'name',
        'slug',
        'generic_name',
        'brand_names_json',
        'composition',
        'strength',
        'medicine_type',
        'category',
        'prescription_required',
        'purpose_en',
        'purpose_hi',
        'overview_en',
        'overview_hi',
        'uses_en',
        'uses_hi',
        'benefits_en',
        'benefits_hi',
        'dosage_information_en',
        'dosage_information_hi',
        'mechanism_en',
        'mechanism_hi',
        'common_side_effects_en',
        'common_side_effects_hi',
        'serious_side_effects_en',
        'serious_side_effects_hi',
        'drug_interactions_en',
        'drug_interactions_hi',
        'food_interactions_en',
        'food_interactions_hi',
        'alcohol_warning_en',
        'alcohol_warning_hi',
        'pregnancy_warning_en',
        'pregnancy_warning_hi',
        'breastfeeding_warning_en',
        'breastfeeding_warning_hi',
        'kidney_warning_en',
        'kidney_warning_hi',
        'liver_warning_en',
        'liver_warning_hi',
        'driving_warning_en',
        'driving_warning_hi',
        'allergy_warning_en',
        'allergy_warning_hi',
        'precautions_en',
        'precautions_hi',
        'contraindications_en',
        'contraindications_hi',
        'avoid_if_en',
        'avoid_if_hi',
        'missed_dose_en',
        'missed_dose_hi',
        'overdose_en',
        'overdose_hi',
        'storage_en',
        'storage_hi',
        'expert_advice_en',
        'expert_advice_hi',
        'when_to_contact_doctor_en',
        'when_to_contact_doctor_hi',
        'faqs_json',
        'source_references_json',
        'meta_title_en',
        'meta_title_hi',
        'meta_description_en',
        'meta_description_hi',
        'reviewed_by',
        'last_reviewed_at',
        'ai_generated',
        'medically_reviewed',
        'review_status',
        'is_published',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'brand_names_json' => 'array',
        'faqs_json' => 'array',
        'source_references_json' => 'array',
        'prescription_required' => 'boolean',
        'ai_generated' => 'boolean',
        'medically_reviewed' => 'boolean',
        'is_published' => 'boolean',
        'last_reviewed_at' => 'datetime',
    ];

    protected $appends = [
        'brand_names',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $medicine) {
            if (blank($medicine->slug) && filled($medicine->name)) {
                $medicine->slug = Str::slug($medicine->name);
            }

            if ($medicine->is_published && $medicine->review_status === 'reviewed') {
                $medicine->review_status = 'published';
            }
        });
    }

    public function reports()
    {
        return $this->hasMany(MedicineReport::class);
    }

    public function getBrandNamesAttribute(): array
    {
        $value = $this->brand_names_json;

        if (is_array($value)) {
            return array_values(array_filter($value, fn ($brand) => is_string($brand) && trim($brand) !== ''));
        }

        return [];
    }

    public function getTranslation(string $field, string $locale): ?string
    {
        return $this->{$field . '_' . $locale} ?? null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereIn('review_status', ['reviewed', 'published']);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $search) use ($term) {
            $search->where('name', 'like', "%{$term}%")
                ->orWhere('generic_name', 'like', "%{$term}%")
                ->orWhere('composition', 'like', "%{$term}%")
                ->orWhere('category', 'like', "%{$term}%")
                ->orWhere('brand_names_json', 'like', "%{$term}%");
        });
    }
}
