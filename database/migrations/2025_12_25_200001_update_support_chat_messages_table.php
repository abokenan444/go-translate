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
        Schema::table('support_chat_messages', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('support_chat_messages', 'sender_type')) {
                $table->string('sender_type')->default('user')->after('session_id'); // user, agent, system
            }
            if (!Schema::hasColumn('support_chat_messages', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('sender_type');
            }
            if (!Schema::hasColumn('support_chat_messages', 'sender_name')) {
                $table->string('sender_name')->nullable()->after('sender_id');
            }
            if (!Schema::hasColumn('support_chat_messages', 'message_type')) {
                $table->string('message_type')->default('text')->after('message'); // text, image, file
            }
            if (!Schema::hasColumn('support_chat_messages', 'file_url')) {
                $table->string('file_url')->nullable()->after('message_type');
            }
            if (!Schema::hasColumn('support_chat_messages', 'file_name')) {
                $table->string('file_name')->nullable()->after('file_url');
            }
            if (!Schema::hasColumn('support_chat_messages', 'metadata')) {
                $table->json('metadata')->nullable()->after('read_at');
            }
            
            // Add index for better performance
            $table->index(['sender_type', 'sender_id']);
        });

        // Update existing data if needed
        if (Schema::hasColumn('support_chat_messages', 'is_from_agent')) {
            \DB::statement("UPDATE support_chat_messages SET sender_type = CASE WHEN is_from_agent = 1 THEN 'agent' ELSE 'user' END WHERE sender_type = 'user'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_chat_messages', function (Blueprint $table) {
            $table->dropIndex(['sender_type', 'sender_id']);
            
            if (Schema::hasColumn('support_chat_messages', 'sender_type')) {
                $table->dropColumn('sender_type');
            }
            if (Schema::hasColumn('support_chat_messages', 'sender_id')) {
                $table->dropColumn('sender_id');
            }
            if (Schema::hasColumn('support_chat_messages', 'sender_name')) {
                $table->dropColumn('sender_name');
            }
            if (Schema::hasColumn('support_chat_messages', 'message_type')) {
                $table->dropColumn('message_type');
            }
            if (Schema::hasColumn('support_chat_messages', 'file_url')) {
                $table->dropColumn('file_url');
            }
            if (Schema::hasColumn('support_chat_messages', 'file_name')) {
                $table->dropColumn('file_name');
            }
            if (Schema::hasColumn('support_chat_messages', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};
