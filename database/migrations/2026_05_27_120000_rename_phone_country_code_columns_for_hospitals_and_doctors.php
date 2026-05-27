<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('doctors', 'country_code') && !Schema::hasColumn('doctors', 'country_code_1')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->renameColumn('country_code', 'country_code_1');
            });
        }

        if (!Schema::hasColumn('doctors', 'country_code_2')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->string('country_code_2')->nullable()->after('country_code_1');
            });
        }

        if (Schema::hasColumn('hospitals', 'emergency_country_code') && !Schema::hasColumn('hospitals', 'country_code_1')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->renameColumn('emergency_country_code', 'country_code_1');
            });
        }

        if (!Schema::hasColumn('hospitals', 'country_code_2')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->string('country_code_2')->nullable()->after('country_code_1');
            });
        }

        if (Schema::hasColumn('hospitals', 'emergency_phone_1') && !Schema::hasColumn('hospitals', 'phone_1')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->renameColumn('emergency_phone_1', 'phone_1');
            });
        }

        if (Schema::hasColumn('hospitals', 'emergency_phone_2') && !Schema::hasColumn('hospitals', 'phone_2')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->renameColumn('emergency_phone_2', 'phone_2');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('hospitals', 'phone_2') && !Schema::hasColumn('hospitals', 'emergency_phone_2')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->renameColumn('phone_2', 'emergency_phone_2');
            });
        }

        if (Schema::hasColumn('hospitals', 'phone_1') && !Schema::hasColumn('hospitals', 'emergency_phone_1')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->renameColumn('phone_1', 'emergency_phone_1');
            });
        }

        if (Schema::hasColumn('hospitals', 'country_code_2')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->dropColumn('country_code_2');
            });
        }

        if (Schema::hasColumn('hospitals', 'country_code_1') && !Schema::hasColumn('hospitals', 'emergency_country_code')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->renameColumn('country_code_1', 'emergency_country_code');
            });
        }

        if (Schema::hasColumn('doctors', 'country_code_2')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->dropColumn('country_code_2');
            });
        }

        if (Schema::hasColumn('doctors', 'country_code_1') && !Schema::hasColumn('doctors', 'country_code')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->renameColumn('country_code_1', 'country_code');
            });
        }
    }
};

