<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('clicks')) {
            Schema::create('clicks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('referral_link_id')->constrained()->cascadeOnDelete();
                $table->string('ip')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('country')->nullable();
                $table->string('referer')->nullable();
                $table->string('session_id')->nullable();
                $table->timestamp('clicked_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
