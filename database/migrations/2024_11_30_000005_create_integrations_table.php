<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('platform'); // slack, wordpress, shopify, etc.
            $table->string('site_url')->nullable();
            $table->text('credentials')->nullable(); // encrypted credentials
            $table->enum('status', ['active', 'inactive', 'pending', 'error'])->default('pending');
            $table->json('metadata')->nullable(); // additional configuration
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'platform']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
