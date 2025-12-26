<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_api_keys')) {
            Schema::create('partner_api_keys', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('key_name');
                $table->string('api_key', 64)->unique();
                $table->text('api_secret'); // encrypted
                $table->json('permissions')->nullable();
                $table->integer('rate_limit')->default(100); // requests per minute
                $table->datetime('last_used_at')->nullable();
                $table->datetime('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['partner_id', 'is_active']);
                $table->index('api_key');
                $table->index('last_used_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_api_keys');
    }
};
