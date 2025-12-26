<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['topup', 'call_usage', 'refund', 'bonus', 'referral']);
            $table->decimal('amount', 10, 2); // positive for credit, negative for debit
            $table->decimal('balance_after', 10, 2);
            $table->string('description');
            $table->json('metadata')->nullable(); // call_id, payment_id, etc.
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_wallet_transactions');
    }
};
