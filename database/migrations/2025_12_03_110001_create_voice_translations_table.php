<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('voice_translations')) {
            Schema::create('voice_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_subscription_id')->nullable()->constrained()->onDelete('set null');
                
                // Audio details
                $table->string('audio_file_path')->nullable();
                $table->string('source_language', 10)->default('auto');
                $table->string('target_language', 10);
                $table->integer('audio_duration_seconds')->default(0);
                $table->integer('audio_file_size')->default(0); // bytes
                
                // Transcription
                $table->text('transcribed_text')->nullable();
                $table->decimal('transcription_confidence', 5, 2)->nullable();
                
                // Translation
                $table->text('translated_text')->nullable();
                $table->decimal('translation_quality_score', 5, 2)->nullable();
                
                // Speech synthesis
                $table->string('output_audio_path')->nullable();
                $table->string('voice_name')->nullable();
                $table->integer('output_duration_seconds')->default(0);
                
                // Pricing
                $table->decimal('cost', 10, 4)->default(0);
                $table->string('pricing_model')->default('per_second'); // per_second, per_minute, flat_rate
                
                // Status
                $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
                $table->text('error_message')->nullable();
                
                // Metadata
                $table->integer('processing_time_ms')->default(0);
                $table->string('ip_address', 45)->nullable();
                $table->json('metadata')->nullable();
                
                $table->timestamps();
                
                $table->index(['user_id', 'created_at']);
                $table->index(['status', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_translations');
    }
};
