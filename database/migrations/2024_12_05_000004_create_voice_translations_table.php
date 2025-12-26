<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voice_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_id')->nullable()->index();
            $table->string('source_language', 10);
            $table->string('target_language', 10);
            $table->text('original_text');
            $table->text('translated_text');
            $table->string('audio_input_path')->nullable();
            $table->string('audio_output_path')->nullable();
            $table->integer('audio_duration_ms')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->string('voice_id')->nullable();
            $table->string('voice_gender', 20)->nullable();
            $table->string('voice_accent', 50)->nullable();
            $table->integer('character_count')->default(0);
            $table->string('engine', 50)->default('openai'); // openai, elevenlabs, google, azure
            $table->decimal('cost', 10, 4)->default(0);
            $table->string('status', 20)->default('completed'); // processing, completed, failed
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
            $table->index(['company_id', 'created_at']);
            $table->index(['source_language', 'target_language']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_translations');
    }
};
