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
        Schema::create('industry_behaviors', function (Blueprint $table) {
            $table->id();
            $table->string('industry'); // e.g., 'finance', 'healthcare', 'legal', 'marketing'
            $table->string('language')->nullable();
            $table->string('culture')->nullable();
            $table->string('tone')->nullable(); // formal, technical, empathetic
            $table->json('vocabulary_preferred')->nullable(); // terms to use
            $table->json('vocabulary_avoid')->nullable(); // terms to avoid
            $table->json('style_rules')->nullable(); // formatting, structure rules
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['industry', 'language', 'culture']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_behaviors');
    }
};
