<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cultural_memories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('source_language', 10)->nullable();
            $table->string('target_language', 10);
            $table->string('target_culture', 10)->nullable();
            $table->text('source_text');
            $table->text('translated_text');
            $table->string('brand_voice')->nullable();
            $table->string('emotion')->nullable();
            $table->string('tone')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'target_language', 'target_culture']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultural_memories');
    }
};
