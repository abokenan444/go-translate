<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationController extends Controller
{
    /**
     * Demo translation endpoint for guest users
     * Uses OpenAI API for translation
     */
    public function translateDemo(Request $request)
    {
        // Validate request
        $request->validate([
            'text' => 'required|string|max:400',
            'source_language' => 'required|string',
            'target_language' => 'required|string'
        ]);

        $text = $request->input('text');
        $sourceLang = $request->input('source_language');
        $targetLang = $request->input('target_language');

        // Language code mapping
        $languageNames = [
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'hy' => 'Armenian',
            'az' => 'Azerbaijani',
            'eu' => 'Basque',
            'be' => 'Belarusian',
            'bn' => 'Bengali',
            'bs' => 'Bosnian',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'ceb' => 'Cebuano',
            'zh' => 'Chinese (Simplified)',
            'zh-TW' => 'Chinese (Traditional)',
            'co' => 'Corsican',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'eo' => 'Esperanto',
            'et' => 'Estonian',
            'fi' => 'Finnish',
            'fr' => 'French',
            'fy' => 'Frisian',
            'gl' => 'Galician',
            'ka' => 'Georgian',
            'de' => 'German',
            'el' => 'Greek',
            'gu' => 'Gujarati',
            'ht' => 'Haitian Creole',
            'ha' => 'Hausa',
            'haw' => 'Hawaiian',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'hmn' => 'Hmong',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'ig' => 'Igbo',
            'id' => 'Indonesian',
            'ga' => 'Irish',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'jv' => 'Javanese',
            'kn' => 'Kannada',
            'kk' => 'Kazakh',
            'km' => 'Khmer',
            'rw' => 'Kinyarwanda',
            'ko' => 'Korean',
            'ku' => 'Kurdish',
            'ky' => 'Kyrgyz',
            'lo' => 'Lao',
            'la' => 'Latin',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'lb' => 'Luxembourgish',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ml' => 'Malayalam',
            'mt' => 'Maltese',
            'mi' => 'Maori',
            'mr' => 'Marathi',
            'mn' => 'Mongolian',
            'my' => 'Myanmar (Burmese)',
            'ne' => 'Nepali',
            'no' => 'Norwegian',
            'ny' => 'Nyanja (Chichewa)',
            'or' => 'Odia (Oriya)',
            'ps' => 'Pashto',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'pa' => 'Punjabi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'sm' => 'Samoan',
            'gd' => 'Scots Gaelic',
            'sr' => 'Serbian',
            'st' => 'Sesotho',
            'sn' => 'Shona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala (Sinhalese)',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'so' => 'Somali',
            'es' => 'Spanish',
            'su' => 'Sundanese',
            'sw' => 'Swahili',
            'sv' => 'Swedish',
            'tl' => 'Tagalog (Filipino)',
            'tg' => 'Tajik',
            'ta' => 'Tamil',
            'tt' => 'Tatar',
            'te' => 'Telugu',
            'th' => 'Thai',
            'tr' => 'Turkish',
            'tk' => 'Turkmen',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'ug' => 'Uyghur',
            'uz' => 'Uzbek',
            'vi' => 'Vietnamese',
            'cy' => 'Welsh',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'zu' => 'Zulu'
        ];

        $sourceLanguageName = $languageNames[$sourceLang] ?? $sourceLang;
        $targetLanguageName = $languageNames[$targetLang] ?? $targetLang;

        try {
            // Get OpenAI API key from environment
            $apiKey = env('OPENAI_API_KEY');
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation service is not configured'
                ], 500);
            }

            // Call OpenAI Chat Completions API for translation (recommended for GPT-5 family)
            $model = env('OPENAI_MODEL', 'gpt-5.2');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional translator. Translate the given text from ' . $sourceLanguageName . ' to ' . $targetLanguageName . '. Preserve the meaning, tone, and cultural context. Only return the translated text without any explanations or additional comments.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $text
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 1000
            ]);

            if ($response->successful()) {
                $data = $response->json() ?? [];
                $translatedText = '';
                
                // Extract from standard Chat Completions format
                if (!empty($data['choices'][0]['message']['content'])) {
                    $translatedText = $data['choices'][0]['message']['content'];
                }

                return response()->json([
                    'success' => true,
                    'translated_text' => trim($translatedText),
                    'source_language' => $sourceLanguageName,
                    'target_language' => $targetLanguageName
                ]);
            } else {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Translation service temporarily unavailable'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Translation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during translation'
            ], 500);
        }
    }
}
