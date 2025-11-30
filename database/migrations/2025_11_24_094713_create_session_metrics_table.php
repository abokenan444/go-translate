<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('realtime_sessions')->cascadeOnDelete();
            $table->timestamp('recorded_at');
            
            // Performance Metrics
            $table->integer('avg_latency')->nullable(); // in milliseconds
            $table->integer('max_latency')->nullable();
            $table->integer('min_latency')->nullable();
            $table->float('packet_loss_rate')->nullable(); // percentage
            $table->float('jitter')->nullable(); // in milliseconds
            
            // Quality Metrics
            $table->integer('avg_audio_level')->nullable(); // 0-100
            $table->string('audio_quality')->nullable(); // good, fair, poor
            $table->string('video_quality')->nullable(); // hd, sd, low
            
            // Usage Metrics
            $table->integer('active_participants')->default(0);
            $table->integer('total_turns')->default(0);
            $table->integer('total_audio_duration')->default(0); // in seconds
            
            // Translation Metrics
            $table->integer('avg_translation_time')->nullable(); // in milliseconds
            $table->integer('successful_translations')->default(0);
            $table->integer('failed_translations')->default(0);
            
            // Connection Metrics
            $table->integer('reconnections')->default(0);
            $table->integer('disconnections')->default(0);
            
            $table->timestamps();
            
            $table->index(['session_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_metrics');
    }
};
