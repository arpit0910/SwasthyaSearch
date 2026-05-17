<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_hi');
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS vector');
                $table->vector('symptoms_embedding', 1536)->nullable();
            } else {
                $table->text('symptoms_embedding')->nullable();
            }
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        (DB::getDriverName() === 'pgsql') ? DB::statement('DROP TABLE IF EXISTS diseases CASCADE') : Schema::dropIfExists('diseases');
    }
};
