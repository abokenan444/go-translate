<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index(); // IPv6 support
            $table->string('fingerprint', 64)->nullable()->index(); // Browser fingerprint
            $table->string('cookie_id', 64)->nullable()->index(); // Cookie tracking
            $table->string('user_agent', 500)->nullable();
            $table->integer('daily_count')->default(1);
            $table->date('usage_date')->index();
            $table->json('translation_history')->nullable(); // تاريخ الترجمات
            $table->timestamp('first_used_at');
            $table->timestamp('last_used_at');
            $table->boolean('blocked')->default(false)->index(); // حظر مؤقت
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();

            // Index مركب للبحث حسب IP + التاريخ
            $table->index(['ip_address', 'usage_date'], 'ip_date_idx');
            
            // Index مركب للبحث حسب Fingerprint + التاريخ
            $table->index(['fingerprint', 'usage_date'], 'fingerprint_date_idx');
            
            // Index مركب للبحث حسب Cookie + التاريخ
            $table->index(['cookie_id', 'usage_date'], 'cookie_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_usage_tracking');
    }
};
