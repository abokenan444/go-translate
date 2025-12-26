<?php

namespace App\Services;

use App\Models\SandboxApiKey;
use App\Models\SandboxInstance;
use App\Models\SandboxPage;
use App\Models\SandboxSiteTemplate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SandboxInstanceService
{
    public function createSandbox(User $user, array $data): SandboxInstance
    {
        return DB::transaction(function () use ($user, $data) {
            $subdomain = $data['subdomain'] ?? Str::slug($data['company_slug'] ?? $data['company_name'] ?? $user->name) . '-' . Str::random(6);

            $instance = SandboxInstance::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'] ?? $user->name,
                'company_slug' => $data['company_slug'] ?? Str::slug($data['company_name'] ?? $user->name),
                'subdomain' => $subdomain,
                'status' => 'active',
                'industry_profile' => $data['industry_profile'] ?? [],
                'target_markets' => $data['target_markets'] ?? [],
                'tone' => $data['tone'] ?? 'neutral',
                'brand_values' => $data['brand_values'] ?? [],
                'preferred_terms' => $data['preferred_terms'] ?? [],
                'forbidden_terms' => $data['forbidden_terms'] ?? [],
                'is_public_preview' => $data['is_public_preview'] ?? true,
                'rate_limit_profile' => $data['rate_limit_profile'] ?? 'sandbox-default',
                'expires_at' => $data['expires_at'] ?? now()->addDays(14),
            ]);

            $apiKey = SandboxApiKey::create([
                'sandbox_instance_id' => $instance->id,
                'key' => SandboxApiKey::generateKey(),
                'name' => 'Default Key',
                'scopes' => $data['scopes'] ?? ['sandbox.read', 'sandbox.write', 'sandbox.translations.*'],
                'rate_limit_profile' => $instance->rate_limit_profile,
            ]);

            $template = SandboxSiteTemplate::active()->first();
            if (!$template) {
                $template = SandboxSiteTemplate::create([
                    'name' => 'SaaS Landing',
                    'slug' => 'saas-landing',
                    'description' => 'Default SaaS landing page template',
                    'is_active' => true,
                    'config' => [
                        'pages' => ['/','/pricing','/about'],
                        'sections' => ['hero','features','cta','footer'],
                        'default_content' => [
                            'en' => [
                                'hero' => ['title' => 'Welcome to Cultural Translate','subtitle' => 'Test your integration safely','cta' => 'Get Started'],
                                'features' => ['items' => ['API v2','Realtime','Webhooks']],
                                'cta' => ['text' => 'Create your sandbox now'],
                            ],
                        ],
                    ],
                ]);
            }

            SandboxPage::create([
                'sandbox_instance_id' => $instance->id,
                'template_id' => $template->id,
                'path' => '/',
                'original_content' => $template->getDefaultContent('en'),
                'translated_content' => null,
                'locale' => 'en',
                'market' => 'US',
            ]);

            return $instance->fresh(['apiKeys','pages']);
        });
    }

    public function updateSandbox(SandboxInstance $instance, array $data): SandboxInstance
    {
        $instance->update($data);
        return $instance->fresh();
    }

    public function expireSandbox(SandboxInstance $instance): void
    {
        $instance->update(['status' => 'expired']);
        $instance->apiKeys()->update(['rate_limit_profile' => 'disabled']);
    }

    public function deleteSandbox(SandboxInstance $instance): void
    {
        DB::transaction(function () use ($instance) {
            $instance->webhookLogs()->delete();
            $instance->webhookEndpoints()->delete();
            $instance->pages()->delete();
            $instance->apiKeys()->delete();
            $instance->delete();
        });
    }
}
<?php

namespace App\Services;

use App\Models\SandboxInstance;
use App\Models\SandboxApiKey;
use App\Models\SandboxPage;
use App\Models\User;
use App\Models\Project;
use App\Models\BrandProfile;
use App\Models\Glossary;
use App\Models\TranslationMemory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SandboxInstanceService
{
    public function createSandbox(User $user, array $data): SandboxInstance
    {
        return DB::transaction(function () use ($user, $data) {
            // Create company slug and subdomain
            $companySlug = Str::slug($data['company_name']);
            $subdomain = $this->generateUniqueSubdomain($companySlug);

            // Create Brand Profile
            $brandProfile = BrandProfile::create([
                'user_id' => $user->id,
                'name' => $data['company_name'],
                'industry' => $data['industry_profile'] ?? 'general',
                'tone' => $data['tone'] ?? 'professional',
                'target_markets' => $data['target_markets'] ?? ['global'],
                'brand_values' => $data['brand_values'] ?? [],
                'preferred_terms' => $data['preferred_terms'] ?? [],
                'forbidden_terms' => $data['forbidden_terms'] ?? [],
            ]);

            // Create Glossary
            $glossary = Glossary::create([
                'user_id' => $user->id,
                'name' => "{$data['company_name']} - Sandbox Glossary",
                'source_language' => 'en',
                'target_language' => $data['target_markets'][0] ?? 'ar',
                'is_active' => true,
            ]);

            // Create Translation Memory
            $memory = TranslationMemory::create([
                'user_id' => $user->id,
                'name' => "{$data['company_name']} - Sandbox Memory",
                'source_language' => 'en',
                'target_language' => $data['target_markets'][0] ?? 'ar',
            ]);

            // Create Project
            $project = Project::create([
                'user_id' => $user->id,
                'name' => "{$data['company_name']} - Sandbox Project",
                'description' => 'Sandbox testing project',
                'source_language' => 'en',
                'target_languages' => $data['target_markets'] ?? ['ar'],
                'status' => 'active',
                'brand_profile_id' => $brandProfile->id,
            ]);

            // Create Sandbox Instance
            $sandbox = SandboxInstance::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'company_slug' => $companySlug,
                'subdomain' => $subdomain,
                'status' => 'active',
                'brand_profile_id' => $brandProfile->id,
                'industry_profile' => $data['industry_profile'] ?? ['type' => 'general'],
                'target_markets' => $data['target_markets'] ?? ['ar'],
                'tone' => $data['tone'] ?? 'professional',
                'brand_values' => $data['brand_values'] ?? [],
                'preferred_terms' => $data['preferred_terms'] ?? [],
                'forbidden_terms' => $data['forbidden_terms'] ?? [],
                'glossary_id' => $glossary->id,
                'memory_id' => $memory->id,
                'project_id' => $project->id,
                'preview_url' => "https://{$subdomain}.integration.culturaltranslate.com",
                'is_public_preview' => $data['is_public_preview'] ?? true,
                'rate_limit_profile' => 'sandbox_default',
                'expires_at' => now()->addDays($data['trial_days'] ?? 14),
            ]);

            // Create API Key
            $apiKey = SandboxApiKey::create([
                'sandbox_instance_id' => $sandbox->id,
                'key' => SandboxApiKey::generateKey(),
                'name' => 'Default Sandbox Key',
                'scopes' => [
                    'sandbox.read',
                    'sandbox.write',
                    'sandbox.translations.*',
                    'sandbox.docs.*',
                ],
                'rate_limit_profile' => 'sandbox_default',
            ]);

            // Create default page from template
            if (isset($data['template_id'])) {
                $this->createPageFromTemplate($sandbox, $data['template_id']);
            }

            return $sandbox->load(['apiKeys', 'pages', 'brandProfile', 'project']);
        });
    }

    public function createPageFromTemplate(SandboxInstance $sandbox, int $templateId, string $locale = 'en'): SandboxPage
    {
        $template = \App\Models\SandboxSiteTemplate::findOrFail($templateId);
        $defaultContent = $template->getDefaultContent($locale);

        return SandboxPage::create([
            'sandbox_instance_id' => $sandbox->id,
            'template_id' => $templateId,
            'path' => '/',
            'original_content' => $defaultContent,
            'locale' => $locale,
            'market' => $sandbox->target_markets[0] ?? 'global',
        ]);
    }

    public function translatePage(SandboxPage $page, string $targetLocale): SandboxPage
    {
        $sandbox = $page->sandboxInstance;
        $originalContent = $page->original_content;
        
        // Here you would integrate with your translation service
        // For now, we'll mark it as ready for translation
        
        $translatedContent = $this->performTranslation(
            $originalContent,
            $page->locale,
            $targetLocale,
            $sandbox
        );

        $page->update([
            'translated_content' => $translatedContent,
            'market' => $targetLocale,
        ]);

        return $page;
    }

    private function performTranslation(array $content, string $sourceLang, string $targetLang, SandboxInstance $sandbox): array
    {
        // Integration with your translation engine
        // This is a placeholder - integrate with OpenAI/custom translation service
        
        $translated = [];
        foreach ($content as $key => $value) {
            if (is_array($value)) {
                $translated[$key] = $this->performTranslation($value, $sourceLang, $targetLang, $sandbox);
            } else {
                // Call translation API here
                $translated[$key] = $value; // Placeholder
            }
        }
        
        return $translated;
    }

    public function expireSandbox(SandboxInstance $sandbox): void
    {
        $sandbox->update(['status' => 'expired']);
        
        // Disable all API keys
        $sandbox->apiKeys()->update(['is_active' => false]);
    }

    public function extendSandbox(SandboxInstance $sandbox, int $days): void
    {
        $currentExpiry = $sandbox->expires_at ?? now();
        $newExpiry = $currentExpiry->addDays($days);
        
        $sandbox->update([
            'expires_at' => $newExpiry,
            'status' => 'active',
        ]);
    }

    public function deleteSandbox(SandboxInstance $sandbox): void
    {
        DB::transaction(function () use ($sandbox) {
            // Delete related resources
            $sandbox->pages()->delete();
            $sandbox->apiKeys()->delete();
            $sandbox->webhookEndpoints()->delete();
            $sandbox->webhookLogs()->delete();
            
            // Optionally delete project, glossary, memory
            $sandbox->project?->delete();
            $sandbox->glossary?->delete();
            $sandbox->translationMemory?->delete();
            
            $sandbox->delete();
        });
    }

    private function generateUniqueSubdomain(string $base): string
    {
        $subdomain = $base;
        $counter = 1;

        while (SandboxInstance::where('subdomain', $subdomain)->exists()) {
            $subdomain = $base . '-' . $counter;
            $counter++;
        }

        return $subdomain;
    }

    public function simulateWebhook(SandboxInstance $sandbox, string $eventType, array $payload): void
    {
        $endpoints = $sandbox->webhookEndpoints()
            ->active()
            ->get()
            ->filter(fn($endpoint) => $endpoint->subscribesToEvent($eventType));

        foreach ($endpoints as $endpoint) {
            $log = \App\Models\SandboxWebhookLog::create([
                'sandbox_instance_id' => $sandbox->id,
                'webhook_endpoint_id' => $endpoint->id,
                'event_type' => $eventType,
                'payload' => $payload,
                'delivered_at' => now(),
                'delivery_status' => $endpoint->is_simulation_only ? 'simulated' : 'delivered',
            ]);

            if (!$endpoint->is_simulation_only && $endpoint->url) {
                $this->deliverWebhook($endpoint, $payload, $log);
            }
        }
    }

    private function deliverWebhook($endpoint, array $payload, $log): void
    {
        try {
            $signature = $endpoint->generateSignature($payload);
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'X-Webhook-Signature' => $signature,
                'X-Event-Type' => $log->event_type,
            ])->post($endpoint->url, $payload);

            $log->update([
                'delivery_status' => $response->successful() ? 'delivered' : 'failed',
                'response_code' => $response->status(),
                'response_body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            $log->update([
                'delivery_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
