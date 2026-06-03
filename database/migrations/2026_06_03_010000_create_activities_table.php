<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_hi')->nullable();
            $table->string('slug')->unique();
            $table->string('category', 50)->index();
            $table->text('description_en')->nullable();
            $table->text('description_hi')->nullable();
            $table->string('route_name');
            $table->string('tone', 30)->default('teal');
            $table->string('cta_en', 50)->default('Open');
            $table->string('cta_hi', 50)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
