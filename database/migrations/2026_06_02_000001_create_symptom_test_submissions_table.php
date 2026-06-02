<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('symptom_test_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 100)->nullable()->index();
            $table->unsignedTinyInteger('age')->nullable()->index();
            $table->string('gender', 20)->nullable()->index();
            $table->text('symptom_text')->nullable();
            $table->json('selected_symptoms')->nullable();
            $table->json('likely_conditions')->nullable();
            $table->json('next_symptoms')->nullable();
            $table->foreignId('recommended_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('top_disease_id')->nullable()->constrained('diseases')->nullOnDelete();
            $table->decimal('top_score', 6, 2)->nullable();
            $table->string('locale', 10)->nullable()->index();
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('symptom_test_submissions');
    }
};
