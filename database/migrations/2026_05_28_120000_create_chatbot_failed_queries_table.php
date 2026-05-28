<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_failed_queries', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->nullable()->index();
            $table->string('city', 120)->nullable()->index();
            $table->string('locale', 8)->nullable()->index();
            $table->string('failure_type', 64)->default('unknown')->index();
            $table->text('user_message')->nullable();
            $table->text('error_message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_failed_queries');
    }
};

