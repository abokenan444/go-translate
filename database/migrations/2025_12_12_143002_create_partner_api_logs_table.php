<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_api_logs')) {
            Schema::create('partner_api_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->foreignId('partner_api_key_id')->nullable()->constrained()->onDelete('set null');
                $table->string('endpoint');
                $table->string('method', 10);
                $table->json('request_data')->nullable();
                $table->integer('response_code');
                $table->integer('response_time'); // milliseconds
                $table->string('ip_address', 45);
                $table->text('user_agent')->nullable();
                $table->timestamp('created_at');
                
                $table->index(['partner_id', 'created_at']);
                $table->index('endpoint');
                $table->index('response_code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_api_logs');
    }
};
