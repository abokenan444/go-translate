<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('contact_name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('country');
                $table->string('website')->nullable();
                $table->text('description')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
                $table->string('api_key', 64)->unique();
                $table->string('api_secret', 128);
                $table->decimal('commission_rate', 5, 2)->default(10.00);
                $table->decimal('balance', 10, 2)->default(0);
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('partner_users')) {
            Schema::create('partner_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['admin', 'user'])->default('user');
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('partner_orders')) {
            Schema::create('partner_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('order_number')->unique();
                $table->string('service_type');
                $table->string('source_language', 10);
                $table->string('target_language', 10);
                $table->text('source_text')->nullable();
                $table->text('translated_text')->nullable();
                $table->integer('word_count')->default(0);
                $table->decimal('price', 10, 2);
                $table->decimal('commission', 10, 2);
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('partner_commissions')) {
            Schema::create('partner_commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->foreignId('partner_order_id')->nullable()->constrained()->onDelete('set null');
                $table->decimal('amount', 10, 2);
                $table->enum('type', ['earned', 'bonus', 'penalty', 'adjustment']);
                $table->text('description')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('partner_payouts')) {
            Schema::create('partner_payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
                $table->string('payment_method');
                $table->json('payment_details')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('partner_white_labels')) {
            Schema::create('partner_white_labels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('domain')->unique();
                $table->string('logo_url')->nullable();
                $table->string('primary_color', 7)->default('#6B46C1');
                $table->string('secondary_color', 7)->default('#10B981');
                $table->json('custom_css')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_number')->unique();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('document_id')->nullable();
                $table->foreignId('translator_id')->nullable();
                $table->string('source_language', 10);
                $table->string('target_language', 10);
                $table->date('issue_date');
                $table->date('expiry_date');
                $table->string('verification_code', 16)->unique();
                $table->enum('status', ['valid', 'revoked', 'expired'])->default('valid');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('partner_white_labels');
        Schema::dropIfExists('partner_payouts');
        Schema::dropIfExists('partner_commissions');
        Schema::dropIfExists('partner_orders');
        Schema::dropIfExists('partner_users');
        Schema::dropIfExists('partners');
    }
};
