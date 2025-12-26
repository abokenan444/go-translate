<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "🔧 Creating Government Verification Protection System...\n\n";

// 1. Add columns to users table
echo "1️⃣ Adding columns to users table...\n";
$userColumns = [
    'account_type' => "VARCHAR(50) DEFAULT 'individual'",
    'account_status' => "VARCHAR(50) DEFAULT 'active'",
    'is_government_verified' => "BOOLEAN DEFAULT FALSE",
    'government_verified_at' => "TIMESTAMP NULL",
    'government_badge' => "VARCHAR(50) NULL",
    'government_access_level' => "VARCHAR(50) DEFAULT 'none'",
    'government_entity_id' => "BIGINT UNSIGNED NULL",
];

foreach ($userColumns as $col => $definition) {
    if (!Schema::hasColumn('users', $col)) {
        try {
            DB::statement("ALTER TABLE users ADD COLUMN $col $definition");
            echo "   ✅ Added: users.$col\n";
        } catch (Exception $e) {
            echo "   ⚠️ $col: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ✓ Exists: users.$col\n";
    }
}

// 2. Create government_email_domains table
echo "\n2️⃣ Creating government_email_domains table...\n";
if (!Schema::hasTable('government_email_domains')) {
    Schema::create('government_email_domains', function (Blueprint $table) {
        $table->id();
        $table->string('domain')->unique();
        $table->string('country_code', 3);
        $table->string('country_name');
        $table->string('entity_type')->nullable(); // ministry, embassy, municipality, agency
        $table->string('entity_name')->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('is_verified')->default(false);
        $table->text('notes')->nullable();
        $table->timestamps();
        
        $table->index(['domain', 'is_active']);
        $table->index('country_code');
    });
    echo "   ✅ Created government_email_domains\n";
} else {
    echo "   ✓ Table exists\n";
}

// 3. Create government_verifications table
echo "\n3️⃣ Creating government_verifications table...\n";
if (!Schema::hasTable('government_verifications')) {
    Schema::create('government_verifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Entity Information
        $table->string('entity_name');
        $table->string('entity_name_ar')->nullable();
        $table->string('entity_name_local')->nullable();
        $table->enum('entity_type', ['ministry', 'embassy', 'consulate', 'municipality', 'agency', 'department', 'court', 'police', 'military', 'other']);
        $table->string('country_code', 3);
        $table->string('country_name');
        $table->string('region')->nullable();
        $table->string('city')->nullable();
        
        // Contact Information
        $table->string('official_email');
        $table->string('official_phone')->nullable();
        $table->string('official_website')->nullable();
        $table->string('official_address')->nullable();
        
        // Applicant Information
        $table->string('job_title');
        $table->string('department')->nullable();
        $table->string('employee_id')->nullable();
        $table->string('supervisor_name')->nullable();
        $table->string('supervisor_email')->nullable();
        $table->string('supervisor_phone')->nullable();
        
        // Documents (JSON array)
        $table->json('documents')->nullable();
        $table->json('additional_documents')->nullable();
        
        // Verification Status
        $table->enum('status', [
            'pending_verification',
            'under_review',
            'documents_requested',
            'external_verification',
            'approved',
            'rejected',
            'suspended',
            'expired'
        ])->default('pending_verification');
        
        // Review Information
        $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('reviewed_at')->nullable();
        $table->text('review_notes')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->text('internal_notes')->nullable();
        
        // Badge & Access
        $table->string('badge_level')->default('none'); // none, pending, verified, premium, diplomatic
        $table->string('access_level')->default('none'); // none, basic, standard, premium, full
        $table->json('permissions')->nullable();
        $table->json('restrictions')->nullable();
        
        // Legal Declaration
        $table->boolean('legal_declaration_accepted')->default(false);
        $table->string('declaration_ip')->nullable();
        $table->timestamp('declaration_timestamp')->nullable();
        $table->text('declaration_text')->nullable();
        
        // Security
        $table->string('verification_token', 64)->nullable();
        $table->timestamp('token_expires_at')->nullable();
        $table->integer('verification_attempts')->default(0);
        $table->timestamp('last_verification_attempt')->nullable();
        
        // External Verification
        $table->boolean('external_verification_required')->default(false);
        $table->string('external_verification_method')->nullable();
        $table->json('external_verification_data')->nullable();
        $table->timestamp('external_verified_at')->nullable();
        
        // MoU / Agreement
        $table->boolean('has_mou')->default(false);
        $table->string('mou_document')->nullable();
        $table->date('mou_start_date')->nullable();
        $table->date('mou_end_date')->nullable();
        $table->enum('mou_status', ['none', 'pending', 'active', 'expired', 'terminated'])->default('none');
        
        // Audit Trail
        $table->json('audit_trail')->nullable();
        $table->string('created_ip')->nullable();
        $table->string('created_user_agent')->nullable();
        
        // Expiration
        $table->date('verification_expires_at')->nullable();
        $table->boolean('auto_renewal')->default(false);
        
        $table->timestamps();
        $table->softDeletes();
        
        // Indexes
        $table->index('status');
        $table->index(['user_id', 'status']);
        $table->index('entity_type');
        $table->index('country_code');
        $table->index('official_email');
        $table->index('verification_token');
    });
    echo "   ✅ Created government_verifications\n";
} else {
    echo "   ✓ Table exists\n";
}

// 4. Create government_verification_logs table
echo "\n4️⃣ Creating government_verification_logs table...\n";
if (!Schema::hasTable('government_verification_logs')) {
    Schema::create('government_verification_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('government_verification_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        
        $table->string('action'); // created, status_changed, documents_uploaded, reviewed, approved, rejected, etc.
        $table->string('from_status')->nullable();
        $table->string('to_status')->nullable();
        $table->text('notes')->nullable();
        $table->json('metadata')->nullable();
        
        $table->string('ip_address')->nullable();
        $table->string('user_agent')->nullable();
        $table->string('location')->nullable();
        
        $table->timestamps();
        
        $table->index(['government_verification_id', 'created_at']);
        $table->index('action');
    });
    echo "   ✅ Created government_verification_logs\n";
} else {
    echo "   ✓ Table exists\n";
}

// 5. Create government_communications table
echo "\n5️⃣ Creating government_communications table...\n";
if (!Schema::hasTable('government_communications')) {
    Schema::create('government_communications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('government_verification_id')->constrained()->onDelete('cascade');
        $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
        
        $table->enum('type', ['message', 'document_request', 'clarification', 'official_letter', 'phone_call', 'meeting', 'email']);
        $table->enum('direction', ['incoming', 'outgoing']);
        $table->string('subject');
        $table->text('content');
        $table->json('attachments')->nullable();
        
        $table->boolean('is_read')->default(false);
        $table->timestamp('read_at')->nullable();
        $table->boolean('requires_response')->default(false);
        $table->timestamp('response_deadline')->nullable();
        $table->foreignId('response_to_id')->nullable();
        
        $table->timestamps();
        
        $table->index(['government_verification_id', 'created_at']);
    });
    echo "   ✅ Created government_communications\n";
} else {
    echo "   ✓ Table exists\n";
}

// 6. Insert default government email domains
echo "\n6️⃣ Inserting default government email domains...\n";
$domains = [
    // Generic
    ['domain' => 'gov', 'country_code' => 'INT', 'country_name' => 'International', 'entity_type' => 'government'],
    
    // United States
    ['domain' => 'gov', 'country_code' => 'USA', 'country_name' => 'United States', 'entity_type' => 'government'],
    ['domain' => 'state.gov', 'country_code' => 'USA', 'country_name' => 'United States', 'entity_type' => 'ministry'],
    ['domain' => 'senate.gov', 'country_code' => 'USA', 'country_name' => 'United States', 'entity_type' => 'government'],
    ['domain' => 'house.gov', 'country_code' => 'USA', 'country_name' => 'United States', 'entity_type' => 'government'],
    
    // United Kingdom
    ['domain' => 'gov.uk', 'country_code' => 'GBR', 'country_name' => 'United Kingdom', 'entity_type' => 'government'],
    ['domain' => 'parliament.uk', 'country_code' => 'GBR', 'country_name' => 'United Kingdom', 'entity_type' => 'government'],
    ['domain' => 'police.uk', 'country_code' => 'GBR', 'country_name' => 'United Kingdom', 'entity_type' => 'police'],
    
    // European Union
    ['domain' => 'europa.eu', 'country_code' => 'EUR', 'country_name' => 'European Union', 'entity_type' => 'agency'],
    ['domain' => 'ec.europa.eu', 'country_code' => 'EUR', 'country_name' => 'European Union', 'entity_type' => 'agency'],
    
    // Germany
    ['domain' => 'bund.de', 'country_code' => 'DEU', 'country_name' => 'Germany', 'entity_type' => 'government'],
    ['domain' => 'bundesregierung.de', 'country_code' => 'DEU', 'country_name' => 'Germany', 'entity_type' => 'government'],
    
    // France
    ['domain' => 'gouv.fr', 'country_code' => 'FRA', 'country_name' => 'France', 'entity_type' => 'government'],
    ['domain' => 'diplomatie.gouv.fr', 'country_code' => 'FRA', 'country_name' => 'France', 'entity_type' => 'ministry'],
    
    // Netherlands
    ['domain' => 'gov.nl', 'country_code' => 'NLD', 'country_name' => 'Netherlands', 'entity_type' => 'government'],
    ['domain' => 'rijksoverheid.nl', 'country_code' => 'NLD', 'country_name' => 'Netherlands', 'entity_type' => 'government'],
    ['domain' => 'minbuza.nl', 'country_code' => 'NLD', 'country_name' => 'Netherlands', 'entity_type' => 'ministry'],
    
    // Saudi Arabia
    ['domain' => 'gov.sa', 'country_code' => 'SAU', 'country_name' => 'Saudi Arabia', 'entity_type' => 'government'],
    ['domain' => 'mofa.gov.sa', 'country_code' => 'SAU', 'country_name' => 'Saudi Arabia', 'entity_type' => 'ministry'],
    
    // UAE
    ['domain' => 'gov.ae', 'country_code' => 'ARE', 'country_name' => 'United Arab Emirates', 'entity_type' => 'government'],
    ['domain' => 'mofaic.gov.ae', 'country_code' => 'ARE', 'country_name' => 'United Arab Emirates', 'entity_type' => 'ministry'],
    
    // Egypt
    ['domain' => 'gov.eg', 'country_code' => 'EGY', 'country_name' => 'Egypt', 'entity_type' => 'government'],
    
    // Jordan
    ['domain' => 'gov.jo', 'country_code' => 'JOR', 'country_name' => 'Jordan', 'entity_type' => 'government'],
    
    // Australia
    ['domain' => 'gov.au', 'country_code' => 'AUS', 'country_name' => 'Australia', 'entity_type' => 'government'],
    
    // Canada
    ['domain' => 'gc.ca', 'country_code' => 'CAN', 'country_name' => 'Canada', 'entity_type' => 'government'],
    ['domain' => 'canada.ca', 'country_code' => 'CAN', 'country_name' => 'Canada', 'entity_type' => 'government'],
    
    // Japan
    ['domain' => 'go.jp', 'country_code' => 'JPN', 'country_name' => 'Japan', 'entity_type' => 'government'],
    
    // China
    ['domain' => 'gov.cn', 'country_code' => 'CHN', 'country_name' => 'China', 'entity_type' => 'government'],
    
    // India
    ['domain' => 'gov.in', 'country_code' => 'IND', 'country_name' => 'India', 'entity_type' => 'government'],
    ['domain' => 'nic.in', 'country_code' => 'IND', 'country_name' => 'India', 'entity_type' => 'agency'],
    
    // Brazil
    ['domain' => 'gov.br', 'country_code' => 'BRA', 'country_name' => 'Brazil', 'entity_type' => 'government'],
    
    // South Africa
    ['domain' => 'gov.za', 'country_code' => 'ZAF', 'country_name' => 'South Africa', 'entity_type' => 'government'],
    
    // Spain
    ['domain' => 'gob.es', 'country_code' => 'ESP', 'country_name' => 'Spain', 'entity_type' => 'government'],
    
    // Italy
    ['domain' => 'gov.it', 'country_code' => 'ITA', 'country_name' => 'Italy', 'entity_type' => 'government'],
    
    // Turkey
    ['domain' => 'gov.tr', 'country_code' => 'TUR', 'country_name' => 'Turkey', 'entity_type' => 'government'],
    
    // Russia
    ['domain' => 'gov.ru', 'country_code' => 'RUS', 'country_name' => 'Russia', 'entity_type' => 'government'],
    
    // South Korea
    ['domain' => 'go.kr', 'country_code' => 'KOR', 'country_name' => 'South Korea', 'entity_type' => 'government'],
    
    // Singapore
    ['domain' => 'gov.sg', 'country_code' => 'SGP', 'country_name' => 'Singapore', 'entity_type' => 'government'],
    
    // Malaysia
    ['domain' => 'gov.my', 'country_code' => 'MYS', 'country_name' => 'Malaysia', 'entity_type' => 'government'],
    
    // Indonesia
    ['domain' => 'go.id', 'country_code' => 'IDN', 'country_name' => 'Indonesia', 'entity_type' => 'government'],
    
    // Mexico
    ['domain' => 'gob.mx', 'country_code' => 'MEX', 'country_name' => 'Mexico', 'entity_type' => 'government'],
    
    // New Zealand
    ['domain' => 'govt.nz', 'country_code' => 'NZL', 'country_name' => 'New Zealand', 'entity_type' => 'government'],
    
    // Switzerland
    ['domain' => 'admin.ch', 'country_code' => 'CHE', 'country_name' => 'Switzerland', 'entity_type' => 'government'],
    
    // Belgium
    ['domain' => 'belgium.be', 'country_code' => 'BEL', 'country_name' => 'Belgium', 'entity_type' => 'government'],
    
    // Austria
    ['domain' => 'gv.at', 'country_code' => 'AUT', 'country_name' => 'Austria', 'entity_type' => 'government'],
    
    // Norway
    ['domain' => 'dep.no', 'country_code' => 'NOR', 'country_name' => 'Norway', 'entity_type' => 'government'],
    
    // Sweden
    ['domain' => 'gov.se', 'country_code' => 'SWE', 'country_name' => 'Sweden', 'entity_type' => 'government'],
    
    // Denmark
    ['domain' => 'gov.dk', 'country_code' => 'DNK', 'country_name' => 'Denmark', 'entity_type' => 'government'],
    
    // Finland
    ['domain' => 'gov.fi', 'country_code' => 'FIN', 'country_name' => 'Finland', 'entity_type' => 'government'],
    
    // Poland
    ['domain' => 'gov.pl', 'country_code' => 'POL', 'country_name' => 'Poland', 'entity_type' => 'government'],
    
    // Czech Republic
    ['domain' => 'gov.cz', 'country_code' => 'CZE', 'country_name' => 'Czech Republic', 'entity_type' => 'government'],
    
    // Portugal
    ['domain' => 'gov.pt', 'country_code' => 'PRT', 'country_name' => 'Portugal', 'entity_type' => 'government'],
    
    // Greece
    ['domain' => 'gov.gr', 'country_code' => 'GRC', 'country_name' => 'Greece', 'entity_type' => 'government'],
    
    // Ireland
    ['domain' => 'gov.ie', 'country_code' => 'IRL', 'country_name' => 'Ireland', 'entity_type' => 'government'],
    
    // Israel
    ['domain' => 'gov.il', 'country_code' => 'ISR', 'country_name' => 'Israel', 'entity_type' => 'government'],
    
    // Pakistan
    ['domain' => 'gov.pk', 'country_code' => 'PAK', 'country_name' => 'Pakistan', 'entity_type' => 'government'],
    
    // Bangladesh
    ['domain' => 'gov.bd', 'country_code' => 'BGD', 'country_name' => 'Bangladesh', 'entity_type' => 'government'],
    
    // Thailand
    ['domain' => 'go.th', 'country_code' => 'THA', 'country_name' => 'Thailand', 'entity_type' => 'government'],
    
    // Vietnam
    ['domain' => 'gov.vn', 'country_code' => 'VNM', 'country_name' => 'Vietnam', 'entity_type' => 'government'],
    
    // Philippines
    ['domain' => 'gov.ph', 'country_code' => 'PHL', 'country_name' => 'Philippines', 'entity_type' => 'government'],
    
    // Argentina
    ['domain' => 'gob.ar', 'country_code' => 'ARG', 'country_name' => 'Argentina', 'entity_type' => 'government'],
    
    // Chile
    ['domain' => 'gob.cl', 'country_code' => 'CHL', 'country_name' => 'Chile', 'entity_type' => 'government'],
    
    // Colombia
    ['domain' => 'gov.co', 'country_code' => 'COL', 'country_name' => 'Colombia', 'entity_type' => 'government'],
    
    // Peru
    ['domain' => 'gob.pe', 'country_code' => 'PER', 'country_name' => 'Peru', 'entity_type' => 'government'],
    
    // Kuwait
    ['domain' => 'gov.kw', 'country_code' => 'KWT', 'country_name' => 'Kuwait', 'entity_type' => 'government'],
    
    // Qatar
    ['domain' => 'gov.qa', 'country_code' => 'QAT', 'country_name' => 'Qatar', 'entity_type' => 'government'],
    
    // Bahrain
    ['domain' => 'gov.bh', 'country_code' => 'BHR', 'country_name' => 'Bahrain', 'entity_type' => 'government'],
    
    // Oman
    ['domain' => 'gov.om', 'country_code' => 'OMN', 'country_name' => 'Oman', 'entity_type' => 'government'],
    
    // Morocco
    ['domain' => 'gov.ma', 'country_code' => 'MAR', 'country_name' => 'Morocco', 'entity_type' => 'government'],
    
    // Tunisia
    ['domain' => 'gov.tn', 'country_code' => 'TUN', 'country_name' => 'Tunisia', 'entity_type' => 'government'],
    
    // Algeria
    ['domain' => 'gov.dz', 'country_code' => 'DZA', 'country_name' => 'Algeria', 'entity_type' => 'government'],
    
    // Iraq
    ['domain' => 'gov.iq', 'country_code' => 'IRQ', 'country_name' => 'Iraq', 'entity_type' => 'government'],
    
    // Lebanon
    ['domain' => 'gov.lb', 'country_code' => 'LBN', 'country_name' => 'Lebanon', 'entity_type' => 'government'],
    
    // Syria
    ['domain' => 'gov.sy', 'country_code' => 'SYR', 'country_name' => 'Syria', 'entity_type' => 'government'],
    
    // Yemen
    ['domain' => 'gov.ye', 'country_code' => 'YEM', 'country_name' => 'Yemen', 'entity_type' => 'government'],
    
    // Libya
    ['domain' => 'gov.ly', 'country_code' => 'LBY', 'country_name' => 'Libya', 'entity_type' => 'government'],
    
    // Sudan
    ['domain' => 'gov.sd', 'country_code' => 'SDN', 'country_name' => 'Sudan', 'entity_type' => 'government'],
    
    // Nigeria
    ['domain' => 'gov.ng', 'country_code' => 'NGA', 'country_name' => 'Nigeria', 'entity_type' => 'government'],
    
    // Kenya
    ['domain' => 'go.ke', 'country_code' => 'KEN', 'country_name' => 'Kenya', 'entity_type' => 'government'],
    
    // Ghana
    ['domain' => 'gov.gh', 'country_code' => 'GHA', 'country_name' => 'Ghana', 'entity_type' => 'government'],
    
    // Ethiopia
    ['domain' => 'gov.et', 'country_code' => 'ETH', 'country_name' => 'Ethiopia', 'entity_type' => 'government'],
    
    // Tanzania
    ['domain' => 'go.tz', 'country_code' => 'TZA', 'country_name' => 'Tanzania', 'entity_type' => 'government'],
    
    // Uganda
    ['domain' => 'go.ug', 'country_code' => 'UGA', 'country_name' => 'Uganda', 'entity_type' => 'government'],
];

$inserted = 0;
foreach ($domains as $domain) {
    try {
        DB::table('government_email_domains')->insertOrIgnore([
            'domain' => $domain['domain'],
            'country_code' => $domain['country_code'],
            'country_name' => $domain['country_name'],
            'entity_type' => $domain['entity_type'] ?? 'government',
            'is_active' => true,
            'is_verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $inserted++;
    } catch (Exception $e) {
        // Ignore duplicates
    }
}
echo "   ✅ Inserted $inserted government email domains\n";

echo "\n✅ Government Verification Protection System created successfully!\n";
echo "\nNext steps:\n";
echo "1. Create Filament admin resource for government verifications\n";
echo "2. Create government registration form\n";
echo "3. Set up notification system\n";
