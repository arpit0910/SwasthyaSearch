<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('generic_name')->nullable()->index();
            $table->json('brand_names_json')->nullable();
            $table->string('composition')->nullable();
            $table->string('strength')->nullable();
            $table->string('medicine_type')->nullable();
            $table->string('category')->nullable()->index();
            $table->boolean('prescription_required')->default(false);
            $table->text('purpose_en')->nullable();
            $table->text('purpose_hi')->nullable();
            $table->longText('overview_en')->nullable();
            $table->longText('overview_hi')->nullable();
            $table->longText('uses_en')->nullable();
            $table->longText('uses_hi')->nullable();
            $table->longText('benefits_en')->nullable();
            $table->longText('benefits_hi')->nullable();
            $table->longText('dosage_information_en')->nullable();
            $table->longText('dosage_information_hi')->nullable();
            $table->longText('mechanism_en')->nullable();
            $table->longText('mechanism_hi')->nullable();
            $table->longText('common_side_effects_en')->nullable();
            $table->longText('common_side_effects_hi')->nullable();
            $table->longText('serious_side_effects_en')->nullable();
            $table->longText('serious_side_effects_hi')->nullable();
            $table->longText('drug_interactions_en')->nullable();
            $table->longText('drug_interactions_hi')->nullable();
            $table->longText('food_interactions_en')->nullable();
            $table->longText('food_interactions_hi')->nullable();
            $table->longText('alcohol_warning_en')->nullable();
            $table->longText('alcohol_warning_hi')->nullable();
            $table->longText('pregnancy_warning_en')->nullable();
            $table->longText('pregnancy_warning_hi')->nullable();
            $table->longText('breastfeeding_warning_en')->nullable();
            $table->longText('breastfeeding_warning_hi')->nullable();
            $table->longText('kidney_warning_en')->nullable();
            $table->longText('kidney_warning_hi')->nullable();
            $table->longText('liver_warning_en')->nullable();
            $table->longText('liver_warning_hi')->nullable();
            $table->longText('driving_warning_en')->nullable();
            $table->longText('driving_warning_hi')->nullable();
            $table->longText('allergy_warning_en')->nullable();
            $table->longText('allergy_warning_hi')->nullable();
            $table->longText('precautions_en')->nullable();
            $table->longText('precautions_hi')->nullable();
            $table->longText('contraindications_en')->nullable();
            $table->longText('contraindications_hi')->nullable();
            $table->longText('avoid_if_en')->nullable();
            $table->longText('avoid_if_hi')->nullable();
            $table->longText('missed_dose_en')->nullable();
            $table->longText('missed_dose_hi')->nullable();
            $table->longText('overdose_en')->nullable();
            $table->longText('overdose_hi')->nullable();
            $table->longText('storage_en')->nullable();
            $table->longText('storage_hi')->nullable();
            $table->longText('expert_advice_en')->nullable();
            $table->longText('expert_advice_hi')->nullable();
            $table->longText('when_to_contact_doctor_en')->nullable();
            $table->longText('when_to_contact_doctor_hi')->nullable();
            $table->json('faqs_json')->nullable();
            $table->json('source_references_json')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_hi')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_hi')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('last_reviewed_at')->nullable();
            $table->boolean('ai_generated')->default(false);
            $table->boolean('medically_reviewed')->default(false);
            $table->string('review_status')->default('draft')->index();
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
