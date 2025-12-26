<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_discounts')) {
            Schema::create('partner_discounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('discount_value', 10, 2);
                $table->enum('applies_to', ['all', 'specific_services'])->default('all');
                $table->json('service_types')->nullable();
                $table->decimal('min_order_value', 10, 2)->nullable();
                $table->decimal('max_discount_amount', 10, 2)->nullable();
                $table->datetime('starts_at');
                $table->datetime('ends_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['partner_id', 'is_active']);
                $table->index(['starts_at', 'ends_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_discounts');
    }
};
