<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultural_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50); // system, industry, tone, context, adaptation, sensitivity, quality
            $table->string('language_pair', 20); // en-ar, en-fr, all, etc.
            $table->string('tone', 50)->default('all'); // professional, casual, formal, etc.
            $table->string('industry', 100)->default('all'); // healthcare, legal, finance, etc.
            $table->text('prompt_text'); // The actual prompt content
            $table->integer('priority')->default(50); // 1-100, higher = more important
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for faster lookups
            $table->index(['category', 'language_pair', 'tone', 'industry']);
            $table->index('is_active');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultural_prompts');
    }
};
