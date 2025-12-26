<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_subscriptions')) {
            Schema::create('partner_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->enum('subscription_tier', ['basic', 'professional', 'enterprise'])->default('basic');
                $table->integer('monthly_quota')->default(1000);
                $table->integer('api_calls_limit')->default(100);
                $table->boolean('white_label_enabled')->default(false);
                $table->boolean('custom_domain_enabled')->default(false);
                $table->decimal('price', 10, 2)->default(0);
                $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
                $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active');
                $table->datetime('starts_at');
                $table->datetime('ends_at')->nullable();
                $table->timestamps();
                
                $table->index(['partner_id', 'status']);
                $table->index('subscription_tier');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_subscriptions');
    }
};
