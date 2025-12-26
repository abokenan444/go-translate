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
        // 1. commissions - for affiliate commissions
        if (!Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('affiliate_id')->index();
                $table->unsignedBigInteger('conversion_id')->nullable()->index();
                $table->decimal('rate', 5, 2)->default(0);
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 3)->default('USD');
                $table->string('status')->default('pending'); // pending, eligible, paid
                $table->timestamp('eligible_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 2. contact_forms - for contact form submissions
        if (!Schema::hasTable('contact_forms')) {
            Schema::create('contact_forms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('subject')->nullable();
                $table->text('message');
                $table->string('phone', 50)->nullable();
                $table->string('company')->nullable();
                $table->string('status', 20)->default('new'); // new, read, replied
                $table->timestamp('read_at')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->timestamps();
                
                $table->index('status');
                $table->index('email');
            });
        }

        // 3. conversions - affiliate conversions
        if (!Schema::hasTable('conversions')) {
            Schema::create('conversions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('affiliate_id')->index();
                $table->unsignedBigInteger('referral_link_id')->nullable()->index();
                $table->string('type')->default('sale'); // sale, signup, etc.
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 3)->default('USD');
                $table->timestamp('converted_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 4. coupons - for discount coupons
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('type')->default('percentage'); // percentage, fixed
                $table->decimal('value', 10, 2);
                $table->integer('max_uses')->default(0); // 0 = unlimited
                $table->integer('used_count')->default(0);
                $table->timestamp('valid_from')->nullable();
                $table->timestamp('valid_until')->nullable();
                $table->decimal('minimum_amount', 10, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('code');
                $table->index('is_active');
            });
        }

        // 5. glossaries - for translation glossaries
        if (!Schema::hasTable('glossaries')) {
            Schema::create('glossaries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('project_id')->nullable()->index();
                $table->string('term');
                $table->string('translation');
                $table->string('language_pair'); // e.g., en-ar
                $table->text('context')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('case_sensitive')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('language_pair');
            });
        }

        // 6. payouts - affiliate payouts
        if (!Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('affiliate_id')->index();
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('period')->nullable(); // e.g., 2024-01
                $table->string('status')->default('pending'); // pending, processing, completed, failed
                $table->json('details')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        // 7. plan_comparisons - for comparing plan features
        if (!Schema::hasTable('plan_comparisons')) {
            Schema::create('plan_comparisons', function (Blueprint $table) {
                $table->id();
                $table->string('feature_name');
                $table->text('feature_description')->nullable();
                $table->string('category', 100)->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                $table->index('category');
            });
        }

        // 8. plan_features - features for each plan
        if (!Schema::hasTable('plan_features')) {
            Schema::create('plan_features', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id')->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('value')->nullable();
                $table->boolean('is_unlimited')->default(false);
                $table->integer('order_column')->default(0);
                $table->timestamps();
            });
        }

        // 9. referral_links - affiliate referral links
        if (!Schema::hasTable('referral_links')) {
            Schema::create('referral_links', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('affiliate_id')->index();
                $table->string('slug')->unique();
                $table->string('destination_url');
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 10. translation_memories - for storing translation memories
        if (!Schema::hasTable('translation_memories')) {
            Schema::create('translation_memories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('source_language_id')->nullable()->index();
                $table->unsignedBigInteger('target_language_id')->nullable()->index();
                $table->text('source_text');
                $table->text('target_text');
                $table->text('context')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 11. webhooks - user/company webhooks
        if (!Schema::hasTable('webhooks')) {
            Schema::create('webhooks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('url');
                $table->json('events')->nullable();
                $table->string('secret')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_triggered_at')->nullable();
                $table->timestamps();
            });
        }

        // 12. webhook_endpoints - affiliate webhook endpoints
        if (!Schema::hasTable('webhook_endpoints')) {
            Schema::create('webhook_endpoints', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('affiliate_id')->index();
                $table->string('url');
                $table->json('events')->nullable();
                $table->string('secret')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_endpoints');
        Schema::dropIfExists('webhooks');
        Schema::dropIfExists('translation_memories');
        Schema::dropIfExists('referral_links');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plan_comparisons');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('glossaries');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('conversions');
        Schema::dropIfExists('contact_forms');
        Schema::dropIfExists('commissions');
    }
};
