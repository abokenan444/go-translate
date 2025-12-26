<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['free', 'pay_per_use', 'subscription', 'custom'])->default('pay_per_use');
            
            // Pay-per-use pricing
            $table->decimal('price_per_translation', 10, 4)->default(0); // e.g., $0.05 per translation
            $table->decimal('price_per_1k_chars', 10, 4)->default(0); // e.g., $0.02 per 1k chars
            $table->decimal('price_per_word', 10, 6)->default(0); // e.g., $0.001 per word
            
            // Subscription pricing
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('yearly_price', 10, 2)->nullable();
            
            // Limits
            $table->integer('monthly_translation_limit')->nullable(); // null = unlimited
            $table->integer('daily_translation_limit')->nullable();
            $table->integer('max_chars_per_translation')->default(5000);
            
            // Features
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_bulk_translation')->default(false);
            $table->boolean('has_advanced_features')->default(false);
            $table->boolean('has_priority_support')->default(false);
            $table->json('allowed_languages')->nullable(); // null = all languages
            $table->json('features')->nullable(); // Additional custom features
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true); // Show in pricing page
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
