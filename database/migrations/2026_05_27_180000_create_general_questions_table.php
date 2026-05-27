<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_en');
            $table->string('question_hi');
            $table->text('answer_en');
            $table->text('answer_hi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_questions');
    }
};

