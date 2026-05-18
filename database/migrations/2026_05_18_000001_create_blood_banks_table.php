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
        Schema::create('blood_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_hi');
            $table->string('address_en')->nullable();
            $table->string('address_hi')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('country_code')->default('+91')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_country_code')->default('+91')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->string('source_name')->nullable();
            $table->string('source_url', 1024)->nullable();
            $table->unsignedTinyInteger('source_confidence_score')->default(0);
            $table->string('source_verification_status')->default('needs_manual_review');
            $table->timestamp('source_last_seen_at')->nullable();
            $table->json('source_metadata')->nullable();
            $table->boolean('is_24_7')->default(true);
            $table->boolean('is_government')->default(false);
            $table->boolean('component_facility')->default(true);
            $table->boolean('apheresis_facility')->default(false);
            $table->json('available_blood_groups')->nullable();
            $table->timestamp('last_updated_stock_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['source_verification_status', 'source_confidence_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_banks');
    }
};
