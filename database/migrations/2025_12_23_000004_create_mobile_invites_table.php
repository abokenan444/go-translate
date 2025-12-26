<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->string('invite_code', 20)->unique();
            $table->string('invited_email')->nullable();
            $table->string('invited_phone')->nullable();
            $table->foreignId('registered_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'sent', 'registered', 'rewarded'])->default('pending');
            $table->decimal('reward_minutes', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['inviter_id', 'status']);
            $table->index('invite_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_invites');
    }
};
