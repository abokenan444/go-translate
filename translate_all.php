<?php

/**
 * CulturalTranslate - Batch Translation Script using OpenAI API
 * 
 * This script translates all website content to 14 languages
 * and saves them to language files for instant loading.
 * 
 * Usage: php translate_all.php [language_code]
 * Example: php translate_all.php ar
 * Example: php translate_all.php all
 */

// Configuration
$config = [
    'api_key' => '', // Will be loaded from .env
    'model' => 'gpt-4o-mini', // Cost-effective model
    'batch_size' => 20, // Translate 20 strings at once
    'delay_between_batches' => 1, // Seconds delay to avoid rate limits
];

// Protected words that should NEVER be translated
$doNotTranslate = [
    'CulturalTranslate',
    'Cultural Translate', 
    'culturaltranslate.com',
    'Stripe',
    'PayPal',
    'Google',
    'Microsoft',
    'OpenAI',
    'GPT-4',
    'Claude',
    'Laravel',
    'PHP',
    'API',
    'OAuth',
    'SSL',
    'HTTPS',
    'PDF',
    'CSV',
    'JSON',
    'XML',
    'HTML',
    'CSS',
    'AI',
    'ML',
    'NLP',
    'OCR',
    'TTS',
    'STT',
    'FAQ',
    'SaaS',
    'B2B',
    'B2C',
    'EUR',
    'USD',
    'ISO',
    'GDPR',
    'HIPAA',
    'SOC2',
    'AWS',
    'Azure',
    'WhatsApp',
    'Telegram',
    'LinkedIn',
    'Twitter',
    'Facebook',
    'Instagram',
    'YouTube',
];

// Supported languages
$languages = [
    'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
    'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
    'es' => ['name' => 'Spanish', 'native' => 'Español', 'rtl' => false],
    'fr' => ['name' => 'French', 'native' => 'Français', 'rtl' => false],
    'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'rtl' => false],
    'it' => ['name' => 'Italian', 'native' => 'Italiano', 'rtl' => false],
    'ja' => ['name' => 'Japanese', 'native' => '日本語', 'rtl' => false],
    'ko' => ['name' => 'Korean', 'native' => '한국어', 'rtl' => false],
    'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'rtl' => false],
    'pl' => ['name' => 'Polish', 'native' => 'Polski', 'rtl' => false],
    'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'rtl' => false],
    'ru' => ['name' => 'Russian', 'native' => 'Русский', 'rtl' => false],
    'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'rtl' => false],
    'zh' => ['name' => 'Chinese (Simplified)', 'native' => '简体中文', 'rtl' => false],
];

// Load API key from .env
function loadApiKey() {
    $envFile = __DIR__ . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, 'OPENAI_API_KEY=') === 0) {
                return trim(str_replace('OPENAI_API_KEY=', '', $line));
            }
        }
    }
    return null;
}

// Flatten nested array to dot notation
function flattenArray($array, $prefix = '') {
    $result = [];
    foreach ($array as $key => $value) {
        $newKey = $prefix ? "{$prefix}.{$key}" : $key;
        if (is_array($value)) {
            $result = array_merge($result, flattenArray($value, $newKey));
        } else {
            $result[$newKey] = $value;
        }
    }
    return $result;
}

// Unflatten dot notation back to nested array
function unflattenArray($array) {
    $result = [];
    foreach ($array as $key => $value) {
        $keys = explode('.', $key);
        $current = &$result;
        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                $current[$k] = $value;
            } else {
                if (!isset($current[$k]) || !is_array($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
        }
    }
    return $result;
}

// Translate batch of strings using OpenAI
function translateBatch($strings, $targetLang, $langName, $apiKey, $doNotTranslate, $model) {
    $protectedWordsStr = implode(', ', $doNotTranslate);
    
    // Build the batch prompt
    $textsJson = json_encode($strings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    $prompt = <<<PROMPT
Translate the following JSON object values from English to {$langName}.

CRITICAL RULES:
1. DO NOT translate these brand names and technical terms (keep them exactly as they are): {$protectedWordsStr}
2. Keep JSON keys EXACTLY as they are (do not translate keys)
3. Keep any placeholders like :name, :count, {variable}, %s exactly as they are
4. Maintain the same tone and formality level
5. For Arabic, use Modern Standard Arabic
6. For Chinese, use Simplified Chinese
7. Return ONLY valid JSON with translated values, nothing else

JSON to translate:
{$textsJson}
PROMPT;

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional translator for website localization. Return ONLY valid JSON with translated values. Never translate JSON keys or placeholders.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 4000,
        ]),
        CURLOPT_TIMEOUT => 120,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        echo "  ⚠️  API Error (HTTP {$httpCode})\n";
        return $strings; // Return original on error
    }
    
    $data = json_decode($response, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    $tokensUsed = $data['usage']['total_tokens'] ?? 0;
    
    // Extract JSON from response (in case there's extra text)
    if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
        $content = $matches[0];
    }
    
    $translated = json_decode($content, true);
    
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($translated)) {
        echo "  ⚠️  JSON parse error, retrying with simpler approach...\n";
        return $strings; // Return original on parse error
    }
    
    return ['translations' => $translated, 'tokens' => $tokensUsed];
}

// Generate PHP array string
function arrayToPhpString($array, $indent = 1) {
    $str = "[\n";
    $padding = str_repeat('    ', $indent);
    
    foreach ($array as $key => $value) {
        $keyStr = is_string($key) ? "'" . addslashes($key) . "'" : $key;
        
        if (is_array($value)) {
            $str .= "{$padding}{$keyStr} => " . arrayToPhpString($value, $indent + 1) . ",\n";
        } else {
            $valueStr = "'" . addslashes($value) . "'";
            $str .= "{$padding}{$keyStr} => {$valueStr},\n";
        }
    }
    
    $closePadding = str_repeat('    ', $indent - 1);
    $str .= "{$closePadding}]";
    
    return $str;
}

// Main execution
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     CulturalTranslate - OpenAI Batch Translation System      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Load API key
$config['api_key'] = loadApiKey();
if (empty($config['api_key'])) {
    echo "❌ Error: OpenAI API key not found!\n";
    echo "   Please set OPENAI_API_KEY in your .env file\n\n";
    exit(1);
}
echo "✅ API Key loaded\n";

// Load English source
$englishFile = __DIR__ . '/lang/en/messages.php';
if (!file_exists($englishFile)) {
    echo "❌ Error: English source file not found!\n";
    echo "   Expected: {$englishFile}\n\n";
    exit(1);
}

$englishTranslations = include $englishFile;
$flattened = flattenArray($englishTranslations);
$totalStrings = count($flattened);

echo "📝 Loaded {$totalStrings} strings from English source\n";
echo "🚫 Protected words: " . count($doNotTranslate) . " brand names/terms\n";
echo "\n";

// Determine which languages to process
$targetLang = $argv[1] ?? null;

if (!$targetLang) {
    echo "Usage: php translate_all.php [language_code|all]\n\n";
    echo "Available languages:\n";
    foreach ($languages as $code => $info) {
        echo "  {$code} - {$info['name']} ({$info['native']})\n";
    }
    echo "  all - Translate to all languages\n\n";
    exit(0);
}

$languagesToProcess = [];
if ($targetLang === 'all') {
    $languagesToProcess = $languages;
} elseif (isset($languages[$targetLang])) {
    $languagesToProcess[$targetLang] = $languages[$targetLang];
} else {
    echo "❌ Unknown language: {$targetLang}\n";
    exit(1);
}

$totalTokensUsed = 0;
$totalTranslated = 0;

// Process each language
foreach ($languagesToProcess as $langCode => $langInfo) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔄 Translating to {$langInfo['name']} ({$langInfo['native']})...\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Check existing translations
    $targetFile = __DIR__ . "/lang/{$langCode}/messages.php";
    $existingTranslations = [];
    $existingFlat = [];
    
    if (file_exists($targetFile)) {
        $existingTranslations = include $targetFile;
        $existingFlat = flattenArray($existingTranslations);
        echo "📂 Found " . count($existingFlat) . " existing translations\n";
    }
    
    // Find strings that need translation
    $toTranslate = [];
    foreach ($flattened as $key => $value) {
        if (!isset($existingFlat[$key]) || empty($existingFlat[$key])) {
            $toTranslate[$key] = $value;
        }
    }
    
    if (empty($toTranslate)) {
        echo "✅ All strings already translated!\n\n";
        continue;
    }
    
    echo "📋 Need to translate: " . count($toTranslate) . " strings\n\n";
    
    // Process in batches
    $batches = array_chunk($toTranslate, $config['batch_size'], true);
    $batchCount = count($batches);
    $currentBatch = 0;
    $translated = $existingFlat;
    
    foreach ($batches as $batch) {
        $currentBatch++;
        $batchKeys = array_keys($batch);
        echo "  Batch {$currentBatch}/{$batchCount} (" . count($batch) . " strings)... ";
        
        $result = translateBatch(
            $batch, 
            $langCode, 
            $langInfo['name'], 
            $config['api_key'], 
            $doNotTranslate,
            $config['model']
        );
        
        if (isset($result['translations'])) {
            foreach ($result['translations'] as $key => $value) {
                $translated[$key] = $value;
                $totalTranslated++;
            }
            $totalTokensUsed += $result['tokens'];
            echo "✓ ({$result['tokens']} tokens)\n";
        } else {
            // Fallback - keep original
            foreach ($batch as $key => $value) {
                $translated[$key] = $value;
            }
            echo "⚠️ (using original)\n";
        }
        
        // Delay between batches to avoid rate limits
        if ($currentBatch < $batchCount) {
            sleep($config['delay_between_batches']);
        }
    }
    
    // Convert back to nested array
    $nestedTranslations = unflattenArray($translated);
    
    // Ensure directory exists
    $langDir = __DIR__ . "/lang/{$langCode}";
    if (!is_dir($langDir)) {
        mkdir($langDir, 0755, true);
    }
    
    // Save translations
    $phpContent = "<?php\n\n";
    $phpContent .= "/**\n";
    $phpContent .= " * {$langInfo['name']} ({$langInfo['native']}) Translations\n";
    $phpContent .= " * Auto-generated by CulturalTranslate OpenAI Translation System\n";
    $phpContent .= " * Generated at: " . date('Y-m-d H:i:s') . "\n";
    $phpContent .= " * \n";
    $phpContent .= " * DO NOT EDIT MANUALLY - Run 'php translate_all.php {$langCode}' to update\n";
    $phpContent .= " */\n\n";
    $phpContent .= "return " . arrayToPhpString($nestedTranslations) . ";\n";
    
    file_put_contents($targetFile, $phpContent);
    echo "\n✅ Saved to {$targetFile}\n\n";
}

// Final summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    Translation Summary                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "✅ Total strings translated: {$totalTranslated}\n";
echo "🎯 Total tokens used: {$totalTokensUsed}\n";
$estimatedCost = ($totalTokensUsed / 1000000) * 0.15; // GPT-4o-mini pricing: $0.15/1M input tokens
echo "💰 Estimated cost: $" . number_format($estimatedCost, 4) . "\n";
echo "\n";
echo "🎉 Translation complete! Files saved in /lang/{code}/messages.php\n\n";
