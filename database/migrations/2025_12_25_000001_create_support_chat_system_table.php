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
        // Support ticket messages/replies
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message');
            $table->boolean('is_from_support')->default(false);
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['ticket_id', 'created_at']);
        });

        // Live chat sessions
        Schema::create('support_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->enum('status', ['waiting', 'active', 'closed', 'missed'])->default('waiting');
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('department')->default('general');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('agent_id');
        });

        // Live chat messages
        Schema::create('support_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('support_chat_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message');
            $table->boolean('is_from_agent')->default(false);
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['session_id', 'created_at']);
        });

        // Support agents availability
        Schema::create('support_agent_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_online')->default(false);
            $table->boolean('is_available')->default(false);
            $table->integer('max_chats')->default(5);
            $table->integer('active_chats')->default(0);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_agent_availability');
        Schema::dropIfExists('support_chat_messages');
        Schema::dropIfExists('support_chat_sessions');
        Schema::dropIfExists('support_ticket_messages');
    }
};
