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
        Schema::table('hospitals', function (Blueprint $table) {
            $table->string('landmark')->nullable()->after('address_line2');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->string('landmark')->nullable()->after('address_line2');
        });

        Schema::table('blood_banks', function (Blueprint $table) {
            $table->string('landmark')->nullable()->after('address_hi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn('landmark');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('landmark');
        });

        Schema::table('blood_banks', function (Blueprint $table) {
            $table->dropColumn('landmark');
        });
    }
};
