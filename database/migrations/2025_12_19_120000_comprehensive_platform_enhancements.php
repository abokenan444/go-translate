<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only create tables/columns if they don't exist to avoid conflicts
        
        // 1. Partner Metrics and Performance
        if (Schema::hasTable('partners')) {
            Schema::table('partners', function (Blueprint $table) {
                if (!Schema::hasColumn('partners', 'total_revenue')) {
                    $table->decimal('total_revenue', 15, 2)->default(0)->after('status');
                }
                if (!Schema::hasColumn('partners', 'commission_rate')) {
                    $table->decimal('commission_rate', 5, 2)->default(10.00)->after('total_revenue');
                }
                if (!Schema::hasColumn('partners', 'pending_payout')) {
                    $table->decimal('pending_payout', 15, 2)->default(0)->after('commission_rate');
                }
                if (!Schema::hasColumn('partners', 'total_paid')) {
                    $table->decimal('total_paid', 15, 2)->default(0)->after('pending_payout');
                }
                if (!Schema::hasColumn('partners', 'conversion_rate')) {
                    $table->decimal('conversion_rate', 5, 2)->default(0)->after('total_paid');
                }
                if (!Schema::hasColumn('partners', 'certification_level')) {
                    $table->string('certification_level')->nullable()->after('conversion_rate');
                }
                if (!Schema::hasColumn('partners', 'certified_at')) {
                    $table->timestamp('certified_at')->nullable()->after('certification_level');
                }
                if (!Schema::hasColumn('partners', 'is_public')) {
                    $table->boolean('is_public')->default(true)->after('certified_at');
                }
                if (!Schema::hasColumn('partners', 'specializations')) {
                    $table->json('specializations')->nullable()->after('is_public');
                }
                if (!Schema::hasColumn('partners', 'language_pairs')) {
                    $table->json('language_pairs')->nullable()->after('specializations');
                }
                if (!Schema::hasColumn('partners', 'overall_rating')) {
                    $table->decimal('overall_rating', 3, 2)->default(0)->after('language_pairs');
                }
                if (!Schema::hasColumn('partners', 'quality_rating')) {
                    $table->decimal('quality_rating', 3, 2)->default(0)->after('overall_rating');
                }
                if (!Schema::hasColumn('partners', 'speed_rating')) {
                    $table->decimal('speed_rating', 3, 2)->default(0)->after('quality_rating');
                }
                if (!Schema::hasColumn('partners', 'communication_rating')) {
                    $table->decimal('communication_rating', 3, 2)->default(0)->after('speed_rating');
                }
                if (!Schema::hasColumn('partners', 'total_reviews')) {
                    $table->integer('total_reviews')->default(0)->after('communication_rating');
                }
                if (!Schema::hasColumn('partners', 'total_projects')) {
                    $table->integer('total_projects')->default(0)->after('total_reviews');
                }
                if (!Schema::hasColumn('partners', 'completed_projects')) {
                    $table->integer('completed_projects')->default(0)->after('total_projects');
                }
                if (!Schema::hasColumn('partners', 'success_rate')) {
                    $table->decimal('success_rate', 5, 2)->default(0)->after('completed_projects');
                }
                if (!Schema::hasColumn('partners', 'is_verified')) {
                    $table->boolean('is_verified')->default(false)->after('success_rate');
                }
            });
        }

        // 2. Partner Payouts
        if (!Schema::hasTable('partner_payouts')) {
            Schema::create('partner_payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 15, 2);
                $table->string('status')->default('pending');
                $table->string('payment_method')->nullable();
                $table->text('payment_details')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['partner_id', 'status']);
            });
        }

        // 3. Document Disputes
        if (!Schema::hasTable('document_disputes')) {
            Schema::create('document_disputes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->foreignId('raised_by')->constrained('users')->onDelete('cascade');
                $table->string('dispute_type');
                $table->text('description');
                $table->string('status')->default('open');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->text('resolution')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->index(['document_id', 'status']);
            });
        }

        // 4. Document Classifications & Data Retention
        if (!Schema::hasTable('document_classifications')) {
            Schema::create('document_classifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->string('classification_level')->default('internal');
                $table->integer('retention_years')->default(7);
                $table->timestamp('retention_expires_at')->nullable();
                $table->boolean('auto_purge_enabled')->default(true);
                $table->timestamp('last_reviewed_at')->nullable();
                $table->timestamps();

                $table->index(['document_id', 'retention_expires_at']);
            });
        }

        // 5. Evidence Chain (Blockchain-like audit trail)
        if (!Schema::hasTable('evidence_chain')) {
            Schema::create('evidence_chain', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->string('event_type');
                $table->text('event_data');
                $table->string('actor_type')->nullable();
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->text('metadata')->nullable();
                $table->string('hash', 64)->nullable();
                $table->string('previous_hash', 64)->nullable();
                $table->timestamps();

                $table->index(['document_id', 'created_at']);
                $table->index('event_type');
            });
        }

        // 6. Government Verifications
        if (!Schema::hasTable('government_verifications')) {
            Schema::create('government_verifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->string('government_id');
                $table->string('verification_status');
                $table->json('verification_data')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->string('verified_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['document_id', 'government_id']);
            });
        }

        // 7. Translator Performance
        if (!Schema::hasTable('translator_performance')) {
            Schema::create('translator_performance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('translator_id')->constrained('users')->onDelete('cascade');
                $table->decimal('overall_score', 5, 2)->default(0);
                $table->decimal('quality_score', 5, 2)->default(0);
                $table->decimal('speed_score', 5, 2)->default(0);
                $table->decimal('reliability_score', 5, 2)->default(0);
                $table->decimal('communication_score', 5, 2)->default(0);
                $table->string('level')->default('Beginner');
                $table->timestamps();

                $table->index(['translator_id', 'overall_score']);
            });
        }

        // 8. Device Tokens for Push Notifications
        if (!Schema::hasTable('device_tokens')) {
            Schema::create('device_tokens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('token')->unique();
                $table->string('platform'); // ios, android, web
                $table->string('device_id')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'platform']);
            });
        }

        // 9. Add columns to documents if not exists
        if (Schema::hasTable('documents') || Schema::hasTable('official_documents')) {
            $tableName = Schema::hasTable('official_documents') ? 'official_documents' : 'documents';
            
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'government_verified')) {
                    $table->boolean('government_verified')->default(false)->after('status');
                }
                if (!Schema::hasColumn($tableName, 'verification_date')) {
                    $table->timestamp('verification_date')->nullable()->after('government_verified');
                }
                if (!Schema::hasColumn($tableName, 'assigned_at')) {
                    $table->timestamp('assigned_at')->nullable()->after('verification_date');
                }
                if (!Schema::hasColumn($tableName, 'revision_count')) {
                    $table->integer('revision_count')->default(0)->after('assigned_at');
                }
                if (!Schema::hasColumn($tableName, 'rating')) {
                    $table->decimal('rating', 3, 2)->nullable()->after('revision_count');
                }
            });
        }

        // 10. Document Assignments table
        if (!Schema::hasTable('document_assignments')) {
            Schema::create('document_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->foreignId('translator_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();

                $table->index(['document_id', 'translator_id', 'status']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('document_assignments');
        Schema::dropIfExists('device_tokens');
        Schema::dropIfExists('translator_performance');
        Schema::dropIfExists('government_verifications');
        Schema::dropIfExists('evidence_chain');
        Schema::dropIfExists('document_classifications');
        Schema::dropIfExists('document_disputes');
        Schema::dropIfExists('partner_payouts');

        // Remove columns from partners
        if (Schema::hasTable('partners')) {
            Schema::table('partners', function (Blueprint $table) {
                $columns = [
                    'total_revenue', 'commission_rate', 'pending_payout', 'total_paid',
                    'conversion_rate', 'certification_level', 'certified_at', 'is_public',
                    'specializations', 'language_pairs', 'overall_rating', 'quality_rating',
                    'speed_rating', 'communication_rating', 'total_reviews', 'total_projects',
                    'completed_projects', 'success_rate', 'is_verified'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('partners', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Remove columns from documents/official_documents
        $tableName = Schema::hasTable('official_documents') ? 'official_documents' : 'documents';
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $columns = ['government_verified', 'verification_date', 'assigned_at', 'revision_count', 'rating'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
