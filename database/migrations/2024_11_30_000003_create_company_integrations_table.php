<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('provider'); // slack, stripe, zapier, etc.
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('webhook_url')->nullable();
            $table->json('domains')->nullable(); // allowed domains
            $table->json('events')->nullable(); // subscribed events
            $table->json('features_flags')->nullable(); // enabled features
            $table->enum('status', ['active', 'inactive', 'pending', 'failed'])->default('pending');
            $table->timestamp('last_success_at')->nullable();
            $table->timestamp('last_error_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'provider']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_integrations');
    }
};
