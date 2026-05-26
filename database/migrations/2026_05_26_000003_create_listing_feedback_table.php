<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('listing_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 20);
            $table->unsignedBigInteger('entity_id');
            $table->string('entity_name');
            $table->string('vote_type', 10); // green|red
            $table->string('issue', 50)->nullable();
            $table->text('details')->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index(['entity_type', 'entity_id', 'vote_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_feedback');
    }
};

