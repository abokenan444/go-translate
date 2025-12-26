<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('verification_registry')) {
            Schema::create('verification_registry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cts_certificate_id')->constrained()->cascadeOnDelete();
            $table->string('verification_code')->unique(); // Public verification code
            $table->string('verifier_ip')->nullable();
            $table->string('verifier_country')->nullable();
            $table->string('verifier_user_agent')->nullable();
            $table->timestamp('verified_at');
            $table->integer('verification_count')->default(1);
            $table->timestamp('last_verified_at');
            $table->timestamps();
            
            $table->index('verification_code');
                $table->index('verified_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_registry');
    }
};
