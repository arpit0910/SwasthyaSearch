<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_remedy_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en'); $table->string('name_hi'); $table->string('slug')->unique();
            $table->text('description_en')->nullable(); $table->text('description_hi')->nullable();
            $table->string('icon')->nullable(); $table->string('image')->nullable();
            $table->string('meta_title_en')->nullable(); $table->string('meta_title_hi')->nullable();
            $table->text('meta_description_en')->nullable(); $table->text('meta_description_hi')->nullable();
            $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_remedy_ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name_en'); $table->string('name_hi'); $table->string('slug')->unique();
            $table->text('description_en')->nullable(); $table->text('description_hi')->nullable();
            $table->text('traditional_uses_en')->nullable(); $table->text('traditional_uses_hi')->nullable();
            $table->text('precautions_en')->nullable(); $table->text('precautions_hi')->nullable();
            $table->string('image')->nullable(); $table->string('meta_title_en')->nullable(); $table->string('meta_title_hi')->nullable();
            $table->text('meta_description_en')->nullable(); $table->text('meta_description_hi')->nullable();
            $table->boolean('is_active')->default(true); $table->timestamps();
        });

        Schema::create('home_remedies', function (Blueprint $table) {
            $table->id();
            $textFields = ['title','problem_name','short_description','description','ingredients','preparation','steps','usage_instructions','dosage_or_quantity','frequency','recommended_duration','traditional_benefit','how_it_may_help','evidence_summary','suitable_for','not_suitable_for','child_warning','pregnancy_warning','elderly_warning','medical_condition_warning','medicine_interactions','possible_side_effects','red_flags','when_to_see_doctor','medical_disclaimer','meta_description'];
            foreach ($textFields as $field) { $table->text($field.'_en')->nullable(); $table->text($field.'_hi')->nullable(); }
            $table->string('slug')->unique();
            $table->enum('evidence_level', ['traditional','limited','supportive','moderate','strong','not_recommended'])->default('traditional')->index();
            $table->foreignId('category_id')->constrained('home_remedy_categories')->restrictOnDelete()->index();
            $table->foreignId('doctor_speciality_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('featured_image')->nullable(); $table->string('thumbnail')->nullable();
            $table->text('search_keywords_en')->nullable(); $table->text('search_keywords_hi')->nullable(); $table->text('search_aliases')->nullable(); $table->text('references')->nullable(); $table->text('source_notes')->nullable();
            $table->string('meta_title_en')->nullable(); $table->string('meta_title_hi')->nullable();
            $table->boolean('is_featured')->default(false)->index(); $table->boolean('is_published')->default(false)->index();
            $table->enum('medical_review_status', ['draft','content_reviewed','medical_review_pending','medical_reviewed','published','rejected'])->default('draft')->index();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete(); $table->timestamp('reviewed_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0); $table->timestamps(); $table->softDeletes();
            $table->index(['is_published','created_at']);
        });

        Schema::create('home_remedy_ingredient_map', function (Blueprint $table) {
            $table->foreignId('home_remedy_id')->constrained('home_remedies')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('home_remedy_ingredients')->cascadeOnDelete();
            $table->string('quantity')->nullable(); $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['home_remedy_id','ingredient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_remedy_ingredient_map'); Schema::dropIfExists('home_remedies');
        Schema::dropIfExists('home_remedy_ingredients'); Schema::dropIfExists('home_remedy_categories');
    }
};
