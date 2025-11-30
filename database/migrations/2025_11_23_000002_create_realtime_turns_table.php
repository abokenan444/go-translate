<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('realtime_turns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')
                ->constrained('realtime_sessions')
                ->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('external_id')->nullable();
            $table->string('role')->default('speaker');
            $table->string('direction')->default('source_to_target');
            $table->text('source_text')->nullable();
            $table->text('translated_text')->nullable();
            $table->string('source_language', 10)->nullable();
            $table->string('target_language', 10)->nullable();
            $table->json('raw_stt')->nullable();
            $table->json('raw_llm')->nullable();
            $table->json('raw_tts')->nullable();
            $table->string('audio_path_source')->nullable();
            $table->string('audio_path_translated')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realtime_turns');
    }
};
