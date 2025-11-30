<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Stripe columns to existing subscriptions table
        if (Schema::hasTable('subscriptions') && !Schema::hasColumn('subscriptions', 'stripe_customer_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('stripe_customer_id')->nullable()->after('stripe_subscription_id');
                $table->string('stripe_price_id')->nullable()->after('stripe_customer_id');
                $table->string('plan_name')->nullable()->after('stripe_price_id');
                $table->integer('tokens_limit')->default(0)->after('plan_name');
                $table->integer('tokens_used')->default(0)->after('tokens_limit');
                $table->timestamp('canceled_at')->nullable()->after('current_period_end');
            });
        }

        // Create payment_transactions table
        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
                $table->string('stripe_payment_intent_id')->unique()->nullable();
                $table->string('stripe_invoice_id')->nullable();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('usd');
                $table->string('status'); // succeeded, pending, failed, refunded
                $table->string('type'); // subscription, one_time, refund
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'status']);
                $table->index('stripe_payment_intent_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn([
                    'stripe_customer_id',
                    'stripe_price_id',
                    'plan_name',
                    'tokens_limit',
                    'tokens_used',
                    'canceled_at',
                ]);
            });
        }

        Schema::dropIfExists('payment_transactions');
    }
};
