<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Government Verification Requests Table
        if (Schema::hasTable('government_verifications')) {
            // Already created by another migration, skip creation
        } else {
        Schema::create('government_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Entity Information
            $table->string('entity_name'); // اسم الجهة الرسمي
            $table->string('entity_name_local')->nullable(); // الاسم بالغة المحلية
            $table->string('entity_type'); // ministry, embassy, municipality, agency, court, etc.
            $table->string('country_code', 2); // ISO country code
            $table->string('department')->nullable(); // القسم/الإدارة
            $table->string('official_website')->nullable();
            
            // Contact Person
            $table->string('contact_name');
            $table->string('contact_position'); // المنصب الوظيفي
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('employee_id')->nullable(); // رقم الموظف
            
            // Verification Status
            $table->enum('status', [
                'pending',           // في انتظار المراجعة
                'under_review',      // قيد المراجعة
                'info_requested',    // مطلوب معلومات إضافية
                'approved',          // موافق عليه
                'rejected',          // مرفوض
                'suspended',         // موقوف
                'revoked'            // ملغي
            ])->default('pending');
            
            // Review Information
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('info_request_message')->nullable();
            
            // Legal Acceptance
            $table->boolean('legal_disclaimer_accepted')->default(false);
            $table->timestamp('legal_accepted_at')->nullable();
            $table->string('legal_accepted_ip')->nullable();
            $table->text('legal_disclaimer_text')->nullable();
            
            // Verification Level
            $table->enum('verification_level', [
                'basic',      // تحقق أساسي (إيميل فقط)
                'standard',   // تحقق قياسي (إيميل + وثائق)
                'enhanced',   // تحقق معزز (إيميل + وثائق + اتصال)
                'premium'     // تحقق متميز (MoU رسمي)
            ])->default('basic');
            
            // Access Permissions
            $table->json('granted_permissions')->nullable();
            $table->boolean('can_use_seal')->default(false);
            $table->boolean('can_issue_certificates')->default(false);
            $table->boolean('can_verify_documents')->default(true);
            $table->integer('monthly_document_limit')->default(100);
            
            // MoU / Agreement
            $table->string('mou_reference')->nullable();
            $table->date('mou_start_date')->nullable();
            $table->date('mou_end_date')->nullable();
            $table->string('mou_document_path')->nullable();
            
            // Risk Assessment
            $table->integer('risk_score')->default(0); // 0-100
            $table->json('risk_factors')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'created_at']);
            $table->index('country_code');
            $table->index('entity_type');
        });
        } // End of else block for government_verifications

        // 2. Government Documents Table
        if (!Schema::hasTable('government_documents')) {
        Schema::create('government_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_id')->constrained('government_verifications')->onDelete('cascade');
            
            $table->enum('document_type', [
                'employee_id',           // بطاقة الموظف
                'authorization_letter',  // خطاب تفويض
                'business_card',         // بطاقة عمل
                'official_letter',       // خطاب رسمي
                'mou_agreement',         // اتفاقية تعاون
                'delegation_letter',     // خطاب تفويض
                'id_document',           // وثيقة هوية
                'other'
            ]);
            
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->string('file_hash')->nullable(); // SHA256 للتحقق
            
            // Verification
            $table->enum('verification_status', [
                'pending',
                'verified',
                'rejected',
                'expired'
            ])->default('pending');
            
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            $table->date('expiry_date')->nullable();
            $table->boolean('is_primary')->default(false);
            
            $table->timestamps();
        });
        } // End of government_documents guard

        // 3. Government Audit Logs
        if (!Schema::hasTable('government_audit_logs')) {
        Schema::create('government_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_id')->constrained('government_verifications')->onDelete('cascade');
            $table->foreignId('performed_by')->constrained('users');
            
            $table->string('action'); // created, updated, approved, rejected, suspended, etc.
            $table->string('action_category'); // status_change, document_review, permission_change
            
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('notes')->nullable();
            
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->index(['verification_id', 'created_at']);
            $table->index('action');
        });
        } // End of government_audit_logs guard

        // 4. Government Email Domains (Whitelist)
        if (!Schema::hasTable('government_email_domains')) {
        Schema::create('government_email_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique(); // e.g., gov.sa, gouv.fr
            $table->string('country_code', 2);
            $table->string('country_name');
            $table->string('entity_type')->nullable(); // general, ministry, embassy
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('country_code');
            $table->index('is_active');
        });
        } // End of government_email_domains guard

        // 5. Government Communication Log
        if (!Schema::hasTable('government_communications')) {
        Schema::create('government_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_id')->constrained('government_verifications')->onDelete('cascade');
            $table->foreignId('sent_by')->constrained('users');
            
            $table->enum('type', ['email', 'phone', 'meeting', 'letter', 'internal_note']);
            $table->enum('direction', ['outgoing', 'incoming', 'internal']);
            
            $table->string('subject');
            $table->text('content');
            $table->json('attachments')->nullable();
            
            $table->string('recipient_email')->nullable();
            $table->string('recipient_name')->nullable();
            
            $table->boolean('requires_response')->default(false);
            $table->timestamp('response_deadline')->nullable();
            $table->boolean('response_received')->default(false);
            
            $table->timestamps();
        });
        } // End of government_communications guard

        // 6. Add columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'account_type')) {
                $table->enum('account_type', ['individual', 'business', 'government', 'partner'])->default('individual')->after('email');
            }
            if (!Schema::hasColumn('users', 'is_government_verified')) {
                $table->boolean('is_government_verified')->default(false)->after('account_type');
            }
            if (!Schema::hasColumn('users', 'government_verified_at')) {
                $table->timestamp('government_verified_at')->nullable()->after('is_government_verified');
            }
            if (!Schema::hasColumn('users', 'government_verification_id')) {
                $table->foreignId('government_verification_id')->nullable()->after('government_verified_at');
            }
            if (!Schema::hasColumn('users', 'government_badge')) {
                $table->string('government_badge')->nullable()->after('government_verification_id');
            }
            if (!Schema::hasColumn('users', 'government_access_level')) {
                $table->enum('government_access_level', ['none', 'basic', 'standard', 'enhanced', 'premium'])->default('none')->after('government_badge');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_communications');
        Schema::dropIfExists('government_email_domains');
        Schema::dropIfExists('government_audit_logs');
        Schema::dropIfExists('government_documents');
        Schema::dropIfExists('government_verifications');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'is_government_verified',
                'government_verified_at',
                'government_verification_id',
                'government_badge',
                'government_access_level'
            ]);
        });
    }
};
