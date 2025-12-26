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
        Schema::create('enterprise_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('feature_name');
            $table->text('feature_description')->nullable();
            $table->string('feature_type'); // 'api_limit', 'storage_limit', 'custom_integration', 'dedicated_support', etc.
            $table->json('feature_config'); // JSON configuration for the feature
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'feature_type']);
        });

        Schema::create('enterprise_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('request_type'); // 'feature_request', 'support_request', 'custom_integration', etc.
            $table->string('title');
            $table->text('description');
            $table->string('priority')->default('medium'); // 'low', 'medium', 'high', 'urgent'
            $table->string('status')->default('pending'); // 'pending', 'in_progress', 'completed', 'rejected'
            $table->json('metadata')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
        });

        Schema::create('enterprise_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('limit_type'); // 'api_calls', 'storage_gb', 'users', 'projects', etc.
            $table->bigInteger('limit_value');
            $table->bigInteger('current_usage')->default(0);
            $table->boolean('is_unlimited')->default(false);
            $table->timestamps();
            
            $table->unique(['company_id', 'limit_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_limits');
        Schema::dropIfExists('enterprise_requests');
        Schema::dropIfExists('enterprise_features');
    }
};
