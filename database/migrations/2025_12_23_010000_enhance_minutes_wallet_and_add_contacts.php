<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhance minutes_wallets table
        Schema::table('minutes_wallets', function (Blueprint $table) {
            $table->boolean('auto_topup_enabled')->default(false)->after('balance_seconds');
            $table->unsignedInteger('auto_topup_threshold_minutes')->default(5)->after('auto_topup_enabled');
            $table->unsignedInteger('auto_topup_amount_minutes')->default(60)->after('auto_topup_threshold_minutes');
            $table->string('default_send_language', 10)->default('auto')->after('auto_topup_amount_minutes');
            $table->string('default_receive_language', 10)->default('en')->after('default_send_language');
        });

        // Wallet transactions for history
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['topup', 'call_charge', 'refund', 'bonus', 'auto_topup']);
            $table->integer('amount_seconds'); // positive for credit, negative for debit
            $table->unsignedBigInteger('balance_after');
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable(); // e.g. 'call_session', 'payment'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // User contacts (for calling app)
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('display_name');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('preferred_language', 10)->default('en');
            $table->boolean('is_favorite')->default(false);
            $table->timestamp('last_called_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'contact_user_id']);
            $table->index(['user_id', 'is_favorite']);
        });

        // Call invites
        Schema::create('call_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('invite_code', 32)->unique();
            $table->enum('status', ['pending', 'accepted', 'expired', 'cancelled'])->default('pending');
            $table->string('session_public_id')->nullable(); // if inviting to specific call
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['recipient_email', 'status']);
            $table->index(['invite_code', 'status']);
        });

        // Call history / logs
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_public_id');
            $table->enum('direction', ['outgoing', 'incoming']);
            $table->string('send_language', 10);
            $table->string('receive_language', 10);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedInteger('billed_seconds')->default(0);
            $table->enum('status', ['completed', 'missed', 'cancelled', 'failed']);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_logs');
        Schema::dropIfExists('call_invites');
        Schema::dropIfExists('user_contacts');
        Schema::dropIfExists('wallet_transactions');

        Schema::table('minutes_wallets', function (Blueprint $table) {
            $table->dropColumn([
                'auto_topup_enabled',
                'auto_topup_threshold_minutes',
                'auto_topup_amount_minutes',
                'default_send_language',
                'default_receive_language',
            ]);
        });
    }
};
