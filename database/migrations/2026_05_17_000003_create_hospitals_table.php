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
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_hi');
            $table->string('type')->default('Clinic');
            $table->string('address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('emergency_country_code')->default('+91')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->boolean('accepts_ayushman')->default(false);
            $table->boolean('accepts_janaadhaar')->default(false);
            $table->boolean('accepts_cghs')->default(false);
            $table->boolean('is_cashless')->default(false);
            $table->boolean('cashless_treatment_available')->default(false);
            $table->boolean('accepts_ayushman_card')->default(false);
            $table->boolean('accepts_jan_aadhaar')->default(false);
            $table->boolean('rgahs_approved')->default(false);
            $table->json('cashless_schemes_list')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
