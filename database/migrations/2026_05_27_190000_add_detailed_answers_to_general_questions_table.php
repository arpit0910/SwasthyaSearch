<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_questions', function (Blueprint $table) {
            $table->text('detailed_answer_en')->nullable()->after('answer_hi');
            $table->text('detailed_answer_hi')->nullable()->after('detailed_answer_en');
        });
    }

    public function down(): void
    {
        Schema::table('general_questions', function (Blueprint $table) {
            $table->dropColumn(['detailed_answer_en', 'detailed_answer_hi']);
        });
    }
};

