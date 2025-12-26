<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_logs', function (Blueprint $table) {
            $table->id();
            $table->string('task_key', 100)->index();
            $table->string('culture_code', 64)->index();
            $table->string('tone_code', 64)->nullable()->index();
            $table->string('emotion_code', 64)->nullable()->index();
            $table->string('source_lang', 10)->index();
            $table->string('target_lang', 10)->index();
            $table->text('prompt_text');
            $table->text('input_text')->nullable();
            $table->text('output_text')->nullable();
            $table->integer('token_count')->default(0);
            $table->integer('response_time_ms')->nullable(); // وقت الاستجابة
            $table->boolean('from_cache')->default(false)->index(); // هل من الكاش؟
            $table->decimal('cost', 10, 6)->nullable(); // التكلفة التقديرية
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Index للتحليلات
            $table->index(['task_key', 'created_at'], 'task_analytics_idx');
            $table->index(['from_cache', 'created_at'], 'cache_analytics_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_logs');
    }
};
