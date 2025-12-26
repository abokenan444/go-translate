#!/bin/bash

echo "=================================================="
echo "🧪 TESTING TRANSLATION SYSTEM"
echo "=================================================="

cd /var/www/cultural-translate-platform

echo ""
echo "1️⃣ Testing Usage Check (Guest)..."
curl -s http://localhost/api/public/translation/usage \
  -H "X-Fingerprint: test123" | jq '.'

echo ""
echo "2️⃣ Testing Translation (1st attempt)..."
curl -s -X POST http://localhost/api/public/translation/translate \
  -H "Content-Type: application/json" \
  -H "X-Fingerprint: test123" \
  -d '{
    "source_text": "Hello, how are you?",
    "source_lang": "en",
    "target_lang": "ar"
  }' | jq '.'

echo ""
echo "3️⃣ Testing Translation (2nd attempt - from cache)..."
curl -s -X POST http://localhost/api/public/translation/translate \
  -H "Content-Type: application/json" \
  -H "X-Fingerprint: test123" \
  -d '{
    "source_text": "Hello, how are you?",
    "source_lang": "en",
    "target_lang": "ar"
  }' | jq '.'

echo ""
echo "4️⃣ Testing Translation (3rd attempt - should be blocked)..."
curl -s -X POST http://localhost/api/public/translation/translate \
  -H "Content-Type: application/json" \
  -H "X-Fingerprint: test123" \
  -d '{
    "source_text": "Good morning",
    "source_lang": "en",
    "target_lang": "ar"
  }' | jq '.'

echo ""
echo "5️⃣ Checking Database Stats..."
echo "Translation Cache:"
php artisan tinker --execute="echo 'Count: ' . App\Models\TranslationCache::count() . PHP_EOL;"

echo ""
echo "Guest Tracking:"
php artisan tinker --execute="echo 'Count: ' . App\Models\GuestUsageTracking::count() . PHP_EOL;"

echo ""
echo "Prompt Logs:"
php artisan tinker --execute="echo 'Count: ' . App\Models\PromptLog::count() . PHP_EOL;"

echo ""
echo "=================================================="
echo "✅ TEST COMPLETED"
echo "=================================================="
