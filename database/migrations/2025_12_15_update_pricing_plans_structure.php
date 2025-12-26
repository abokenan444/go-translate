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
        // Check if we need to update the pricing_plans table structure
        if (Schema::hasTable('pricing_plans')) {
            Schema::table('pricing_plans', function (Blueprint $table) {
                // Add new columns if they don't exist
                if (!Schema::hasColumn('pricing_plans', 'type')) {
                    $table->string('type')->default('custom')->after('slug');
                }
                if (!Schema::hasColumn('pricing_plans', 'price_per_translation')) {
                    $table->decimal('price_per_translation', 10, 4)->nullable()->after('price');
                }
                if (!Schema::hasColumn('pricing_plans', 'price_per_1k_chars')) {
                    $table->decimal('price_per_1k_chars', 10, 4)->nullable()->after('price_per_translation');
                }
                if (!Schema::hasColumn('pricing_plans', 'price_per_word')) {
                    $table->decimal('price_per_word', 10, 4)->nullable()->after('price_per_1k_chars');
                }
                if (!Schema::hasColumn('pricing_plans', 'monthly_price')) {
                    $table->decimal('monthly_price', 10, 2)->nullable()->after('price_per_word');
                }
                if (!Schema::hasColumn('pricing_plans', 'yearly_price')) {
                    $table->decimal('yearly_price', 10, 2)->nullable()->after('monthly_price');
                }
                if (!Schema::hasColumn('pricing_plans', 'daily_translation_limit')) {
                    $table->integer('daily_translation_limit')->nullable()->after('tokens_limit');
                }
                if (!Schema::hasColumn('pricing_plans', 'monthly_translation_limit')) {
                    $table->integer('monthly_translation_limit')->nullable()->after('daily_translation_limit');
                }
                if (!Schema::hasColumn('pricing_plans', 'max_chars_per_translation')) {
                    $table->integer('max_chars_per_translation')->nullable()->after('monthly_translation_limit');
                }
                if (!Schema::hasColumn('pricing_plans', 'has_api_access')) {
                    $table->boolean('has_api_access')->default(false)->after('api_access');
                }
                if (!Schema::hasColumn('pricing_plans', 'has_bulk_translation')) {
                    $table->boolean('has_bulk_translation')->default(false)->after('has_api_access');
                }
                if (!Schema::hasColumn('pricing_plans', 'has_advanced_features')) {
                    $table->boolean('has_advanced_features')->default(false)->after('has_bulk_translation');
                }
                if (!Schema::hasColumn('pricing_plans', 'has_priority_support')) {
                    $table->boolean('has_priority_support')->default(false)->after('has_advanced_features');
                }
                if (!Schema::hasColumn('pricing_plans', 'is_public')) {
                    $table->boolean('is_public')->default(true)->after('is_active');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pricing_plans')) {
            Schema::table('pricing_plans', function (Blueprint $table) {
                $columns = [
                    'type',
                    'price_per_translation',
                    'price_per_1k_chars',
                    'price_per_word',
                    'monthly_price',
                    'yearly_price',
                    'daily_translation_limit',
                    'monthly_translation_limit',
                    'max_chars_per_translation',
                    'has_api_access',
                    'has_bulk_translation',
                    'has_advanced_features',
                    'has_priority_support',
                    'is_public'
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('pricing_plans', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
