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

            // Scheme / Independent Clinic Flags
            $table->boolean('cashless_treatment_available')->default(false);
            $table->boolean('accepts_ayushman_card')->default(false);
            $table->boolean('accepts_jan_aadhaar')->default(false);
            $table->boolean('rgahs_approved')->default(false);

            // Personal Details
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
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
