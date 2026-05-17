<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            // Core Details
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
            $table->string('registration_number')->nullable();
            $table->string('medical_council')->nullable();
            $table->json('education_degrees')->nullable();
            $table->integer('experience_years')->default(0);
            $table->text('about_en')->nullable();
            $table->text('about_hi')->nullable();
            $table->boolean('is_verified')->default(true);

            // Personal Details
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->json('languages_spoken')->nullable();

            // Professional Details
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->text('specialization_summary')->nullable();
            $table->json('awards_recognitions')->nullable();
            $table->json('membership_fellowships')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
