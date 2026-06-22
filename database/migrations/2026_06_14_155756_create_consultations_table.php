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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->string('patient_name');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('status')->default('pending');
            $table->mediumText('sdp_offer')->nullable();
            $table->mediumText('sdp_answer')->nullable();
            $table->longText('ice_candidates_patient')->nullable();
            $table->longText('ice_candidates_doctor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
