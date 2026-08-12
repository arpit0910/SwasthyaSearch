<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->boolean('is_verified')->nullable()->default(null)->change();
            $table->string('country_code_1')->nullable()->default(null)->change();
        });

        Schema::table('doctor_hospital', function (Blueprint $table) {
            $table->string('external_link_id')->nullable()->after('hospital_id');
            $table->string('role')->nullable()->after('external_link_id');
            $table->string('consultation_mode')->nullable()->after('role');
            $table->text('availability')->nullable()->after('consultation_mode');
        });
    }

    public function down(): void
    {
        Schema::table('doctor_hospital', function (Blueprint $table) {
            $table->dropColumn(['external_link_id', 'role', 'consultation_mode', 'availability']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->boolean('is_verified')->nullable(false)->default(true)->change();
            $table->string('country_code_1')->nullable()->default('+91')->change();
        });
    }
};
