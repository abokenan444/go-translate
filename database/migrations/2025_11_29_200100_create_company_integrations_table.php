<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('company_integrations')) {
            Schema::create('company_integrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->string('provider');
                $table->string('api_key')->nullable();
                $table->string('api_secret')->nullable();
                $table->string('webhook_url')->nullable();
                $table->json('domains')->nullable();
                $table->json('events')->nullable();
                $table->json('features_flags')->nullable();
                $table->enum('status', ['active','inactive'])->default('active');
                $table->timestamp('last_success_at')->nullable();
                $table->timestamp('last_error_at')->nullable();
                $table->timestamps();
                $table->unique(['company_id','provider']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_integrations');
    }
};
