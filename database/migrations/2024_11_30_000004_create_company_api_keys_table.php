<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('key')->unique(); // hashed API key
            $table->string('name'); // friendly name for the key
            $table->json('scopes')->nullable(); // permissions: read, write, admin, etc.
            $table->integer('rate_limit_per_minute')->default(60);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('revoked')->default(false);
            $table->timestamps();

            $table->index(['company_id', 'revoked']);
            $table->index('key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_api_keys');
    }
};
