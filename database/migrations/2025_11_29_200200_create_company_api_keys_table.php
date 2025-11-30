<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('key')->unique();
            $table->string('name')->nullable();
            $table->json('scopes')->nullable();
            $table->unsignedInteger('rate_limit_per_minute')->default(60);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('revoked')->default(false);
            $table->timestamps();
            $table->index(['company_id','revoked']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_api_keys');
    }
};
