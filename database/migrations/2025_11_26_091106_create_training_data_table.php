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
        Schema::create('training_data', function (Blueprint $table) {
            $table->id();
            
            // Input data
            $table->text('source_text');
            $table->string('source_language', 10);
            $table->string('target_language', 10);
            
            // Translation result
            $table->text('translated_text');
            
            // Context and metadata
            $table->string('tone')->nullable();
            $table->text('context')->nullable();
            $table->string('industry')->nullable();
            $table->string('model_used')->default('gpt-4o-mini');
            
            // Quality metrics
            $table->integer('user_rating')->nullable(); // 1-5 stars
            $table->text('user_feedback')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->integer('word_count')->default(0);
            $table->integer('tokens_used')->default(0);
            
            // User information (optional)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            
            // Status and flags
            $table->boolean('is_suitable_for_training')->default(true);
            $table->boolean('contains_sensitive_data')->default(false);
            $table->string('data_quality')->default('pending'); // pending, good, excellent, poor
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['source_language', 'target_language']);
            $table->index('is_suitable_for_training');
            $table->index('data_quality');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_data');
    }
};
