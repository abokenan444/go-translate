<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translation_id')->constrained('translations')->onDelete('cascade');
            $table->foreignId('translation_version_id')->nullable()->constrained('translation_versions')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->string('tag')->nullable(); // good, bad, inappropriate, etc.
            $table->text('comment')->nullable();
            $table->text('suggested_text')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['translation_id', 'user_id']);
            $table->index('rating');
            $table->index('tag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_feedback');
    }
};
