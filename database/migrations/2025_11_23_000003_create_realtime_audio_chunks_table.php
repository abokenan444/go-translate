<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('realtime_audio_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')
                ->constrained('realtime_sessions')
                ->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('direction')->default('source_to_target');
            $table->string('format')->default('webm');
            $table->string('path');
            $table->unsignedInteger('sequence')->default(0);
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realtime_audio_chunks');
    }
};
