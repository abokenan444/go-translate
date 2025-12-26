<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_call_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_id')->unique();

            $table->foreignId('caller_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('callee_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('mode')->default('webrtc'); // webrtc | pstn_future
            $table->string('status')->default('created'); // created|ringing|active|ended|failed

            $table->string('caller_send_lang', 10);
            $table->string('caller_receive_lang', 10);

            $table->string('callee_send_lang', 10)->nullable();
            $table->string('callee_receive_lang', 10)->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->unsignedInteger('billed_seconds')->default(0);

            $table->string('billing_mode')->default('prepaid'); // prepaid|payg
            $table->decimal('price_per_minute_snapshot', 8, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_call_sessions');
    }
};
