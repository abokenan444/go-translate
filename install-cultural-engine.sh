#!/usr/bin/env bash

set -e

echo "============================================"
echo "Cultural Engine Package Installer"
echo "============================================"
echo ""

PROJECT_ROOT="/var/www/cultural-translate-platform"
PACKAGE_DIR="$PROJECT_ROOT/packages/culturaltranslate/cultural-engine"

cd "$PROJECT_ROOT"

echo "===> Step 1: Creating package directory structure..."
mkdir -p "$PACKAGE_DIR/src/Models"
mkdir -p "$PACKAGE_DIR/src/Services"
mkdir -p "$PACKAGE_DIR/database/migrations"
mkdir -p "$PACKAGE_DIR/database/seeders"
mkdir -p "$PACKAGE_DIR/config"
mkdir -p "$PACKAGE_DIR/stubs/filament"

echo "===> Step 2: Creating composer.json for package..."
cat > "$PACKAGE_DIR/composer.json" << 'COMPOSER_JSON'
{
  "name": "culturaltranslate/cultural-engine",
  "description": "Cultural Intelligence Engine for CulturalTranslate platform",
  "type": "library",
  "license": "proprietary",
  "autoload": {
    "psr-4": {
      "CulturalTranslate\\CulturalEngine\\": "src/"
    }
  },
  "extra": {
    "laravel": {
      "providers": [
        "CulturalTranslate\\CulturalEngine\\CulturalEngineServiceProvider"
      ]
    }
  },
  "require": {
    "php": ">=8.1",
    "illuminate/support": "^10.0|^11.0"
  }
}
COMPOSER_JSON

echo "===> Step 3: Creating Service Provider..."
cat > "$PACKAGE_DIR/src/CulturalEngineServiceProvider.php" << 'SERVICE_PROVIDER'
<?php

namespace CulturalTranslate\CulturalEngine;

use Illuminate\Support\ServiceProvider;
use CulturalTranslate\CulturalEngine\Services\CulturalPromptBuilder;
use CulturalTranslate\CulturalEngine\Services\CulturalPostProcessor;

class CulturalEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/cultural_engine.php',
            'cultural_engine'
        );

        $this->app->singleton(CulturalPromptBuilder::class, function ($app) {
            return new CulturalPromptBuilder(config('cultural_engine'));
        });

        $this->app->singleton(CulturalPostProcessor::class, function ($app) {
            return new CulturalPostProcessor(config('cultural_engine'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/cultural_engine.php' => config_path('cultural_engine.php'),
        ], 'cultural-engine-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders/CulturalEngineSeeder.php'
                => database_path('seeders/CulturalEngineSeeder.php'),
        ], 'cultural-engine-seeders');

        $this->publishes([
            __DIR__ . '/../stubs/filament' => app_path('Filament/Admin/Resources'),
        ], 'cultural-engine-filament');
    }
}
SERVICE_PROVIDER

echo "===> Step 4: Creating config file..."
cat > "$PACKAGE_DIR/config/cultural_engine.php" << 'CONFIG'
<?php

return [
    'default_culture_key'   => 'sa_marketing',
    'default_tone_key'      => 'friendly',
    'default_industry_key'  => 'generic',
    'default_task_key'      => 'translation.general',

    'fallback_prompt' => <<<PROMPT
You are a professional cultural translator. 
Translate the following text from {source_lang} to {target_lang}, preserving the emotional intent and adapting it to the target culture. 
Avoid literal translation and prefer natural, human-like phrasing.
PROMPT,

    'max_source_length' => 8000,
];
CONFIG

echo "===> Step 5: Creating Models..."

# CultureProfile Model
cat > "$PACKAGE_DIR/src/Models/CultureProfile.php" << 'MODEL_CULTURE'
<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class CultureProfile extends Model
{
    protected $table = 'culture_profiles';

    protected $fillable = [
        'key',
        'name',
        'locale',
        'country_code',
        'description',
        'audience_notes',
        'constraints',
        'is_default',
    ];

    protected $casts = [
        'constraints' => 'array',
        'is_default'  => 'boolean',
    ];
}
MODEL_CULTURE

# EmotionalTone Model
cat > "$PACKAGE_DIR/src/Models/EmotionalTone.php" << 'MODEL_TONE'
<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class EmotionalTone extends Model
{
    protected $table = 'emotional_tones';

    protected $fillable = [
        'key',
        'label',
        'description',
        'intensity',
    ];
}
MODEL_TONE

# Industry Model
cat > "$PACKAGE_DIR/src/Models/Industry.php" << 'MODEL_INDUSTRY'
<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $table = 'industries';

    protected $fillable = [
        'key',
        'name',
        'description',
    ];
}
MODEL_INDUSTRY

# TaskTemplate Model
cat > "$PACKAGE_DIR/src/Models/TaskTemplate.php" << 'MODEL_TASK'
<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    protected $table = 'task_templates';

    protected $fillable = [
        'key',
        'name',
        'type',
        'category',
        'industry_key',
        'base_prompt',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
MODEL_TASK

echo "===> Step 6: Creating Services..."

# CulturalPromptBuilder
cat > "$PACKAGE_DIR/src/Services/CulturalPromptBuilder.php" << 'SERVICE_BUILDER'
<?php

namespace CulturalTranslate\CulturalEngine\Services;

use CulturalTranslate\CulturalEngine\Models\CultureProfile;
use CulturalTranslate\CulturalEngine\Models\EmotionalTone;
use CulturalTranslate\CulturalEngine\Models\Industry;
use CulturalTranslate\CulturalEngine\Models\TaskTemplate;

class CulturalPromptBuilder
{
    public function __construct(
        protected array $config = []
    ) {}

    public function buildPrompt(array $params): string
    {
        $sourceText   = $params['source_text']   ?? '';
        $sourceLang   = $params['source_lang']   ?? $params['source_language'] ?? 'auto';
        $targetLang   = $params['target_lang']   ?? $params['target_language'] ?? 'en';
        $cultureKey   = $params['culture_key']   ?? $this->config['default_culture_key'];
        $toneKey      = $params['tone_key']      ?? $this->config['default_tone_key'];
        $industryKey  = $params['industry_key']  ?? $this->config['default_industry_key'];
        $taskKey      = $params['task_key']      ?? $this->config['default_task_key'];
        $extraContext = $params['context']       ?? '';

        $culture  = CultureProfile::where('key', $cultureKey)->first();
        $tone     = EmotionalTone::where('key', $toneKey)->first();
        $industry = Industry::where('key', $industryKey)->first();
        $task     = TaskTemplate::where('key', $taskKey)->first();

        if (! $task) {
            return strtr($this->config['fallback_prompt'] ?? '', [
                '{source_lang}' => $sourceLang,
                '{target_lang}' => $targetLang,
                '{source_text}' => $sourceText,
            ]);
        }

        $prompt = $task->base_prompt;

        $promptParts = [
            '{source_lang}'   => $sourceLang,
            '{target_lang}'   => $targetLang,
            '{source_text}'   => $sourceText,
            '{extra_context}' => $extraContext,
            '{culture_notes}' => $culture?->audience_notes ?? '',
            '{culture_desc}'  => $culture?->description ?? '',
            '{tone_label}'    => $tone?->label ?? '',
            '{tone_desc}'     => $tone?->description ?? '',
            '{industry_name}' => $industry?->name ?? 'general',
            '{industry_desc}' => $industry?->description ?? '',
        ];

        return strtr($prompt, $promptParts);
    }
}
SERVICE_BUILDER

# CulturalPostProcessor
cat > "$PACKAGE_DIR/src/Services/CulturalPostProcessor.php" << 'SERVICE_PROCESSOR'
<?php

namespace CulturalTranslate\CulturalEngine\Services;

class CulturalPostProcessor
{
    public function __construct(
        protected array $config = []
    ) {}

    public function cleanOutput(string $text): string
    {
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        return trim($text);
    }
}
SERVICE_PROCESSOR

echo "===> Step 7: Creating Migrations..."

# Culture Profiles Migration
cat > "$PACKAGE_DIR/database/migrations/2025_11_24_100001_create_culture_profiles_table.php" << 'MIGRATION_CULTURE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('culture_profiles')) {
            Schema::create('culture_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('name');
                $table->string('locale')->nullable();
                $table->string('country_code', 5)->nullable();
                $table->text('description')->nullable();
                $table->text('audience_notes')->nullable();
                $table->json('constraints')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('culture_profiles');
    }
};
MIGRATION_CULTURE

# Emotional Tones Migration
cat > "$PACKAGE_DIR/database/migrations/2025_11_24_100002_create_emotional_tones_table.php" << 'MIGRATION_TONE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('emotional_tones')) {
            Schema::create('emotional_tones', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('label');
                $table->text('description')->nullable();
                $table->unsignedTinyInteger('intensity')->default(5);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('emotional_tones');
    }
};
MIGRATION_TONE

# Industries Migration
cat > "$PACKAGE_DIR/database/migrations/2025_11_24_100003_create_industries_table.php" << 'MIGRATION_INDUSTRY'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('industries')) {
            Schema::create('industries', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('industries');
    }
};
MIGRATION_INDUSTRY

# Task Templates Migration
cat > "$PACKAGE_DIR/database/migrations/2025_11_24_100004_create_task_templates_table.php" << 'MIGRATION_TASK'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('task_templates')) {
            Schema::create('task_templates', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('name');
                $table->string('type')->default('translation');
                $table->string('category')->nullable();
                $table->string('industry_key')->nullable();
                $table->text('base_prompt');
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('task_templates');
    }
};
MIGRATION_TASK

echo "===> Step 8: Creating Seeder..."
cat > "$PACKAGE_DIR/database/seeders/CulturalEngineSeeder.php" << 'SEEDER'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use CulturalTranslate\CulturalEngine\Models\CultureProfile;
use CulturalTranslate\CulturalEngine\Models\EmotionalTone;
use CulturalTranslate\CulturalEngine\Models\Industry;
use CulturalTranslate\CulturalEngine\Models\TaskTemplate;

class CulturalEngineSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Cultures
        $cultures = [
            [
                'key'           => 'sa_marketing',
                'name'          => 'Saudi Arabia – Marketing',
                'locale'        => 'ar-SA',
                'country_code'  => 'SA',
                'description'   => 'Conservative, family-oriented, religiously sensitive, prefers respectful and inspiring tone.',
                'audience_notes'=> 'Avoid direct confrontation, use respectful forms, mention trust and family values.',
                'constraints'   => ['avoid' => ['offensive_jokes', 'gambling', 'alcohol']],
                'is_default'    => true,
            ],
            [
                'key'           => 'nl_direct',
                'name'          => 'Netherlands – Direct Communication',
                'locale'        => 'nl-NL',
                'country_code'  => 'NL',
                'description'   => 'Very direct, values clarity, honesty, and practicality.',
                'audience_notes'=> 'Be clear and to the point, avoid overselling, focus on facts and benefits.',
                'constraints'   => ['avoid' => ['exaggeration', 'too_many_superlatives']],
                'is_default'    => false,
            ],
            [
                'key'           => 'uae_b2b',
                'name'          => 'UAE – B2B Professional',
                'locale'        => 'ar-AE',
                'country_code'  => 'AE',
                'description'   => 'Professional, bilingual culture with mix of Arabic and English business tones.',
                'audience_notes'=> 'Formal yet warm, highlight innovation and reliability.',
                'constraints'   => ['avoid' => ['political_topics']],
                'is_default'    => false,
            ],
        ];

        foreach ($cultures as $data) {
            CultureProfile::updateOrCreate(['key' => $data['key']], $data);
        }

        // 2) Tones
        $tones = [
            ['key' => 'friendly', 'label' => 'Friendly & Warm', 'description' => 'Warm, human, and accessible tone that builds trust.', 'intensity' => 7],
            ['key' => 'formal',   'label' => 'Formal & Professional', 'description' => 'Clear, respectful, and structured tone for business or official content.', 'intensity' => 6],
            ['key' => 'emotional','label' => 'Emotional & Inspiring', 'description' => 'Evokes feelings of hope, motivation, or empathy.', 'intensity' => 8],
            ['key' => 'direct',   'label' => 'Direct & Straightforward','description' => 'No fluff, straight to the point, ideal for Dutch/German audiences.', 'intensity' => 5],
        ];

        foreach ($tones as $data) {
            EmotionalTone::updateOrCreate(['key' => $data['key']], $data);
        }

        // 3) Industries
        $industries = [
            ['key' => 'generic',   'name' => 'Generic / Mixed', 'description' => 'Generic content not tied to a specific industry.'],
            ['key' => 'ecommerce', 'name' => 'E-Commerce',      'description' => 'Product pages, offers, landing pages, email campaigns.'],
            ['key' => 'saas',      'name' => 'SaaS & Tech',     'description' => 'Apps, platforms, onboarding, changelogs, feature pages.'],
            ['key' => 'tourism',   'name' => 'Tourism & Travel','description' => 'Travel packages, hotels, experiences.'],
            ['key' => 'finance',   'name' => 'Finance & Fintech','description'=> 'Fintech apps, payment platforms, bank marketing.'],
        ];

        foreach ($industries as $data) {
            Industry::updateOrCreate(['key' => $data['key']], $data);
        }

        // 4) Task Templates
        $tasks = [
            [
                'key'          => 'translation.general',
                'name'         => 'General Cultural Translation',
                'type'         => 'translation',
                'category'     => 'generic',
                'industry_key' => 'generic',
                'base_prompt'  => 'You are a cultural translator.

Goal:
- Translate the following text from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Respect the audience: {audience_notes}
- Use the tone: {tone_label} – {tone_desc}
- Consider the industry: {industry_name} – {industry_desc}

Instructions:
- Do NOT translate word-by-word.
- Rephrase to sound natural and human in the target language.
- If the text contains idioms, adapt them to culturally equivalent expressions.
- If something is inappropriate for this culture, replace it with a culturally safe alternative.

Additional context from the client:
{extra_context}

Source text:
"""{source_text}"""

Now produce only the final translated text, without explanations.',
                'meta' => ['example_use' => 'Any general marketing or communication text.'],
            ],

            [
                'key'          => 'translation.ad_copy_short',
                'name'         => 'Short Ad Copy (Performance Marketing)',
                'type'         => 'translation',
                'category'     => 'ads',
                'industry_key' => 'ecommerce',
                'base_prompt'  => 'You are a performance marketing copywriter and cultural translator.

Task:
- Rewrite the following short ad copy from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Audience notes: {audience_notes}
- Tone: {tone_label} – {tone_desc}
- Industry: {industry_name} – {industry_desc}

Requirements:
- Keep it short, punchy, and emotionally engaging.
- Focus on one clear benefit for the reader.
- Avoid any cultural or legal issues for this market.
- Suggest 2 variations separated by "---".

Extra context:
{extra_context}

Original ad copy:
"""{source_text}"""',
                'meta' => ['variations' => 2],
            ],

            [
                'key'          => 'email.welcome_series',
                'name'         => 'Welcome Email – Warm & Trust-Building',
                'type'         => 'email',
                'category'     => 'lifecycle',
                'industry_key' => 'saas',
                'base_prompt'  => 'You are a lifecycle email copywriter and cultural translator.

Task:
- Transform the following draft into a culturally adapted welcome email.
- From {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}
- Industry: {industry_name} – {industry_desc}

Requirements:
- Start with a warm, personal greeting (adapted to culture).
- Briefly explain the main value of the product/service.
- Add 2–3 clear next steps (CTAs).
- End with a simple, human closing line.

Context:
{extra_context}

Draft email:
"""{source_text}"""',
                'meta' => ['channel' => 'email', 'stage' => 'welcome'],
            ],

            [
                'key'          => 'product.description',
                'name'         => 'Product Page Description (E-Commerce)',
                'type'         => 'product',
                'category'     => 'product_page',
                'industry_key' => 'ecommerce',
                'base_prompt'  => 'You are an e-commerce copywriter and cultural translator.

Task:
- Rewrite the following product description from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Tone: {tone_label} – {tone_desc}
- Audience: {audience_notes}

Requirements:
- Start with a 1–2 sentence hook highlighting the main benefit.
- Then write 3–5 bullet points with key features and benefits.
- Make sure all claims are realistic and acceptable in this market.
- Avoid offensive or culturally sensitive terms.

Context:
{extra_context}

Original product description:
"""{source_text}"""',
                'meta' => ['channel' => 'web', 'section' => 'product'],
            ],
        ];

        foreach ($tasks as $data) {
            TaskTemplate::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
SEEDER

echo "===> Step 9: Updating main composer.json..."

# Backup original composer.json
cp "$PROJECT_ROOT/composer.json" "$PROJECT_ROOT/composer.json.backup"

# Update composer.json to include package
php -r '
$file = "/var/www/cultural-translate-platform/composer.json";
$json = json_decode(file_get_contents($file), true);

// Add autoload
if (!isset($json["autoload"]["psr-4"]["CulturalTranslate\\\\CulturalEngine\\\\"])) {
    $json["autoload"]["psr-4"]["CulturalTranslate\\\\CulturalEngine\\\\"] = "packages/culturaltranslate/cultural-engine/src/";
}

// Add repository
$repoExists = false;
if (isset($json["repositories"])) {
    foreach ($json["repositories"] as $repo) {
        if (isset($repo["url"]) && $repo["url"] === "packages/culturaltranslate/cultural-engine") {
            $repoExists = true;
            break;
        }
    }
}

if (!$repoExists) {
    if (!isset($json["repositories"])) {
        $json["repositories"] = [];
    }
    $json["repositories"][] = [
        "type" => "path",
        "url" => "packages/culturaltranslate/cultural-engine",
        "options" => ["symlink" => true]
    ];
}

// Add require
if (!isset($json["require"]["culturaltranslate/cultural-engine"])) {
    $json["require"]["culturaltranslate/cultural-engine"] = "*";
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
'

echo "===> Step 10: Running composer commands..."
composer dump-autoload -o
composer update culturaltranslate/cultural-engine --no-scripts

echo "===> Step 11: Clearing cache..."
php artisan optimize:clear || true

echo "===> Step 12: Publishing package assets..."
php artisan vendor:publish --provider="CulturalTranslate\CulturalEngine\CulturalEngineServiceProvider" --tag="cultural-engine-config" --force
php artisan vendor:publish --provider="CulturalTranslate\CulturalEngine\CulturalEngineServiceProvider" --tag="cultural-engine-seeders" --force

echo "===> Step 13: Running migrations..."
php artisan migrate --force

echo "===> Step 14: Running seeder..."
php artisan db:seed --class="Database\Seeders\CulturalEngineSeeder" --force

echo "===> Step 15: Optimizing application..."
php artisan optimize

echo ""
echo "============================================"
echo "✅ Cultural Engine installed successfully!"
echo "============================================"
echo ""
echo "Package location: $PACKAGE_DIR"
echo "Config published to: config/cultural_engine.php"
echo "Seeder published to: database/seeders/CulturalEngineSeeder.php"
echo ""
echo "You can now:"
echo "  - Manage cultures, tones, industries, and task templates from Filament"
echo "  - Use CulturalPromptBuilder in your TranslationService"
echo "  - Extend the seeder with more task templates"
echo ""
