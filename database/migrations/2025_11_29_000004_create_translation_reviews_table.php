<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translation_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('memory_id');
            $table->unsignedTinyInteger('quality_score')->nullable();
            $table->text('comments')->nullable();
            $table->json('suggestions')->nullable();
            $table->timestamps();
            $table->foreign('memory_id')->references('id')->on('cultural_memories')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_reviews');
    }
};
