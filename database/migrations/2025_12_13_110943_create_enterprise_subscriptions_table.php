<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('enterprise_subscriptions')) {
            Schema::create('enterprise_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('subscription_code')->unique();
            $table->string('plan_name');
            $table->enum('status', ['pending', 'active', 'suspended', 'cancelled', 'expired'])->default('pending');
            
            // Pricing
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->integer('payment_terms_days')->default(30);
            
            // Usage limits
            $table->bigInteger('committed_words_monthly')->nullable();
            $table->decimal('overage_rate_per_word', 10, 6)->nullable();
            $table->decimal('spending_limit_monthly', 10, 2)->nullable();
            
            // Current usage
            $table->bigInteger('current_month_words')->default(0);
            $table->bigInteger('current_month_characters')->default(0);
            $table->integer('current_month_api_calls')->default(0);
            $table->integer('current_month_voice_seconds')->default(0);
            $table->decimal('current_month_cost', 10, 2)->default(0);
            $table->timestamp('current_month_start')->nullable();
            
            // Billing
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('next_billing_date')->nullable();
            $table->date('last_billing_date')->nullable();
            
            // Alerts
            $table->boolean('spending_alert_sent')->default(false);
            $table->integer('usage_alert_threshold')->default(80);
            
            // Contract
            $table->text('contract_terms')->nullable();
            $table->string('contract_file_path')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
                $table->index('next_billing_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('enterprise_subscriptions');
    }
};
