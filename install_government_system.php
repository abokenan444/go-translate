<?php
/**
 * Government Protection System - Complete Installation Script
 * نظام حماية الحسابات الحكومية - سكريبت التثبيت الكامل
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "🏛️ Government Protection System Installation\n";
echo "=============================================\n\n";

// =====================================================
// 1. CREATE TABLES
// =====================================================
echo "📊 Step 1: Creating Database Tables...\n";

// Government Verifications Table
if (!Schema::hasTable('government_verifications')) {
    Schema::create('government_verifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Entity Information
        $table->string('entity_name');
        $table->string('entity_name_local')->nullable();
        $table->string('entity_type');
        $table->string('country_code', 2);
        $table->string('department')->nullable();
        $table->string('official_website')->nullable();
        
        // Contact Person
        $table->string('contact_name');
        $table->string('contact_position');
        $table->string('contact_email');
        $table->string('contact_phone')->nullable();
        $table->string('employee_id')->nullable();
        
        // Status
        $table->enum('status', [
            'pending', 'under_review', 'info_requested',
            'approved', 'rejected', 'suspended', 'revoked'
        ])->default('pending');
        
        // Review
        $table->unsignedBigInteger('reviewed_by')->nullable();
        $table->timestamp('reviewed_at')->nullable();
        $table->text('review_notes')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->text('info_request_message')->nullable();
        
        // Legal
        $table->boolean('legal_disclaimer_accepted')->default(false);
        $table->timestamp('legal_accepted_at')->nullable();
        $table->string('legal_accepted_ip')->nullable();
        $table->text('legal_disclaimer_text')->nullable();
        
        // Verification Level
        $table->enum('verification_level', ['basic', 'standard', 'enhanced', 'premium'])->default('basic');
        
        // Permissions
        $table->json('granted_permissions')->nullable();
        $table->boolean('can_use_seal')->default(false);
        $table->boolean('can_issue_certificates')->default(false);
        $table->boolean('can_verify_documents')->default(true);
        $table->integer('monthly_document_limit')->default(100);
        
        // MoU
        $table->string('mou_reference')->nullable();
        $table->date('mou_start_date')->nullable();
        $table->date('mou_end_date')->nullable();
        $table->string('mou_document_path')->nullable();
        
        // Risk
        $table->integer('risk_score')->default(0);
        $table->json('risk_factors')->nullable();
        
        $table->timestamps();
        $table->softDeletes();
        
        $table->index(['status', 'created_at']);
        $table->index('country_code');
    });
    echo "  ✅ Created: government_verifications\n";
} else {
    echo "  ⏭️ Exists: government_verifications\n";
}

// Government Documents Table
if (!Schema::hasTable('government_documents')) {
    Schema::create('government_documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('verification_id')->constrained('government_verifications')->onDelete('cascade');
        
        $table->enum('document_type', [
            'employee_id', 'authorization_letter', 'business_card',
            'official_letter', 'mou_agreement', 'delegation_letter',
            'id_document', 'other'
        ]);
        
        $table->string('file_path');
        $table->string('original_name');
        $table->string('mime_type');
        $table->integer('file_size');
        $table->string('file_hash')->nullable();
        
        $table->enum('verification_status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
        $table->text('verification_notes')->nullable();
        $table->unsignedBigInteger('verified_by')->nullable();
        $table->timestamp('verified_at')->nullable();
        
        $table->date('expiry_date')->nullable();
        $table->boolean('is_primary')->default(false);
        
        $table->timestamps();
    });
    echo "  ✅ Created: government_documents\n";
} else {
    echo "  ⏭️ Exists: government_documents\n";
}

// Government Audit Logs Table
if (!Schema::hasTable('government_audit_logs')) {
    Schema::create('government_audit_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('verification_id')->constrained('government_verifications')->onDelete('cascade');
        $table->unsignedBigInteger('performed_by');
        
        $table->string('action');
        $table->string('action_category');
        
        $table->json('old_values')->nullable();
        $table->json('new_values')->nullable();
        $table->text('notes')->nullable();
        
        $table->string('ip_address')->nullable();
        $table->string('user_agent')->nullable();
        
        $table->timestamps();
        
        $table->index(['verification_id', 'created_at']);
    });
    echo "  ✅ Created: government_audit_logs\n";
} else {
    echo "  ⏭️ Exists: government_audit_logs\n";
}

// Government Email Domains Table
if (!Schema::hasTable('government_email_domains')) {
    Schema::create('government_email_domains', function (Blueprint $table) {
        $table->id();
        $table->string('domain')->unique();
        $table->string('country_code', 2);
        $table->string('country_name');
        $table->string('entity_type')->nullable();
        $table->boolean('is_verified')->default(true);
        $table->boolean('is_active')->default(true);
        $table->text('notes')->nullable();
        $table->timestamps();
        
        $table->index('country_code');
        $table->index('is_active');
    });
    echo "  ✅ Created: government_email_domains\n";
} else {
    echo "  ⏭️ Exists: government_email_domains\n";
}

// Government Communications Table
if (!Schema::hasTable('government_communications')) {
    Schema::create('government_communications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('verification_id')->constrained('government_verifications')->onDelete('cascade');
        $table->unsignedBigInteger('sent_by');
        
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
    echo "  ✅ Created: government_communications\n";
} else {
    echo "  ⏭️ Exists: government_communications\n";
}

// =====================================================
// 2. ADD USER COLUMNS
// =====================================================
echo "\n👤 Step 2: Adding User Table Columns...\n";

$userColumns = [
    'account_type' => "ENUM('individual', 'business', 'government', 'partner') DEFAULT 'individual'",
    'is_government_verified' => "BOOLEAN DEFAULT FALSE",
    'government_verified_at' => "TIMESTAMP NULL",
    'government_verification_id' => "BIGINT UNSIGNED NULL",
    'government_badge' => "VARCHAR(50) NULL",
    'government_access_level' => "ENUM('none', 'basic', 'standard', 'enhanced', 'premium') DEFAULT 'none'"
];

foreach ($userColumns as $column => $definition) {
    if (!Schema::hasColumn('users', $column)) {
        DB::statement("ALTER TABLE users ADD COLUMN {$column} {$definition}");
        echo "  ✅ Added: users.{$column}\n";
    } else {
        echo "  ⏭️ Exists: users.{$column}\n";
    }
}

// =====================================================
// 3. SEED GOVERNMENT EMAIL DOMAINS
// =====================================================
echo "\n📧 Step 3: Seeding Government Email Domains...\n";

$domains = [
    // General Government
    ['domain' => 'gov', 'country_code' => 'XX', 'country_name' => 'International', 'entity_type' => 'general'],
    
    // United States
    ['domain' => 'gov', 'country_code' => 'US', 'country_name' => 'United States', 'entity_type' => 'federal'],
    ['domain' => 'state.gov', 'country_code' => 'US', 'country_name' => 'United States', 'entity_type' => 'state_dept'],
    ['domain' => 'mail.house.gov', 'country_code' => 'US', 'country_name' => 'United States', 'entity_type' => 'congress'],
    
    // United Kingdom
    ['domain' => 'gov.uk', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'entity_type' => 'general'],
    ['domain' => 'parliament.uk', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'entity_type' => 'parliament'],
    ['domain' => 'nhs.uk', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'entity_type' => 'healthcare'],
    ['domain' => 'police.uk', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'entity_type' => 'police'],
    
    // European Union
    ['domain' => 'europa.eu', 'country_code' => 'EU', 'country_name' => 'European Union', 'entity_type' => 'general'],
    
    // France
    ['domain' => 'gouv.fr', 'country_code' => 'FR', 'country_name' => 'France', 'entity_type' => 'general'],
    ['domain' => 'diplomatie.gouv.fr', 'country_code' => 'FR', 'country_name' => 'France', 'entity_type' => 'foreign_affairs'],
    
    // Germany
    ['domain' => 'bund.de', 'country_code' => 'DE', 'country_name' => 'Germany', 'entity_type' => 'federal'],
    ['domain' => 'bundesregierung.de', 'country_code' => 'DE', 'country_name' => 'Germany', 'entity_type' => 'federal'],
    
    // Netherlands
    ['domain' => 'gov.nl', 'country_code' => 'NL', 'country_name' => 'Netherlands', 'entity_type' => 'general'],
    ['domain' => 'rijksoverheid.nl', 'country_code' => 'NL', 'country_name' => 'Netherlands', 'entity_type' => 'general'],
    ['domain' => 'minbuza.nl', 'country_code' => 'NL', 'country_name' => 'Netherlands', 'entity_type' => 'foreign_affairs'],
    
    // Saudi Arabia
    ['domain' => 'gov.sa', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'entity_type' => 'general'],
    ['domain' => 'mofa.gov.sa', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'entity_type' => 'foreign_affairs'],
    ['domain' => 'moi.gov.sa', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'entity_type' => 'interior'],
    
    // UAE
    ['domain' => 'gov.ae', 'country_code' => 'AE', 'country_name' => 'United Arab Emirates', 'entity_type' => 'general'],
    ['domain' => 'mofaic.gov.ae', 'country_code' => 'AE', 'country_name' => 'United Arab Emirates', 'entity_type' => 'foreign_affairs'],
    
    // Qatar
    ['domain' => 'gov.qa', 'country_code' => 'QA', 'country_name' => 'Qatar', 'entity_type' => 'general'],
    ['domain' => 'mofa.gov.qa', 'country_code' => 'QA', 'country_name' => 'Qatar', 'entity_type' => 'foreign_affairs'],
    
    // Kuwait
    ['domain' => 'gov.kw', 'country_code' => 'KW', 'country_name' => 'Kuwait', 'entity_type' => 'general'],
    
    // Bahrain
    ['domain' => 'gov.bh', 'country_code' => 'BH', 'country_name' => 'Bahrain', 'entity_type' => 'general'],
    
    // Oman
    ['domain' => 'gov.om', 'country_code' => 'OM', 'country_name' => 'Oman', 'entity_type' => 'general'],
    
    // Egypt
    ['domain' => 'gov.eg', 'country_code' => 'EG', 'country_name' => 'Egypt', 'entity_type' => 'general'],
    
    // Jordan
    ['domain' => 'gov.jo', 'country_code' => 'JO', 'country_name' => 'Jordan', 'entity_type' => 'general'],
    
    // Morocco
    ['domain' => 'gov.ma', 'country_code' => 'MA', 'country_name' => 'Morocco', 'entity_type' => 'general'],
    
    // Tunisia
    ['domain' => 'gov.tn', 'country_code' => 'TN', 'country_name' => 'Tunisia', 'entity_type' => 'general'],
    
    // Canada
    ['domain' => 'gc.ca', 'country_code' => 'CA', 'country_name' => 'Canada', 'entity_type' => 'federal'],
    ['domain' => 'canada.ca', 'country_code' => 'CA', 'country_name' => 'Canada', 'entity_type' => 'federal'],
    
    // Australia
    ['domain' => 'gov.au', 'country_code' => 'AU', 'country_name' => 'Australia', 'entity_type' => 'general'],
    
    // Japan
    ['domain' => 'go.jp', 'country_code' => 'JP', 'country_name' => 'Japan', 'entity_type' => 'general'],
    
    // China
    ['domain' => 'gov.cn', 'country_code' => 'CN', 'country_name' => 'China', 'entity_type' => 'general'],
    
    // India
    ['domain' => 'gov.in', 'country_code' => 'IN', 'country_name' => 'India', 'entity_type' => 'general'],
    ['domain' => 'nic.in', 'country_code' => 'IN', 'country_name' => 'India', 'entity_type' => 'general'],
    
    // Brazil
    ['domain' => 'gov.br', 'country_code' => 'BR', 'country_name' => 'Brazil', 'entity_type' => 'general'],
    
    // South Africa
    ['domain' => 'gov.za', 'country_code' => 'ZA', 'country_name' => 'South Africa', 'entity_type' => 'general'],
    
    // Singapore
    ['domain' => 'gov.sg', 'country_code' => 'SG', 'country_name' => 'Singapore', 'entity_type' => 'general'],
    
    // Spain
    ['domain' => 'gob.es', 'country_code' => 'ES', 'country_name' => 'Spain', 'entity_type' => 'general'],
    
    // Italy
    ['domain' => 'gov.it', 'country_code' => 'IT', 'country_name' => 'Italy', 'entity_type' => 'general'],
    
    // Sweden
    ['domain' => 'gov.se', 'country_code' => 'SE', 'country_name' => 'Sweden', 'entity_type' => 'general'],
    
    // Norway
    ['domain' => 'gov.no', 'country_code' => 'NO', 'country_name' => 'Norway', 'entity_type' => 'general'],
    
    // Denmark
    ['domain' => 'gov.dk', 'country_code' => 'DK', 'country_name' => 'Denmark', 'entity_type' => 'general'],
    
    // Belgium
    ['domain' => 'gov.be', 'country_code' => 'BE', 'country_name' => 'Belgium', 'entity_type' => 'general'],
    
    // Switzerland
    ['domain' => 'admin.ch', 'country_code' => 'CH', 'country_name' => 'Switzerland', 'entity_type' => 'general'],
    
    // Austria
    ['domain' => 'gv.at', 'country_code' => 'AT', 'country_name' => 'Austria', 'entity_type' => 'general'],
    
    // Poland
    ['domain' => 'gov.pl', 'country_code' => 'PL', 'country_name' => 'Poland', 'entity_type' => 'general'],
    
    // Turkey
    ['domain' => 'gov.tr', 'country_code' => 'TR', 'country_name' => 'Turkey', 'entity_type' => 'general'],
    
    // Russia
    ['domain' => 'gov.ru', 'country_code' => 'RU', 'country_name' => 'Russia', 'entity_type' => 'general'],
    
    // South Korea
    ['domain' => 'go.kr', 'country_code' => 'KR', 'country_name' => 'South Korea', 'entity_type' => 'general'],
    
    // Mexico
    ['domain' => 'gob.mx', 'country_code' => 'MX', 'country_name' => 'Mexico', 'entity_type' => 'general'],
    
    // Argentina
    ['domain' => 'gob.ar', 'country_code' => 'AR', 'country_name' => 'Argentina', 'entity_type' => 'general'],
    
    // Indonesia
    ['domain' => 'go.id', 'country_code' => 'ID', 'country_name' => 'Indonesia', 'entity_type' => 'general'],
    
    // Malaysia
    ['domain' => 'gov.my', 'country_code' => 'MY', 'country_name' => 'Malaysia', 'entity_type' => 'general'],
    
    // Thailand
    ['domain' => 'go.th', 'country_code' => 'TH', 'country_name' => 'Thailand', 'entity_type' => 'general'],
    
    // Philippines
    ['domain' => 'gov.ph', 'country_code' => 'PH', 'country_name' => 'Philippines', 'entity_type' => 'general'],
    
    // Vietnam
    ['domain' => 'gov.vn', 'country_code' => 'VN', 'country_name' => 'Vietnam', 'entity_type' => 'general'],
    
    // New Zealand
    ['domain' => 'govt.nz', 'country_code' => 'NZ', 'country_name' => 'New Zealand', 'entity_type' => 'general'],
    
    // Ireland
    ['domain' => 'gov.ie', 'country_code' => 'IE', 'country_name' => 'Ireland', 'entity_type' => 'general'],
    
    // Israel
    ['domain' => 'gov.il', 'country_code' => 'IL', 'country_name' => 'Israel', 'entity_type' => 'general'],
    
    // Pakistan
    ['domain' => 'gov.pk', 'country_code' => 'PK', 'country_name' => 'Pakistan', 'entity_type' => 'general'],
    
    // Nigeria
    ['domain' => 'gov.ng', 'country_code' => 'NG', 'country_name' => 'Nigeria', 'entity_type' => 'general'],
    
    // Kenya
    ['domain' => 'go.ke', 'country_code' => 'KE', 'country_name' => 'Kenya', 'entity_type' => 'general'],
    
    // Greece
    ['domain' => 'gov.gr', 'country_code' => 'GR', 'country_name' => 'Greece', 'entity_type' => 'general'],
    
    // Portugal
    ['domain' => 'gov.pt', 'country_code' => 'PT', 'country_name' => 'Portugal', 'entity_type' => 'general'],
    
    // Czech Republic
    ['domain' => 'gov.cz', 'country_code' => 'CZ', 'country_name' => 'Czech Republic', 'entity_type' => 'general'],
    
    // Hungary
    ['domain' => 'gov.hu', 'country_code' => 'HU', 'country_name' => 'Hungary', 'entity_type' => 'general'],
    
    // Romania
    ['domain' => 'gov.ro', 'country_code' => 'RO', 'country_name' => 'Romania', 'entity_type' => 'general'],
];

$inserted = 0;
foreach ($domains as $domain) {
    $exists = DB::table('government_email_domains')->where('domain', $domain['domain'])->exists();
    if (!$exists) {
        DB::table('government_email_domains')->insert(array_merge($domain, [
            'is_verified' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]));
        $inserted++;
    }
}
echo "  ✅ Inserted {$inserted} new domains\n";
$total = DB::table('government_email_domains')->count();
echo "  📊 Total domains: {$total}\n";

// =====================================================
// 4. VERIFY INSTALLATION
// =====================================================
echo "\n✅ Step 4: Verifying Installation...\n";

$checks = [
    'government_verifications table' => Schema::hasTable('government_verifications'),
    'government_documents table' => Schema::hasTable('government_documents'),
    'government_audit_logs table' => Schema::hasTable('government_audit_logs'),
    'government_email_domains table' => Schema::hasTable('government_email_domains'),
    'government_communications table' => Schema::hasTable('government_communications'),
    'users.account_type column' => Schema::hasColumn('users', 'account_type'),
    'users.is_government_verified column' => Schema::hasColumn('users', 'is_government_verified'),
];

$allPassed = true;
foreach ($checks as $check => $passed) {
    if ($passed) {
        echo "  ✅ {$check}\n";
    } else {
        echo "  ❌ {$check}\n";
        $allPassed = false;
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
if ($allPassed) {
    echo "🎉 INSTALLATION COMPLETE!\n";
    echo "Government Protection System is ready.\n";
} else {
    echo "⚠️ INSTALLATION INCOMPLETE\n";
    echo "Some components failed to install.\n";
}
echo str_repeat("=", 50) . "\n";
