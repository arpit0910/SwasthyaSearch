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
        Schema::table('doctors', function (Blueprint $table) {
            $table->integer('experience_years')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('doctors')
            ->whereNull('experience_years')
            ->update(['experience_years' => 0]);

        Schema::table('doctors', function (Blueprint $table) {
            $table->integer('experience_years')->nullable(false)->default(0)->change();
        });
    }
};
