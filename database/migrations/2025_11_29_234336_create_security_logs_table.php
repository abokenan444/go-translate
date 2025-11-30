<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('attack_type', 100)->index(); // SQL Injection, XSS, CSRF, etc.
            $table->string('ip_address', 45)->index(); // IPv4 or IPv6
            $table->text('url')->nullable();
            $table->string('input_field', 255)->nullable();
            $table->text('suspicious_value')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->string('request_method', 10)->nullable(); // GET, POST, etc.
            $table->json('payload')->nullable(); // Full request data
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium')->index();
            $table->boolean('blocked')->default(true)->index();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('created_at');
            $table->index(['attack_type', 'created_at']);
            $table->index(['severity', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
