<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyIntegration;
use App\Models\CompanyApiKey;
use App\Models\CompanySetting;
use Illuminate\Support\Str;

class DemoCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Demo Company
        $demoCompany = Company::firstOrCreate(
            ['domain' => 'demo.culturaltranslate.com'],
            [
                'name' => 'Demo Company (Trial)',
                'plan_id' => null, // Free trial
                'status' => 'active',
                'member_count' => 1,
                'translation_memory_size' => 0,
                'sso_enabled' => false,
                'custom_domain_enabled' => false,
            ]
        );

        // Create Company Settings
        CompanySetting::firstOrCreate(
            ['company_id' => $demoCompany->id],
            [
                'enabled_features' => [
                    'glossary' => true,
                    'brand_voice' => true,
                    'cultural_memory' => false, // Limited for trial
                ],
                'allowed_models' => ['gpt-4o-mini'], // Trial uses mini model
                'rate_limit_per_minute' => 10, // 10 requests per minute for demo
                'max_tokens_monthly' => 100000, // 100K tokens/month for trial
            ]
        );

        // Create Company Integration (Custom API)
        CompanyIntegration::firstOrCreate(
            [
                'company_id' => $demoCompany->id,
                'provider' => 'custom'
            ],
            [
                'api_key' => 'ck_demo_' . bin2hex(random_bytes(16)),
                'api_secret' => 'cs_demo_' . bin2hex(random_bytes(32)),
                'webhook_url' => 'https://demo.culturaltranslate.com/api/webhooks',
                'events' => ['translation.completed', 'translation.failed'],
                'domains' => [
                    ['domain' => 'demo.culturaltranslate.com'],
                    ['domain' => 'localhost'],
                ],
                'features_flags' => [
                    'auto_detect_language' => true,
                    'cultural_adaptation' => true,
                ],
                'status' => 'active', // String not boolean
                'last_success_at' => now(),
            ]
        );

        // Create Demo API Key for public testing
        CompanyApiKey::firstOrCreate(
            [
                'company_id' => $demoCompany->id,
                'name' => 'Public Demo Key'
            ],
            [
                'key' => 'demo_' . Str::uuid() . '_' . bin2hex(random_bytes(8)),
                'scopes' => [
                    'translate:read' => true,
                    'translate:write' => true,
                    'glossary:read' => true,
                ],
                'rate_limit_per_minute' => 5, // 5 requests per minute for public key
                'expires_at' => null, // Never expires for demo
                'revoked' => false,
            ]
        );

        $this->command->info('✅ Demo company created successfully!');
        $this->command->info('🌐 Domain: demo.culturaltranslate.com');
        $this->command->info('🏢 Company: ' . $demoCompany->name);
        $this->command->info('🔑 API Integration created with webhooks');
        $this->command->info('⚡ Rate limit: 10 req/min | 100K tokens/month');
    }
}
