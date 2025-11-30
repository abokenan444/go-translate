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
        Schema::create('user_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // slack, teams, zoom, gitlab
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('status')->default('active'); // active, inactive, error
            $table->json('metadata')->nullable(); // Additional platform-specific data
            $table->timestamp('connected_at');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            // Ensure one integration per platform per user
            $table->unique(['user_id', 'platform']);
            
            // Index for faster queries
            $table->index(['user_id', 'status']);
            $table->index('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_integrations');
    }
};
