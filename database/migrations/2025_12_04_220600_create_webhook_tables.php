<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('webhook_endpoints')) {
            Schema::create('webhook_endpoints', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->nullable()->constrained('affiliates')->cascadeOnDelete();
                $table->string('url');
                $table->json('events')->nullable(); // ['commission.created', 'commission.paid', 'payout.initiated', 'payout.paid']
                $table->string('secret')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('webhook_logs')) {
            Schema::create('webhook_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('webhook_endpoint_id')->constrained('webhook_endpoints')->cascadeOnDelete();
                $table->string('event');
                $table->json('payload');
                $table->integer('response_code')->nullable();
                $table->text('response_body')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('webhook_endpoints');
    }
};
