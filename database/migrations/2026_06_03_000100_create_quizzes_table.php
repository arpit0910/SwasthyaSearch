<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_hi');
            $table->string('slug')->unique();
            $table->string('category')->nullable()->index();
            $table->text('description_en')->nullable();
            $table->text('description_hi')->nullable();
            $table->longText('intro_en')->nullable();
            $table->longText('intro_hi')->nullable();
            $table->json('questions_json')->nullable();
            $table->json('result_ranges_json')->nullable();
            $table->text('disclaimer_en')->nullable();
            $table->text('disclaimer_hi')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_hi')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_hi')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
