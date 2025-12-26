<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sandbox_api_keys')) {
            Schema::create('sandbox_api_keys', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sandbox_instance_id')->constrained()->onDelete('cascade');
                $table->string('key', 64)->unique();
                $table->string('name');
                $table->json('scopes')->nullable();
                $table->string('rate_limit_profile')->default('sandbox_basic');
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
                
                // Indexes
                $table->index('sandbox_instance_id');
                $table->index('key');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandbox_api_keys');
    }
};
