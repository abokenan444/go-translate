<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained('affiliates')->cascadeOnDelete();
                $table->foreignId('conversion_id')->nullable()->constrained('conversions')->nullOnDelete();
                $table->decimal('rate', 5, 2)->default(0); // percentage at time of calc
                $table->decimal('amount', 10, 2)->default(0);
                $table->string('currency')->default('USD');
                $table->string('status')->default('pending'); // pending, frozen, eligible, paid, void
                $table->timestamp('eligible_at')->nullable(); // after freeze
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
