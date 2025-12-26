<?php
/**
 * Quick Translation Script for Flat Keys
 * Translates the new flat-key messages.php to all languages
 */

function loadEnvValue(string $key): ?string
{
    $envFile = __DIR__ . '/.env';
    if (!file_exists($envFile)) {
        return getenv($key) !== false ? (string) getenv($key) : null;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        // Support "export KEY=value"
        if (str_starts_with($line, 'export ')) {
            $line = trim(substr($line, 7));
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        if ($k !== $key) {
            continue;
        }

        $v = trim($v);
        // Strip surrounding quotes
        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }
        return $v;
    }

    return getenv($key) !== false ? (string) getenv($key) : null;
}

$apiKey = loadEnvValue('OPENAI_API_KEY');
if (!$apiKey) {
    die("Error: OPENAI_API_KEY not found in .env\n");
}

// Load English source
$englishFile = __DIR__ . '/lang/en/messages.php';
$english = include $englishFile;

echo "✓ Loaded " . count($english) . " strings from English source\n\n";

// Target languages
$languages = [
    'ar' => ['name' => 'Arabic', 'native' => 'العربية'],
    'de' => ['name' => 'German', 'native' => 'Deutsch'],
    'es' => ['name' => 'Spanish', 'native' => 'Español'],
    'fr' => ['name' => 'French', 'native' => 'Français'],
    'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी'],
    'it' => ['name' => 'Italian', 'native' => 'Italiano'],
    'ja' => ['name' => 'Japanese', 'native' => '日本語'],
    'ko' => ['name' => 'Korean', 'native' => '한국어'],
    'nl' => ['name' => 'Dutch', 'native' => 'Nederlands'],
    'pl' => ['name' => 'Polish', 'native' => 'Polski'],
    'pt' => ['name' => 'Portuguese', 'native' => 'Português'],
    'ru' => ['name' => 'Russian', 'native' => 'Русский'],
    'tr' => ['name' => 'Turkish', 'native' => 'Türkçe'],
    'zh' => ['name' => 'Chinese', 'native' => '中文'],
];

// Protected terms
$protectedTerms = [
    'CulturalTranslate', 'Laravel', 'Stripe', 'OpenAI', 'GPT-4', 'API', 'REST',
    'GDPR', 'HIPAA', 'SSL', 'PayPal', 'Google', 'GitHub', 'WordPress', 'Shopify'
];

function translateBatch($texts, $targetLang, $langName, $apiKey, $protectedTerms) {
    $protectedList = implode(', ', $protectedTerms);
    
    $prompt = "Translate the following JSON object values from English to {$langName}. 
Keep all keys exactly as they are (in English).
Do NOT translate these terms: {$protectedList}
Keep :variable placeholders unchanged.
Return ONLY valid JSON with the same structure.

JSON to translate:
" . json_encode($texts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'system', 'content' => "You are a professional translator. Translate to {$langName}. Return only valid JSON."],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.3,
        'max_tokens' => 4000
    ];

    $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($payload === false) {
        return null;
    }

    $retries = 2;
    for ($attempt = 0; $attempt <= $retries; $attempt++) {
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_TIMEOUT => 120,
            CURLOPT_CONNECTTIMEOUT => 15,
        ]);

        $response = curl_exec($ch);
        $curlErrNo = curl_errno($ch);
        $curlErr = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $curlErrNo !== 0) {
            if ($attempt < $retries) {
                usleep(500000);
                continue;
            }
            return null;
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            // Retry on transient errors
            if ($attempt < $retries && in_array($httpCode, [408, 429, 500, 502, 503, 504], true)) {
                usleep(750000);
                continue;
            }
            return null;
        }

        $result = json_decode($response, true);
        if (!is_array($result) || !isset($result['choices'][0]['message']['content'])) {
            return null;
        }

        $content = (string) $result['choices'][0]['message']['content'];
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*/', '', $content);
        $content = trim($content);

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            // If the model returns invalid JSON, retry once.
            if ($attempt < $retries) {
                usleep(750000);
                continue;
            }
            return null;
        }

        // Ensure keys match exactly (no missing/extra keys)
        $inKeys = array_keys($texts);
        $outKeys = array_keys($decoded);
        sort($inKeys);
        sort($outKeys);
        if ($inKeys !== $outKeys) {
            if ($attempt < $retries) {
                usleep(750000);
                continue;
            }
            return null;
        }

        return $decoded;
    }

    return null;
}

function saveTranslationFile($langCode, $translations) {
    $dir = __DIR__ . '/lang/' . $langCode;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $targetPath = $dir . '/messages.php';
    $tmpPath = $targetPath . '.tmp';

    // Use var_export to avoid PHP syntax breakage due to escaping edge-cases.
    $date = date('Y-m-d H:i:s');
    $content = "<?php\n\n/**\n * CulturalTranslate - {$langCode} Translation File\n * Auto-generated\n * Generated: {$date}\n */\n\nreturn " . var_export($translations, true) . ";\n";

    // Atomic write: write temp then rename. Also lock writes.
    $bytes = file_put_contents($tmpPath, $content, LOCK_EX);
    if ($bytes === false) {
        throw new RuntimeException('Failed to write translation file: ' . $tmpPath);
    }

    if (!@rename($tmpPath, $targetPath)) {
        @unlink($tmpPath);
        throw new RuntimeException('Failed to replace translation file: ' . $targetPath);
    }
}

// Process each language
foreach ($languages as $code => $info) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  Translating to {$info['name']} ({$code})...\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $allTranslations = [];
    $chunks = array_chunk($english, 40, true);
    $total = count($chunks);
    
    foreach ($chunks as $i => $chunk) {
        $num = $i + 1;
        echo "  [{$num}/{$total}] Translating batch... ";
        
        $translated = translateBatch($chunk, $code, $info['name'], $apiKey, $protectedTerms);
        
        if ($translated) {
            $allTranslations = array_merge($allTranslations, $translated);
            echo "✓ Done (" . count($chunk) . " strings)\n";
        } else {
            echo "✗ Failed, using English\n";
            $allTranslations = array_merge($allTranslations, $chunk);
        }
        
        usleep(500000); // 0.5s delay
    }
    
    saveTranslationFile($code, $allTranslations);
    echo "  ✓ Saved to lang/{$code}/messages.php\n\n";
}

echo "\n════════════════════════════════════════════\n";
echo "  TRANSLATION COMPLETE!\n";
echo "════════════════════════════════════════════\n";
