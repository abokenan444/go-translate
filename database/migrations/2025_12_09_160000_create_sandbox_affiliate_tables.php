<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sandbox Instances Table
        if (!Schema::hasTable('sandbox_instances')) {
            Schema::create('sandbox_instances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('company_name');
                $table->string('api_key', 64)->unique();
                $table->string('api_secret', 64);
                $table->string('status', 50)->default('active'); // active, expired, suspended
                $table->timestamp('expires_at')->nullable();
                $table->integer('rate_limit')->default(100); // requests per minute
                $table->integer('requests_count')->default(0);
                $table->timestamp('last_request_at')->nullable();
                $table->text('allowed_domains')->nullable(); // JSON array
                $table->text('webhook_url')->nullable();
                $table->text('features')->nullable(); // JSON array of enabled features
                $table->timestamps();
                
                $table->index(['user_id', 'status']);
                $table->index('api_key');
            });
        }

        // API Logs Table
        if (!Schema::hasTable('api_logs')) {
            Schema::create('api_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sandbox_id')->constrained('sandbox_instances')->onDelete('cascade');
                $table->string('endpoint');
                $table->string('method', 10);
                $table->text('request_payload')->nullable();
                $table->text('response_payload')->nullable();
                $table->integer('status_code');
                $table->integer('response_time')->nullable(); // milliseconds
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('created_at');
                
                $table->index(['sandbox_id', 'created_at']);
                $table->index('endpoint');
            });
        }

        // Affiliates Table
        if (!Schema::hasTable('affiliates')) {
            Schema::create('affiliates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('referral_code', 50)->unique();
                $table->decimal('commission_rate', 5, 2)->default(10.00); // percentage
                $table->decimal('total_earnings', 10, 2)->default(0);
                $table->decimal('pending_earnings', 10, 2)->default(0);
                $table->decimal('paid_earnings', 10, 2)->default(0);
                $table->string('status', 50)->default('active'); // active, suspended, inactive
                $table->string('payment_method')->nullable(); // paypal, bank_transfer
                $table->text('payment_details')->nullable(); // JSON
                $table->timestamps();
                
                $table->index('referral_code');
                $table->index(['user_id', 'status']);
            });
        }

        // Referrals Table
        if (!Schema::hasTable('referrals')) {
            Schema::create('referrals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
                $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
                $table->decimal('commission_amount', 10, 2)->default(0);
                $table->string('status', 50)->default('pending'); // pending, approved, paid
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
                
                $table->index(['affiliate_id', 'status']);
                $table->index('referred_user_id');
            });
        }

        // Partners Table
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type', 100); // law_firm, translation_agency, university, corporate
                $table->string('contact_name')->nullable();
                $table->string('contact_email');
                $table->string('contact_phone')->nullable();
                $table->boolean('white_label')->default(false);
                $table->decimal('discount_rate', 5, 2)->default(0); // percentage
                $table->text('custom_domain')->nullable();
                $table->text('logo_url')->nullable();
                $table->string('status', 50)->default('active'); // active, inactive, pending
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index('type');
                $table->index('status');
            });
        }

        // Document Reviews Table
        if (!Schema::hasTable('document_reviews')) {
            Schema::create('document_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('official_document_id')->constrained()->onDelete('cascade');
                $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
                $table->string('status', 50)->default('pending'); // pending, in_review, approved, changes_required, rejected
                $table->text('notes')->nullable();
                $table->text('changes_requested')->nullable(); // JSON array
                $table->integer('quality_score')->nullable(); // 1-100
                $table->timestamp('started_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                
                $table->index(['official_document_id', 'status']);
                $table->index(['reviewer_id', 'status']);
            });
        }

        // Review Logs Table
        if (!Schema::hasTable('review_logs')) {
            Schema::create('review_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_review_id')->constrained()->onDelete('cascade');
                $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
                $table->string('action', 100); // started_review, added_comment, requested_changes, approved, rejected
                $table->text('details')->nullable();
                $table->timestamp('created_at');
                
                $table->index('document_review_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('review_logs');
        Schema::dropIfExists('document_reviews');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('affiliates');
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('sandbox_instances');
    }
};
