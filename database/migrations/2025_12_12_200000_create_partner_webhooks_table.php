<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_webhooks')) {
            Schema::create('partner_webhooks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
                $table->string('url');
                $table->json('events'); // Array of event types
                $table->string('secret')->nullable(); // For signature verification
                $table->boolean('is_active')->default(true);
                $table->integer('failure_count')->default(0);
                $table->timestamp('last_triggered_at')->nullable();
                $table->timestamp('last_success_at')->nullable();
                $table->timestamp('last_failure_at')->nullable();
                $table->timestamps();
                
                $table->index('partner_id');
                $table->index('is_active');
            });
        }

        if (!Schema::hasTable('partner_webhook_logs')) {
            Schema::create('partner_webhook_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('webhook_id')->constrained('partner_webhooks')->onDelete('cascade');
                $table->string('event_type');
                $table->json('payload');
                $table->json('response')->nullable();
                $table->integer('status_code')->nullable();
                $table->integer('response_time')->nullable(); // milliseconds
                $table->boolean('success')->default(false);
                $table->text('error_message')->nullable();
                $table->timestamps();
                
                $table->index('webhook_id');
                $table->index('event_type');
                $table->index('success');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_webhook_logs');
        Schema::dropIfExists('partner_webhooks');
    }
};
