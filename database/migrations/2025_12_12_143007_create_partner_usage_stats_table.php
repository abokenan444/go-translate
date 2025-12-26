<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_usage_stats')) {
            Schema::create('partner_usage_stats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->integer('api_calls')->default(0);
                $table->integer('translations_count')->default(0);
                $table->bigInteger('characters_translated')->default(0);
                $table->decimal('revenue_generated', 12, 2)->default(0);
                $table->decimal('commission_earned', 12, 2)->default(0);
                $table->timestamps();
                
                $table->unique(['partner_id', 'date']);
                $table->index('date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_usage_stats');
    }
};
