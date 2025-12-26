<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\SandboxInstance;
use App\Models\SandboxApiKey;
use App\Models\SandboxPage;
use App\Models\SandboxSiteTemplate;

class SampleSandboxSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for SQLite
        DB::statement('PRAGMA foreign_keys = OFF');

        $template = SandboxSiteTemplate::first();
        if (! $template) {
            $template = SandboxSiteTemplate::create([
                'name' => 'Default SaaS',
                'slug' => 'default-saas',
                'config' => [
                    'sections' => ['hero', 'features', 'pricing', 'faq'],
                ],
            ]);
        }

        $subdomain = 'demo-' . Str::lower(Str::random(6));
        $companySlug = 'demo-company-' . Str::lower(Str::random(6));

        // Use DB directly to avoid model events and relations
        $instanceId = DB::table('sandbox_instances')->insertGetId([
            'user_id' => 1, // Default admin user
            'company_name' => 'Demo Company',
            'company_slug' => $companySlug,
            'subdomain' => $subdomain,
            'status' => 'active',
            'expires_at' => now()->addDays(7),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sandbox_api_keys')->insert([
            'sandbox_instance_id' => $instanceId,
            'name' => 'Default API Key',
            'scopes' => json_encode(['translate', 'webhooks']),
            'key' => Str::uuid()->toString(),
            'last_used_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sandbox_pages')->insert([
            'sandbox_instance_id' => $instanceId,
            'path' => '/',
            'original_content' => '<h1>Welcome to Demo</h1><p>This is a demo sandbox.</p>',
            'translated_content' => null,
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Re-enable foreign key checks
        DB::statement('PRAGMA foreign_keys = ON');

        $this->command->info('Sample sandbox created: ' . $subdomain);
        $this->command->info('Preview URL: https://' . $subdomain . '.integration.culturaltranslate.com/');
    }
}
