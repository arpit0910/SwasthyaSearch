<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('articles')) {
            return;
        }

        DB::table('articles')
            ->where('author_name', 'Swasthya Editorial')
            ->update(['author_name' => 'Arogio Editorial']);

        Schema::table('articles', function (Blueprint $table) {
            $table->string('author_name')->default('Arogio Editorial')->change();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('articles')) {
            return;
        }

        DB::table('articles')
            ->where('author_name', 'Arogio Editorial')
            ->update(['author_name' => 'Swasthya Editorial']);

        Schema::table('articles', function (Blueprint $table) {
            $table->string('author_name')->default('Swasthya Editorial')->change();
        });
    }
};
