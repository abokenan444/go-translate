<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sandbox_webhook_logs')) {
            Schema::create('sandbox_webhook_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sandbox_instance_id')->constrained()->onDelete('cascade');
                $table->foreignId('webhook_endpoint_id')->nullable()->constrained('sandbox_webhook_endpoints')->onDelete('set null');
                $table->string('event_type');
                $table->json('payload')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->enum('delivery_status', ['simulated', 'delivered', 'failed'])->default('simulated');
                $table->timestamps();
                
                // Indexes
                $table->index('sandbox_instance_id');
                $table->index('event_type');
                $table->index('delivered_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandbox_webhook_logs');
    }
};
