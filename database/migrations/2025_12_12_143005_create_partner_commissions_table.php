<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_commissions')) {
            Schema::create('partner_commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('order_id');
                $table->enum('order_type', ['translation', 'official_document'])->default('translation');
                $table->decimal('order_amount', 10, 2);
                $table->decimal('commission_rate', 5, 2); // percentage
                $table->decimal('commission_amount', 10, 2);
                $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
                $table->datetime('approved_at')->nullable();
                $table->datetime('paid_at')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('payment_reference')->nullable();
                $table->timestamps();
                
                $table->index(['partner_id', 'status']);
                $table->index(['order_id', 'order_type']);
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_commissions');
    }
};
