<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('translation_cache')) {
            Schema::create('translation_cache', function (Blueprint $table) {
                $table->id();
                $table->string('cache_key')->unique();
                $table->text('source_text')->nullable();
                $table->string('source_language', 10)->nullable();
                $table->string('target_language', 10)->nullable();
                $table->string('tone', 50)->nullable();
                $table->string('industry', 100)->nullable();
                $table->string('task_type', 100)->nullable();
                $table->longText('translated_text');
                $table->text('metadata')->nullable();
                $table->unsignedInteger('tokens_used')->default(0);
                $table->unsignedInteger('response_time_ms')->default(0);
                $table->float('quality_score')->nullable();
                $table->unsignedInteger('hit_count')->default(0);
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamps();
                $table->index(['cache_key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_cache');
    }
};
