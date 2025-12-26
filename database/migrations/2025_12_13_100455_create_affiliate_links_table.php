<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('affiliate_links')) {
            Schema::create('affiliate_links', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('code')->unique();
                $table->string('url');
                $table->integer('clicks')->default(0);
                $table->integer('conversions')->default(0);
                $table->decimal('commission_earned', 10, 2)->default(0);
                $table->decimal('commission_rate', 5, 2)->default(10.00); // 10% default
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_clicked_at')->nullable();
                $table->timestamps();
                
                $table->index('code');
                $table->index('user_id');
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_links');
    }
};
