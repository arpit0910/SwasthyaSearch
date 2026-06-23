<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'doctor' or 'hospital'
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->json('details');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_submissions');
    }
};
