<?php

/**
 * CulturalTranslate - OpenAI Translation Script
 * 
 * This script translates all website text to 15 languages using OpenAI API
 * Translations are saved to files for permanent use (no API calls on each page load)
 * 
 * Usage: php translate_with_openai.php [language_code]
 * Example: php translate_with_openai.php ar
 * Or: php translate_with_openai.php all
 */

// Configuration
$config = [
    'api_key' => '', // Will be loaded from .env
    'model' => 'gpt-4o-mini', // Cost-effective and fast
    'batch_size' => 50, // Translate 50 strings at a time
    'delay_between_batches' => 1, // Seconds between API calls
];

// Protected words/names that should NOT be translated
$protectedTerms = [
    'CulturalTranslate',
    'Cultural Translate',
    'Laravel',
    'Stripe',
    'OpenAI',
    'GPT-4',
    'API',
    'SSL',
    'HTTPS',
    'PDF',
    'CSV',
    'Excel',
    'Google',
    'Microsoft',
    'Apple',
    'PayPal',
    'Visa',
    'Mastercard',
    'WhatsApp',
    'Telegram',
    'Facebook',
    'Twitter',
    'LinkedIn',
    'Instagram',
    'YouTube',
    'GitHub',
    'ISO',
    'GDPR',
    'EU',
    'UNESCO',
    'NAATI',
    'ATA',
    'DIN',
    'EN 15038',
    'ISO 17100',
];

// Target languages with their full names
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
    'zh' => ['name' => 'Chinese', 'native' => '中文', 'rtl' => false],
];

// Load .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return [];
    }
    $env = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Support optional leading "export "
        if (str_starts_with($key, 'export ')) {
            $key = trim(substr($key, 7));
        }
        // Strip surrounding quotes only
        if ($value !== '' && (
            ($value[0] === '"' && substr($value, -1) === '"') ||
            ($value[0] === "'" && substr($value, -1) === "'")
        )) {
            $value = substr($value, 1, -1);
        }
        $env[$key] = $value;
    }
    return $env;
}

function atomicWriteFile($path, $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new Exception("Failed to create directory: {$dir}");
        }
    }

    $tmp = $path . '.tmp.' . getmypid() . '.' . bin2hex(random_bytes(6));
    $bytes = file_put_contents($tmp, $content, LOCK_EX);
    if ($bytes === false) {
        @unlink($tmp);
        throw new Exception("Failed to write temp file: {$tmp}");
    }

    // On Windows, rename() may fail if destination exists.
    if (file_exists($path)) {
        @unlink($path);
    }
    if (!rename($tmp, $path)) {
        @unlink($tmp);
        throw new Exception("Failed to move temp file into place: {$path}");
    }
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

// Unflatten dot notation to nested array
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

// Call OpenAI API
function translateWithOpenAI($texts, $targetLang, $langName, $apiKey, $protectedTerms, $model = 'gpt-4o-mini') {
    $protectedList = implode(', ', $protectedTerms);
    
    $prompt = "You are a professional translator. Translate the following English texts to {$langName}.

IMPORTANT RULES:
1. Keep these terms EXACTLY as they are (do not translate): {$protectedList}
2. Maintain the same tone and style
3. Keep any placeholders like :name, :count, :amount unchanged
4. Keep HTML tags unchanged if present
5. For UI text, keep it concise and natural
6. Return ONLY a JSON object with the same keys and translated values
7. Do not add any explanation or markdown formatting

Input JSON:
" . json_encode($texts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $maxAttempts = 5;
    $baseDelaySeconds = 2;
    $lastError = null;

    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional translator. Return only valid JSON.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
                'max_tokens' => 4000,
            ], JSON_UNESCAPED_UNICODE),
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT => 120,
        ]);

        $response = curl_exec($ch);
        $curlErrNo = curl_errno($ch);
        $curlErr = $curlErrNo ? curl_error($ch) : null;
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $shouldRetry = false;
        if ($response === false) {
            $lastError = "cURL error ({$curlErrNo}): {$curlErr}";
            $shouldRetry = true;
        } elseif ($httpCode === 429 || ($httpCode >= 500 && $httpCode <= 599)) {
            $error = json_decode($response, true);
            $msg = $error['error']['message'] ?? "HTTP {$httpCode}";
            $lastError = "API transient error ({$httpCode}): {$msg}";
            $shouldRetry = true;
        } elseif ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new Exception("API Error ({$httpCode}): " . ($error['error']['message'] ?? $response));
        }

        if ($shouldRetry) {
            if ($attempt === $maxAttempts) {
                throw new Exception($lastError ?? 'OpenAI request failed');
            }
            $sleep = min(30, $baseDelaySeconds * (2 ** ($attempt - 1))) + (mt_rand(0, 1000) / 1000);
            usleep((int)($sleep * 1_000_000));
            continue;
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            throw new Exception('Invalid API response JSON');
        }
        $content = $data['choices'][0]['message']['content'] ?? '';
    
        // Clean up response - remove markdown code blocks if present
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*/', '', $content);
        $content = trim($content);

        $translated = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($translated)) {
            throw new Exception("Invalid JSON content from model: " . json_last_error_msg() . "\nContent: " . substr($content, 0, 500));
        }

        $inKeys = array_keys($texts);
        $outKeys = array_keys($translated);
        $missing = array_diff($inKeys, $outKeys);
        $extra = array_diff($outKeys, $inKeys);
        if (!empty($missing) || !empty($extra)) {
            throw new Exception('Model output keys mismatch (missing: ' . count($missing) . ', extra: ' . count($extra) . ')');
        }

        return $translated;
    }

    throw new Exception($lastError ?? 'OpenAI request failed');
}

// Generate PHP array file content
function generatePhpFile($array, $langCode, $langName) {
    $date = date('Y-m-d H:i:s');
    $content = "<?php\n\n";
    $content .= "/**\n";
    $content .= " * CulturalTranslate - {$langName} Translation File\n";
    $content .= " * Auto-generated by OpenAI Translation Script\n";
    $content .= " * Generated: {$date}\n";
    $content .= " * \n";
    $content .= " * DO NOT EDIT MANUALLY - Changes will be overwritten\n";
    $content .= " * Edit lang/en/messages.php and run: php translate_with_openai.php {$langCode}\n";
    $content .= " */\n\n";
    $content .= "return " . varExportFormatted($array) . ";\n";
    return $content;
}

// Format var_export output nicely
function varExportFormatted($var, $indent = '') {
    if (is_array($var)) {
        $indexed = array_keys($var) === range(0, count($var) - 1);
        $r = [];
        foreach ($var as $key => $value) {
            $r[] = $indent . '    '
                . ($indexed ? '' : varExportFormatted($key) . ' => ')
                . varExportFormatted($value, $indent . '    ');
        }
        return "[\n" . implode(",\n", $r) . ",\n{$indent}]";
    }
    return var_export($var, true);
}

// Main execution
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     CulturalTranslate - OpenAI Translation System           ║\n";
echo "╠══════════════════════════════════════════════════════════════╣\n";
echo "║  Translates all website text and saves to files             ║\n";
echo "║  Uses GPT-4o-mini for cost-effective, high-quality results  ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Load API key from .env
$envPath = __DIR__ . '/.env';
$env = loadEnv($envPath);
$config['api_key'] = $env['OPENAI_API_KEY'] ?? '';

if (empty($config['api_key'])) {
    die("Error: OPENAI_API_KEY not found in .env file\n");
}

echo "✓ API Key loaded from .env\n";

// Load English source file
$englishFile = __DIR__ . '/lang/en/messages.php';
if (!file_exists($englishFile)) {
    die("Error: English source file not found: {$englishFile}\n");
}

$englishTranslations = require $englishFile;
$flatEnglish = flattenArray($englishTranslations);
$totalStrings = count($flatEnglish);

echo "✓ Loaded {$totalStrings} strings from English source\n\n";

// Determine which languages to translate
$targetLangCode = $argv[1] ?? 'all';

if ($targetLangCode === 'all') {
    $langsToTranslate = $languages;
    echo "Translating to ALL " . count($languages) . " languages...\n\n";
} elseif (isset($languages[$targetLangCode])) {
    $langsToTranslate = [$targetLangCode => $languages[$targetLangCode]];
    echo "Translating to {$languages[$targetLangCode]['name']} only...\n\n";
} else {
    echo "Invalid language code: {$targetLangCode}\n";
    echo "Available: " . implode(', ', array_keys($languages)) . ", all\n";
    exit(1);
}

// Process each language
$successCount = 0;
$errorCount = 0;

foreach ($langsToTranslate as $langCode => $langInfo) {
    $langName = $langInfo['name'];
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  Translating to {$langName} ({$langCode})...\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $translated = [];
    $batches = array_chunk($flatEnglish, $config['batch_size'], true);
    $batchCount = count($batches);
    
    echo "  Processing {$batchCount} batches of {$config['batch_size']} strings each...\n\n";
    
    $batchNum = 0;
    foreach ($batches as $batch) {
        $batchNum++;
        $progress = round(($batchNum / $batchCount) * 100);
        echo "  [{$batchNum}/{$batchCount}] {$progress}% - Translating batch... ";
        
        try {
            $translatedBatch = translateWithOpenAI(
                $batch,
                $langCode,
                $langName,
                $config['api_key'],
                $protectedTerms,
                $config['model']
            );
            
            $translated = array_merge($translated, $translatedBatch);
            echo "✓ Done (" . count($translatedBatch) . " strings)\n";
            
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            // On error, keep original English text for this batch
            $translated = array_merge($translated, $batch);
            $errorCount++;
        }
        
        // Delay between batches to avoid rate limits
        if ($batchNum < $batchCount) {
            sleep($config['delay_between_batches']);
        }
    }
    
    // Convert flat array back to nested structure
    $nestedTranslations = unflattenArray($translated);
    
    // Create language directory if not exists
    $langDir = __DIR__ . "/lang/{$langCode}";
    if (!is_dir($langDir)) {
        mkdir($langDir, 0755, true);
    }
    
    // Save to file
    $outputFile = "{$langDir}/messages.php";
    $phpContent = generatePhpFile($nestedTranslations, $langCode, $langName);
    atomicWriteFile($outputFile, $phpContent);
    
    echo "\n  ✓ Saved to {$outputFile}\n";
    echo "  Total strings: " . count($translated) . "\n\n";
    
    $successCount++;
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "  TRANSLATION COMPLETE!\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  Languages processed: {$successCount}\n";
echo "  Errors encountered: {$errorCount}\n";
echo "  Total strings per language: {$totalStrings}\n";
echo "\n";
echo "  Translations are saved in lang/[code]/messages.php files.\n";
echo "  The website will use these files directly - no API calls needed!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
