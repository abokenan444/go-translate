<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('realtime_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('type')->default('meeting'); // meeting | call | game | webinar
            $table->string('title')->nullable();
            $table->string('source_language', 10)->default('ar');
            $table->string('target_language', 10)->default('en');
            $table->string('source_culture_code', 32)->nullable();
            $table->string('target_culture_code', 32)->nullable();
            $table->boolean('bi_directional')->default(true);
            $table->boolean('record_audio')->default(false);
            $table->boolean('record_transcript')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('max_participants')->default(8);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realtime_sessions');
    }
};
