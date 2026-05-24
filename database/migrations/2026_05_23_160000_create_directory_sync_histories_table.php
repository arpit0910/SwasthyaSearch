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
        Schema::create('directory_sync_histories', function (Blueprint $table) {
            $table->id();
            $table->string('city', 100)->index();
            $table->string('status', 20)->index();
            $table->boolean('force_fallback')->default(false);

            $table->unsignedInteger('fetched_hospitals')->default(0);
            $table->unsignedInteger('fetched_doctors')->default(0);
            $table->unsignedInteger('fetched_blood_banks')->default(0);

            $table->unsignedInteger('db_hospitals_before')->default(0);
            $table->unsignedInteger('db_doctors_before')->default(0);
            $table->unsignedInteger('db_blood_banks_before')->default(0);
            $table->unsignedInteger('db_hospitals_after')->default(0);
            $table->unsignedInteger('db_doctors_after')->default(0);
            $table->unsignedInteger('db_blood_banks_after')->default(0);

            $table->integer('delta_hospitals')->default(0);
            $table->integer('delta_doctors')->default(0);
            $table->integer('delta_blood_banks')->default(0);

            $table->text('message')->nullable();
            $table->json('report_payload')->nullable();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directory_sync_histories');
    }
};

