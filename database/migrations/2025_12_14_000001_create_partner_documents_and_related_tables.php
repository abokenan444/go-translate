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
        // 1. Partner Documents Table
        if (!Schema::hasTable('partner_documents')) {
            Schema::create('partner_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('cts_partners')->onDelete('cascade');
            $table->string('document_type'); // license, certification, insurance, etc.
            $table->string('document_number')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->integer('file_size');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->string('status')->default('active'); // active, expired, revoked
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['partner_id', 'document_type']);
                $table->index('status');
            });
        }

        // 2. Partner Users Table (for partner staff accounts)
        if (!Schema::hasTable('partner_users')) {
            Schema::create('partner_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('cts_partners')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role'); // admin, translator, reviewer, manager
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->unique(['partner_id', 'user_id']);
                $table->index('role');
            });
        }

        // 3. Partner Orders Table (certified translation orders)
        if (!Schema::hasTable('partner_orders')) {
            Schema::create('partner_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('partner_id')->constrained('cts_partners')->onDelete('cascade');
            $table->foreignId('translation_id')->nullable()->constrained('translations')->onDelete('set null');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('service_type'); // certified_translation, notarization, apostille
            $table->string('source_language', 10);
            $table->string('target_language', 10);
            $table->integer('page_count')->default(1);
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status'); // pending, in_progress, completed, delivered, cancelled
            $table->timestamp('ordered_at');
            $table->timestamp('deadline')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['partner_id', 'status']);
                $table->index('order_number');
                $table->index('client_id');
            });
        }

        // 4. Partner Print Jobs Table
        if (!Schema::hasTable('partner_print_jobs')) {
            Schema::create('partner_print_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('cts_partners')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('partner_orders')->onDelete('set null');
            $table->foreignId('certificate_id')->nullable()->constrained('cts_certificates')->onDelete('set null');
            $table->string('job_number')->unique();
            $table->string('print_type'); // certificate, translation, both
            $table->integer('copies')->default(1);
            $table->string('paper_size')->default('A4');
            $table->string('color_mode')->default('color'); // color, grayscale
            $table->boolean('double_sided')->default(false);
            $table->string('binding_type')->nullable(); // none, stapled, bound
            $table->string('status'); // pending, printing, completed, shipped
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['partner_id', 'status']);
                $table->index('job_number');
            });
        }

        // 5. Shipping Labels Table
        if (!Schema::hasTable('shipping_labels')) {
            Schema::create('shipping_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('cts_partners')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('partner_orders')->onDelete('set null');
            $table->foreignId('print_job_id')->nullable()->constrained('partner_print_jobs')->onDelete('set null');
            $table->string('tracking_number')->unique();
            $table->string('carrier'); // DHL, FedEx, UPS, etc.
            $table->string('service_level'); // standard, express, overnight
            
            // Sender (Platform)
            $table->string('sender_name')->default('Cultural Translate');
            $table->string('sender_company')->default('Cultural Translate Platform');
            $table->text('sender_address');
            $table->string('sender_city');
            $table->string('sender_state')->nullable();
            $table->string('sender_postal_code');
            $table->string('sender_country');
            $table->string('sender_phone');
            $table->string('sender_email');
            
            // Recipient
            $table->string('recipient_name');
            $table->string('recipient_company')->nullable();
            $table->text('recipient_address');
            $table->string('recipient_city');
            $table->string('recipient_state')->nullable();
            $table->string('recipient_postal_code');
            $table->string('recipient_country');
            $table->string('recipient_phone');
            $table->string('recipient_email')->nullable();
            
            // Package details
            $table->decimal('weight', 8, 2); // in kg
            $table->decimal('length', 8, 2)->nullable(); // in cm
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->string('package_type')->default('envelope');
            $table->text('contents_description');
            $table->decimal('declared_value', 10, 2)->nullable();
            
            // Shipping details
            $table->decimal('shipping_cost', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status'); // created, printed, shipped, in_transit, delivered, returned
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Label file
            $table->string('label_file_path')->nullable();
            $table->json('tracking_events')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('tracking_number');
                $table->index(['partner_id', 'status']);
                $table->index('carrier');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_labels');
        Schema::dropIfExists('partner_print_jobs');
        Schema::dropIfExists('partner_orders');
        Schema::dropIfExists('partner_users');
        Schema::dropIfExists('partner_documents');
    }
};
