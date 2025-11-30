<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realtime_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('realtime_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('external_id')->nullable(); // For non-registered users
            $table->string('display_name');
            $table->enum('role', ['moderator', 'speaker', 'listener'])->default('speaker');
            $table->enum('status', ['connected', 'disconnected', 'muted'])->default('connected');
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_video_enabled')->default(false);
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->integer('total_speaking_time')->default(0); // in seconds
            $table->json('connection_quality')->nullable(); // latency, packet_loss, etc.
            $table->timestamps();
            
            $table->index(['session_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realtime_participants');
    }
};
