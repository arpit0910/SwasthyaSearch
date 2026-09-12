<?php

namespace App\Support;

use App\Models\HomeRemedy;

class RemedyDesign
{
    public static function item(HomeRemedy $remedy): array
    {
        $locale = app()->getLocale();
        $fields = ['title', 'problem_name', 'short_description', 'description', 'ingredients', 'preparation', 'steps', 'usage_instructions', 'dosage_or_quantity', 'frequency', 'recommended_duration', 'traditional_benefit', 'how_it_may_help', 'evidence_summary', 'suitable_for', 'not_suitable_for', 'child_warning', 'pregnancy_warning', 'elderly_warning', 'medical_condition_warning', 'medicine_interactions', 'possible_side_effects', 'red_flags', 'when_to_see_doctor', 'medical_disclaimer'];
        $item = collect($fields)->mapWithKeys(fn ($field) => [$field => trim(strip_tags($remedy->getTranslation($field, $locale) ?: ''))])->all();

        return array_merge($item, [
            'id' => $remedy->id,
            'url' => route('nani-dadi.show', $remedy->slug),
            'evidence' => $remedy->evidence_level,
            'category' => $remedy->category?->getTranslation('name', $locale),
            'categoryUrl' => $remedy->category ? route('nani-dadi.category', $remedy->category->slug) : null,
            'ingredientItems' => $remedy->ingredients->map(fn ($ingredient) => [
                'name' => $ingredient->getTranslation('name', $locale),
                'quantity' => $ingredient->pivot?->quantity,
                'url' => route('nani-dadi.ingredient', $ingredient->slug),
            ])->values()->all(),
            'reviewedAt' => $remedy->reviewed_at?->toDateString(),
            'reviewStatus' => $remedy->medical_review_status,
            'references' => $remedy->references,
            'department' => $remedy->doctor_speciality_id,
        ]);
    }
}
