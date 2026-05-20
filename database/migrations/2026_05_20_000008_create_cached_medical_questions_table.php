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
        Schema::create('cached_medical_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_en');
            $table->string('question_hi');
            $table->text('answer_en');
            $table->text('answer_hi');
            $table->string('category')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('question_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_medical_questions');
    }
};

