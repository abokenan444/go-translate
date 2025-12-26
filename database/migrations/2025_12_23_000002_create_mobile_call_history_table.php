<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_call_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('mobile_contacts')->nullOnDelete();
            $table->string('session_public_id');
            $table->enum('direction', ['outgoing', 'incoming']);
            $table->enum('status', ['completed', 'missed', 'declined', 'failed'])->default('completed');
            $table->string('caller_send_language', 10)->nullable();
            $table->string('caller_receive_language', 10)->nullable();
            $table->string('receiver_send_language', 10)->nullable();
            $table->string('receiver_receive_language', 10)->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->decimal('minutes_used', 10, 2)->default(0);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('session_public_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_call_history');
    }
};
