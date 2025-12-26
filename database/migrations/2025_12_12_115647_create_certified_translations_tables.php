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
        // Create certified_translations table
        if (!Schema::hasTable('certified_translations')) {
            Schema::create('certified_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('certificate_id', 50)->unique();
                $table->string('document_type', 100);
                $table->string('source_language', 10);
                $table->string('target_language', 10);
                $table->string('original_file_path', 255);
                $table->string('certified_file_path', 255)->nullable();
                $table->enum('delivery_method', ['digital', 'physical']);
                $table->string('status', 50)->default('processing');
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('certificate_id');
                $table->index('status');
            });
        }

        // Create shipping_orders table
        if (!Schema::hasTable('shipping_orders')) {
            Schema::create('shipping_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('certified_translation_id')->constrained()->onDelete('cascade');
                $table->string('recipient_name', 255);
                $table->string('phone', 50);
                $table->string('street_address', 255);
                $table->string('city', 100);
                $table->string('state', 100)->nullable();
                $table->string('postal_code', 20);
                $table->string('country', 100);
                $table->string('status', 50)->default('pending');
                $table->string('tracking_number', 100)->nullable();
                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamps();
                
                $table->index('status');
                $table->index('tracking_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_orders');
        Schema::dropIfExists('certified_translations');
    }
};
