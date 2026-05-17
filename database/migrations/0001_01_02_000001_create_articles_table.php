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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_hi');
            $table->text('excerpt_en');
            $table->text('excerpt_hi');
            $table->longText('content_en');
            $table->longText('content_hi');
            $table->string('category')->default('Wellness');
            $table->string('author_name')->default('Swasthya Editorial');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
