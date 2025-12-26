<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('company_settings')) {
            Schema::create('company_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->json('enabled_features')->nullable();
                $table->json('allowed_models')->nullable();
                $table->unsignedInteger('rate_limit_per_minute')->default(120);
                $table->unsignedBigInteger('max_tokens_monthly')->default(0);
                $table->timestamps();
                $table->unique('company_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
