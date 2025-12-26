<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    public function index()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Getting Started',
                'icon' => '🚀',
                'articles' => [
                    ['title' => 'How to create an account', 'views' => 1250],
                    ['title' => 'Uploading your first document', 'views' => 980],
                    ['title' => 'Understanding pricing plans', 'views' => 750],
                ]
            ],
            [
                'id' => 2,
                'name' => 'Translation Services',
                'icon' => '🌍',
                'articles' => [
                    ['title' => 'Supported languages', 'views' => 2100],
                    ['title' => 'Document types we translate', 'views' => 1850],
                    ['title' => 'Quality assurance process', 'views' => 1200],
                ]
            ],
            [
                'id' => 3,
                'name' => 'Certification',
                'icon' => '✅',
                'articles' => [
                    ['title' => 'What is CTS certification?', 'views' => 3200],
                    ['title' => 'How to verify a certificate', 'views' => 2850],
                    ['title' => 'Certificate validity period', 'views' => 1450],
                ]
            ],
            [
                'id' => 4,
                'name' => 'Government Portal',
                'icon' => '🏛️',
                'articles' => [
                    ['title' => 'Accessing government portal', 'views' => 890],
                    ['title' => 'Bulk upload guide', 'views' => 670],
                    ['title' => 'Priority processing', 'views' => 540],
                ]
            ],
            [
                'id' => 5,
                'name' => 'API & Integration',
                'icon' => '🔌',
                'articles' => [
                    ['title' => 'API documentation', 'views' => 1650],
                    ['title' => 'WordPress plugin setup', 'views' => 980],
                    ['title' => 'Webhook configuration', 'views' => 720],
                ]
            ],
            [
                'id' => 6,
                'name' => 'Billing & Payments',
                'icon' => '💳',
                'articles' => [
                    ['title' => 'Payment methods', 'views' => 1450],
                    ['title' => 'Refund policy', 'views' => 890],
                    ['title' => 'Invoice management', 'views' => 650],
                ]
            ],
        ];
        
        $popularArticles = [
            ['title' => 'What is CTS certification?', 'category' => 'Certification', 'views' => 3200],
            ['title' => 'Supported languages', 'category' => 'Translation', 'views' => 2100],
            ['title' => 'How to verify a certificate', 'category' => 'Certification', 'views' => 2850],
            ['title' => 'Document types we translate', 'category' => 'Translation', 'views' => 1850],
            ['title' => 'API documentation', 'category' => 'Integration', 'views' => 1650],
        ];
        
        return view('help-center.index', compact('categories', 'popularArticles'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        // Mock search results
        $results = [
            ['title' => 'How to verify a certificate', 'excerpt' => 'Learn how to verify translation certificates...', 'category' => 'Certification'],
            ['title' => 'Supported languages', 'excerpt' => 'We support over 100 languages including...', 'category' => 'Translation'],
        ];
        
        return view('help-center.search', compact('results', 'query'));
    }
    
    /**
     * Articles database with full content
     */
    private function getArticles()
    {
        return [
            'how-to-verify-a-certificate' => [
                'title' => 'How to Verify a Certificate',
                'category' => 'Certification',
                'content' => '
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Certificate Verification Process</h2>
                    <p class="mb-6 text-gray-700">CulturalTranslate provides a secure and easy way to verify the authenticity of CTS™ certified translations. Follow these simple steps to verify any certificate.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Step 1: Locate Your Certificate ID</h3>
                    <p class="mb-4 text-gray-700">Every CTS™ certified translation comes with a unique Certificate ID. You can find this ID:</p>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li>At the bottom of your certified document</li>
                        <li>On the official certificate page</li>
                        <li>In your email confirmation</li>
                        <li>By scanning the QR code on the document</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Step 2: Visit the Verification Page</h3>
                    <p class="mb-4 text-gray-700">Go to <a href="/certificate-verification" class="text-purple-600 hover:underline">Certificate Verification</a> page or scan the QR code on your document.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Step 3: Enter the Certificate ID</h3>
                    <p class="mb-4 text-gray-700">Enter your certificate ID in the format: <code class="bg-gray-100 px-2 py-1 rounded">CTS-YYYY-XXXXXXXXXX</code></p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Step 4: View Verification Results</h3>
                    <p class="mb-4 text-gray-700">The system will instantly display:</p>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li><strong>Authenticity Status:</strong> Verified or Invalid</li>
                        <li><strong>Document Details:</strong> Type, languages, and date</li>
                        <li><strong>Translator Information:</strong> Certified translator credentials</li>
                        <li><strong>CTS Score:</strong> Cultural Translation Score rating</li>
                    </ul>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <p class="text-blue-700"><strong>💡 Pro Tip:</strong> You can also verify certificates using the QR code scanner on your mobile device for instant verification.</p>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">What Makes Our Certificates Authentic?</h3>
                    <p class="mb-4 text-gray-700">All CTS™ certificates include multiple security features:</p>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li>Unique blockchain-secured certificate ID</li>
                        <li>QR code with encrypted verification link</li>
                        <li>Digital signature of certified translator</li>
                        <li>Tamper-evident document hash</li>
                        <li>Real-time database verification</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Need Help?</h3>
                    <p class="text-gray-700">If you have any issues verifying your certificate, please <a href="/contact" class="text-purple-600 hover:underline">contact our support team</a>. We\'re available 24/7 to assist you.</p>
                ',
                'updated_at' => now()->subDays(3),
            ],
            'what-is-cts-certification' => [
                'title' => 'What is CTS Certification?',
                'category' => 'Certification',
                'content' => '
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Understanding CTS™ Certification</h2>
                    <p class="mb-6 text-gray-700">CTS™ (Cultural Translation Score) certification is our proprietary quality assurance standard that guarantees culturally-adapted, accurate translations.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">What Does CTS™ Measure?</h3>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li><strong>Linguistic Accuracy:</strong> Grammar, spelling, and syntax correctness</li>
                        <li><strong>Cultural Adaptation:</strong> Proper localization of idioms and expressions</li>
                        <li><strong>Context Preservation:</strong> Maintaining original meaning and intent</li>
                        <li><strong>Formatting Quality:</strong> Document structure and layout preservation</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">CTS Score Levels</h3>
                    <div class="space-y-3 mb-6">
                        <div class="bg-green-50 p-4 rounded-lg"><strong class="text-green-700">95-100%:</strong> Excellent - Ready for official use</div>
                        <div class="bg-blue-50 p-4 rounded-lg"><strong class="text-blue-700">85-94%:</strong> Very Good - Suitable for most purposes</div>
                        <div class="bg-yellow-50 p-4 rounded-lg"><strong class="text-yellow-700">75-84%:</strong> Good - May need minor review</div>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Benefits of CTS™ Certification</h3>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li>Accepted by government agencies worldwide</li>
                        <li>Recognized by immigration authorities</li>
                        <li>Valid for legal and official documents</li>
                        <li>Includes verification capability</li>
                        <li>24/7 online certificate validation</li>
                    </ul>
                ',
                'updated_at' => now()->subDays(7),
            ],
            'supported-languages' => [
                'title' => 'Supported Languages',
                'category' => 'Translation Services',
                'content' => '
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">100+ Languages Supported</h2>
                    <p class="mb-6 text-gray-700">CulturalTranslate supports over 100 languages with native-speaking certified translators.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Major Languages</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 p-3 rounded text-center">🇺🇸 English</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇸🇦 Arabic</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇨🇳 Chinese</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇪🇸 Spanish</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇫🇷 French</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇩🇪 German</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇯🇵 Japanese</div>
                        <div class="bg-gray-50 p-3 rounded text-center">🇰🇷 Korean</div>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Regional Languages</h3>
                    <p class="mb-4 text-gray-700">We also support regional variants including:</p>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li>Arabic (Saudi, Egyptian, Gulf, Levantine)</li>
                        <li>Chinese (Simplified, Traditional)</li>
                        <li>Spanish (Spain, Latin America)</li>
                        <li>Portuguese (Brazil, Portugal)</li>
                        <li>French (France, Canada)</li>
                    </ul>
                    
                    <p class="text-gray-700">For a complete list of supported languages, visit your <a href="/dashboard" class="text-purple-600 hover:underline">dashboard</a> or <a href="/contact" class="text-purple-600 hover:underline">contact us</a>.</p>
                ',
                'updated_at' => now()->subDays(5),
            ],
            'api-documentation' => [
                'title' => 'API Documentation',
                'category' => 'API & Integration',
                'content' => '
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">CulturalTranslate API</h2>
                    <p class="mb-6 text-gray-700">Integrate our powerful translation API into your applications.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Quick Start</h3>
                    <pre class="bg-gray-900 text-green-400 p-4 rounded-lg mb-6 overflow-x-auto"><code>curl -X POST https://culturaltranslate.com/api/v1/translate \\
  -H "Content-Type: application/json" \\
  -d \'{"text": "Hello World", "source": "en", "target": "ar"}\'</code></pre>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">API Endpoints</h3>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li><code class="bg-gray-100 px-2 py-1 rounded">POST /api/v1/translate</code> - Translate text</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/v1/languages</code> - List languages</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">POST /api/v1/detect</code> - Detect language</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/v1/health</code> - Check API status</li>
                    </ul>
                    
                    <p class="text-gray-700">For complete documentation, visit <a href="/api-docs" class="text-purple-600 hover:underline">API Documentation</a>.</p>
                ',
                'updated_at' => now()->subDays(2),
            ],
        ];
    }

    public function article($slug)
    {
        $articles = $this->getArticles();
        
        // Try to find article by slug
        if (isset($articles[$slug])) {
            $article = $articles[$slug];
        } else {
            // Default article for any slug
            $article = [
                'title' => ucwords(str_replace('-', ' ', $slug)),
                'category' => 'Help Center',
                'content' => '
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to CulturalTranslate Help Center</h2>
                    <p class="mb-6 text-gray-700">Thank you for visiting our help center. This article is being updated with detailed information.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">About CulturalTranslate</h3>
                    <p class="mb-4 text-gray-700">CulturalTranslate is a leading AI-powered translation platform that delivers culturally-adapted translations for businesses and individuals worldwide.</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Our Services</h3>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li><strong>AI Translation:</strong> Instant, accurate translations in 100+ languages</li>
                        <li><strong>CTS™ Certification:</strong> Official certified translations for legal documents</li>
                        <li><strong>Cultural Adaptation:</strong> Translations that resonate with local audiences</li>
                        <li><strong>API Integration:</strong> Seamless integration with your applications</li>
                        <li><strong>Government Portal:</strong> Specialized services for government agencies</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Getting Started</h3>
                    <ol class="list-decimal list-inside mb-6 text-gray-700 space-y-2">
                        <li>Create a free account at <a href="/register" class="text-purple-600 hover:underline">Sign Up</a></li>
                        <li>Choose your source and target languages</li>
                        <li>Upload your document or paste your text</li>
                        <li>Receive instant AI-powered translation</li>
                        <li>Optionally request CTS™ certification</li>
                    </ol>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Quality Assurance</h3>
                    <p class="mb-4 text-gray-700">All translations undergo rigorous quality checks:</p>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li>AI accuracy verification</li>
                        <li>Cultural appropriateness review</li>
                        <li>Grammar and syntax checking</li>
                        <li>Terminology consistency</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Pricing</h3>
                    <p class="mb-4 text-gray-700">We offer flexible pricing plans:</p>
                    <ul class="list-disc list-inside mb-6 text-gray-700 space-y-2">
                        <li><strong>Free:</strong> 1,000 characters/month</li>
                        <li><strong>Pro:</strong> 100,000 characters/month - $29/month</li>
                        <li><strong>Business:</strong> 500,000 characters/month - $99/month</li>
                        <li><strong>Enterprise:</strong> Unlimited - Custom pricing</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Need More Help?</h3>
                    <p class="text-gray-700">
                        If you have questions or need assistance:
                    </p>
                    <ul class="list-disc list-inside mt-4 text-gray-700 space-y-2">
                        <li>Email us at <a href="mailto:support@culturaltranslate.com" class="text-purple-600 hover:underline">support@culturaltranslate.com</a></li>
                        <li>Visit our <a href="/contact" class="text-purple-600 hover:underline">Contact Page</a></li>
                        <li>Browse more articles in the <a href="/help-center" class="text-purple-600 hover:underline">Help Center</a></li>
                    </ul>
                ',
                'updated_at' => now(),
            ];
        }
        
        return view('help-center.article', compact('article'));
    }
}
