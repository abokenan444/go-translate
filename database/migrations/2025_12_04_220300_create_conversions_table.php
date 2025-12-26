<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('conversions')) {
            Schema::create('conversions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained('affiliates')->cascadeOnDelete();
                $table->foreignId('referral_link_id')->nullable()->constrained('referral_links')->nullOnDelete();
                $table->string('type'); // signup, purchase
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('order_id')->nullable();
                $table->decimal('amount', 10, 2)->default(0);
                $table->string('currency')->default('USD');
                $table->timestamp('converted_at')->useCurrent();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
