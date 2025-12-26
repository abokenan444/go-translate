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
        if (Schema::hasTable('official_translation_orders')) {
            Schema::table('official_translation_orders', function (Blueprint $table) {
                // Delivery method and sworn translator options
                if (!Schema::hasColumn('official_translation_orders', 'delivery_method')) {
                    $table->string('delivery_method', 20)->default('digital')->after('payment_status');
                }
                if (!Schema::hasColumn('official_translation_orders', 'sworn_translator')) {
                    $table->boolean('sworn_translator')->default(false)->after('delivery_method');
                }
                
                // Shipping information (for physical delivery)
                if (!Schema::hasColumn('official_translation_orders', 'shipping_name')) {
                    $table->string('shipping_name')->nullable()->after('sworn_translator');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipping_phone')) {
                    $table->string('shipping_phone', 50)->nullable()->after('shipping_name');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipping_address')) {
                    $table->text('shipping_address')->nullable()->after('shipping_phone');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipping_city')) {
                    $table->string('shipping_city', 100)->nullable()->after('shipping_address');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipping_state')) {
                    $table->string('shipping_state', 100)->nullable()->after('shipping_city');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipping_postal_code')) {
                    $table->string('shipping_postal_code', 20)->nullable()->after('shipping_state');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipping_country')) {
                    $table->string('shipping_country', 2)->nullable()->after('shipping_postal_code');
                }
                
                // Shipping tracking (for future use)
                if (!Schema::hasColumn('official_translation_orders', 'tracking_number')) {
                    $table->string('tracking_number')->nullable()->after('shipping_country');
                }
                if (!Schema::hasColumn('official_translation_orders', 'shipped_at')) {
                    $table->timestamp('shipped_at')->nullable()->after('tracking_number');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_translation_orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_method',
                'sworn_translator',
                'shipping_name',
                'shipping_phone',
                'shipping_address',
                'shipping_city',
                'shipping_state',
                'shipping_postal_code',
                'shipping_country',
                'tracking_number',
                'shipped_at',
            ]);
        });
    }
};
