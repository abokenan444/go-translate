<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_call_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('live_call_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->unsignedInteger('seconds_processed')->default(0);
            $table->decimal('cost_snapshot', 10, 4)->default(0.0000);
            $table->timestamps();

            $table->index(['session_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_call_usages');
    }
};
