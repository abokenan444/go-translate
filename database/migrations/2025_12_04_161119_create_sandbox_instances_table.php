<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sandbox_instances')) {
            Schema::create('sandbox_instances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('company_name');
                $table->string('company_slug')->unique();
                $table->string('subdomain')->unique();
                $table->enum('status', ['active', 'expired', 'blocked'])->default('active');
            
            // Brand & Industry
            $table->foreignId('brand_profile_id')->nullable()->constrained('brand_profiles')->onDelete('set null');
            $table->string('industry_profile')->nullable();
            $table->json('target_markets')->nullable();
            $table->string('tone')->nullable();
            $table->json('brand_values')->nullable();
            $table->json('preferred_terms')->nullable();
            $table->json('forbidden_terms')->nullable();
            
            // Translation Resources
            $table->foreignId('glossary_id')->nullable()->constrained('glossaries')->onDelete('set null');
            $table->foreignId('memory_id')->nullable()->constrained('translation_memories')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            
            // Preview & Settings
            $table->string('preview_url')->nullable();
            $table->boolean('is_public_preview')->default(false);
            $table->string('rate_limit_profile')->default('sandbox_basic');
            
            // Expiration
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('subdomain');
            $table->index('expires_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandbox_instances');
    }
};
