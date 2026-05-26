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
            $table->renameColumn('emergency_phone', 'emergency_phone_1');
            $table->string('emergency_phone_2')->nullable()->after('emergency_phone_1');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->renameColumn('phone', 'phone_1');
            $table->string('phone_2')->nullable()->after('phone_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn('emergency_phone_2');
            $table->renameColumn('emergency_phone_1', 'emergency_phone');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('phone_2');
            $table->renameColumn('phone_1', 'phone');
        });
    }
};
