<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_subscription_id')->nullable()->constrained()->onDelete('set null');
            
            // Usage details
            $table->string('service_type')->default('translation'); // translation, api, bulk, etc.
            $table->string('source_lang', 10)->nullable();
            $table->string('target_lang', 10)->nullable();
            $table->integer('character_count')->default(0);
            $table->integer('word_count')->default(0);
            $table->boolean('from_cache')->default(false);
            
            // Pricing
            $table->decimal('unit_price', 10, 6)->default(0);
            $table->decimal('total_cost', 10, 4)->default(0);
            $table->string('pricing_model')->default('per_translation'); // per_translation, per_char, per_word
            
            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['user_subscription_id', 'created_at']);
            $table->index('service_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_records');
    }
};
