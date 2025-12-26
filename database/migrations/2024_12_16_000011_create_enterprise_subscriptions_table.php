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
        Schema::create('enterprise_subscriptions', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            
            // Company Information
            $table->string('subscription_code')->unique();
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('tax_id')->nullable();
            
            // Billing Contact
            $table->string('billing_contact_name')->nullable();
            $table->string('billing_contact_email')->nullable();
            
            // Subscription Details
            $table->string('plan_type')->default('pay_as_you_go'); // pay_as_you_go, committed, hybrid
            $table->string('status')->default('pending'); // pending, active, suspended, cancelled
            
            // Pricing
            $table->decimal('price_per_word', 10, 6)->default(0.01);
            $table->decimal('price_per_character', 10, 6)->default(0.0015);
            $table->decimal('price_per_api_call', 10, 6)->default(0.001);
            $table->decimal('price_per_voice_second', 10, 6)->default(0.02);
            
            // Committed Volume Pricing
            $table->unsignedBigInteger('committed_words_monthly')->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('overage_multiplier', 5, 2)->default(1.5);
            
            // Usage Totals
            $table->unsignedBigInteger('total_words_translated')->default(0);
            $table->unsignedBigInteger('total_characters_translated')->default(0);
            $table->unsignedBigInteger('total_api_calls')->default(0);
            $table->unsignedBigInteger('total_voice_seconds')->default(0);
            
            // Current Month Usage
            $table->unsignedBigInteger('current_month_words')->default(0);
            $table->unsignedBigInteger('current_month_characters')->default(0);
            $table->unsignedBigInteger('current_month_api_calls')->default(0);
            $table->unsignedBigInteger('current_month_voice_seconds')->default(0);
            $table->timestamp('current_month_start')->nullable();
            $table->decimal('current_month_cost', 12, 2)->default(0);
            
            // Billing
            $table->decimal('total_billed', 12, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('billing_cycle')->default('monthly'); // monthly, quarterly, annual
            $table->timestamp('last_billing_date')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->unsignedInteger('payment_terms_days')->default(30);
            
            // Spending Limits
            $table->decimal('spending_limit_monthly', 12, 2)->nullable();
            $table->boolean('auto_suspend_on_limit')->default(false);
            $table->unsignedInteger('warning_threshold_percentage')->default(80);
            $table->boolean('spending_alert_sent')->default(false);
            
            // Features & Limits
            $table->json('enabled_features')->nullable();
            $table->unsignedInteger('max_team_members')->default(10);
            $table->unsignedInteger('max_projects')->default(50);
            $table->unsignedInteger('max_api_keys')->default(5);
            
            // Premium Features
            $table->boolean('dedicated_support')->default(false);
            $table->boolean('custom_integrations')->default(false);
            $table->boolean('white_label')->default(false);
            $table->boolean('sso_enabled')->default(false);
            $table->boolean('audit_logs')->default(true);
            $table->boolean('priority_processing')->default(false);
            $table->string('sla_tier')->nullable(); // basic, standard, premium, enterprise
            
            // Contract
            $table->timestamp('contract_start_date')->nullable();
            $table->timestamp('contract_end_date')->nullable();
            $table->unsignedInteger('contract_term_months')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->text('special_terms')->nullable();
            $table->text('notes')->nullable();
            
            // Account Manager
            $table->string('account_manager_name')->nullable();
            $table->string('account_manager_email')->nullable();
            $table->string('account_manager_phone')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('plan_type');
            $table->index('company_name');
            $table->index('next_billing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_subscriptions');
    }
};
