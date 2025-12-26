<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Official Documents Translation System.
     */
    public function up(): void
    {
        // Main documents table
        if (!Schema::hasTable('official_documents')) {
            Schema::create('official_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('document_type'); // birth_certificate, passport, contract, etc.
                $table->string('source_language', 10);
                $table->string('target_language', 10);
                $table->string('original_file_path');
                $table->string('original_hash');
                $table->string('status')->default('pending'); 
                // pending, awaiting_payment, paid, processing, reviewing, completed, failed
                $table->unsignedBigInteger('order_id')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['user_id', 'status']);
                $table->index('status');
            });
        }

        // Payment/Order table
        if (!Schema::hasTable('official_translation_orders')) {
            Schema::create('official_translation_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('document_id')->constrained('official_documents')->onDelete('cascade');
                $table->integer('page_count')->default(1);
                $table->integer('word_count')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('currency', 3)->default('EUR');
                $table->string('payment_provider')->default('stripe');
                $table->string('payment_status')->default('unpaid'); 
                // unpaid, pending, paid, failed, refunded
                $table->string('payment_reference')->nullable(); // stripe session/payment_intent id
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'payment_status']);
                $table->index('payment_status');
            });
        }

        // Translations table
        if (!Schema::hasTable('document_translations')) {
            Schema::create('document_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('official_document_id')->constrained('official_documents')->onDelete('cascade');
                $table->string('translated_file_path')->nullable();
                $table->string('certified_file_path')->nullable();
                $table->text('layout_data')->nullable(); // JSON for OCR layout info
                $table->string('ai_engine_version')->nullable();
                $table->integer('quality_score')->nullable();
                $table->boolean('reviewed_by_human')->default(false);
                $table->timestamps();

                $table->index('official_document_id');
            });
        }

        // Certificates table
        if (!Schema::hasTable('document_certificates')) {
            Schema::create('document_certificates', function (Blueprint $table) {
                $table->id();
                $table->string('cert_id')->unique(); // CT-YYYY-MM-XXXXXXXX
                $table->foreignId('document_id')->constrained('official_documents')->onDelete('cascade');
                $table->string('original_hash');
                $table->string('translated_hash');
                $table->string('status')->default('valid'); // valid, revoked, expired
                $table->timestamp('issued_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->text('qr_code_path')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index('cert_id');
                $table->index(['document_id', 'status']);
            });
        }

        // Human reviews table
        if (!Schema::hasTable('document_reviews')) {
            Schema::create('document_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('official_documents')->onDelete('cascade');
                $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('pending'); // pending, in_review, approved, changes_requested, rejected
                $table->text('review_notes')->nullable();
                $table->integer('quality_rating')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->index(['document_id', 'status']);
                $table->index('reviewer_id');
            });
        }

        // Add foreign key for order_id in official_documents
        if (Schema::hasTable('official_documents') && Schema::hasTable('official_translation_orders')) {
            try {
                Schema::table('official_documents', function (Blueprint $table) {
                    $table->foreign('order_id')->references('id')->on('official_translation_orders')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key may already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_documents', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('document_reviews');
        Schema::dropIfExists('document_certificates');
        Schema::dropIfExists('document_translations');
        Schema::dropIfExists('official_translation_orders');
        Schema::dropIfExists('official_documents');
    }
};
