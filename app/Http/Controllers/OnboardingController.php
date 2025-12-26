<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    /**
     * Show onboarding page
     */
    public function index()
    {
        $user = auth()->user();
        
        // Check if user completed onboarding
        if ($user && $user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.index', [
            'steps' => $this->getOnboardingSteps(),
            'currentStep' => request()->get('step', 1)
        ]);
    }

    /**
     * Complete onboarding step
     */
    public function completeStep(Request $request)
    {
        $validated = $request->validate([
            'step' => 'required|integer|min:1|max:5',
            'data' => 'nullable|array'
        ]);

        $user = auth()->user();
        $stepData = $user->onboarding_data ?? [];
        
        // Save step data
        $stepData['step_' . $validated['step']] = [
            'completed_at' => now(),
            'data' => $validated['data'] ?? []
        ];

        $user->update([
            'onboarding_data' => $stepData,
            'onboarding_step' => $validated['step']
        ]);

        // If final step, mark as completed
        if ($validated['step'] >= 5) {
            $user->update([
                'onboarding_completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'completed' => true,
                'redirect' => route('dashboard')
            ]);
        }

        return response()->json([
            'success' => true,
            'next_step' => $validated['step'] + 1
        ]);
    }

    /**
     * Skip onboarding
     */
    public function skip()
    {
        auth()->user()->update([
            'onboarding_completed_at' => now(),
            'onboarding_skipped' => true
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Get onboarding steps configuration
     */
    private function getOnboardingSteps(): array
    {
        return [
            [
                'number' => 1,
                'title' => 'Welcome to Cultural Translate',
                'description' => 'Let\'s get you started with AI-powered cultural translation',
                'icon' => '👋',
                'content' => [
                    'heading' => 'Transform Your Global Communication',
                    'features' => [
                        'Translate to 14 languages with cultural context',
                        'Adapt tone for your audience',
                        'Quality scoring and improvement suggestions',
                        'Seamless WordPress & Shopify integration'
                    ],
                    'video_url' => 'https://culturaltranslate.com/videos/intro.mp4'
                ]
            ],
            [
                'number' => 2,
                'title' => 'Create Your First Company',
                'description' => 'Set up your organization profile',
                'icon' => '🏢',
                'content' => [
                    'heading' => 'Tell Us About Your Business',
                    'fields' => [
                        ['name' => 'company_name', 'label' => 'Company Name', 'type' => 'text', 'required' => true],
                        ['name' => 'industry', 'label' => 'Industry', 'type' => 'select', 'options' => [
                            'ecommerce' => 'E-commerce',
                            'saas' => 'SaaS',
                            'agency' => 'Agency',
                            'education' => 'Education',
                            'other' => 'Other'
                        ]],
                        ['name' => 'target_markets', 'label' => 'Target Markets', 'type' => 'multiselect', 'options' => [
                            'ar' => '🇸🇦 Arabic',
                            'de' => '🇩🇪 German',
                            'es' => '🇪🇸 Spanish',
                            'fr' => '🇫🇷 French',
                            'zh' => '🇨🇳 Chinese'
                        ]]
                    ]
                ]
            ],
            [
                'number' => 3,
                'title' => 'Try Your First Translation',
                'description' => 'See Cultural Translate in action',
                'icon' => '✨',
                'content' => [
                    'heading' => 'Experience AI Translation',
                    'sample_text' => 'Welcome to our innovative platform! We\'re excited to help you reach global audiences.',
                    'demo_languages' => ['ar', 'es', 'fr', 'zh'],
                    'demo_tones' => ['formal', 'casual', 'marketing'],
                    'show_quality_score' => true
                ]
            ],
            [
                'number' => 4,
                'title' => 'Set Up Your Integration',
                'description' => 'Connect your tools',
                'icon' => '🔌',
                'content' => [
                    'heading' => 'Choose Your Integration',
                    'integrations' => [
                        [
                            'id' => 'wordpress',
                            'name' => 'WordPress',
                            'icon' => '📝',
                            'description' => 'Auto-translate posts and pages',
                            'setup_url' => '/integrations/wordpress/setup'
                        ],
                        [
                            'id' => 'shopify',
                            'name' => 'Shopify',
                            'icon' => '🛒',
                            'description' => 'Translate products and collections',
                            'setup_url' => '/integrations/shopify/setup'
                        ],
                        [
                            'id' => 'api',
                            'name' => 'API',
                            'icon' => '⚡',
                            'description' => 'Use our REST API',
                            'setup_url' => '/sandbox/create'
                        ],
                        [
                            'id' => 'sdk',
                            'name' => 'SDK',
                            'icon' => '📦',
                            'description' => 'JavaScript, Python, PHP SDKs',
                            'setup_url' => '/docs/sdk'
                        ]
                    ]
                ]
            ],
            [
                'number' => 5,
                'title' => 'You\'re All Set!',
                'description' => 'Start translating with confidence',
                'icon' => '🎉',
                'content' => [
                    'heading' => 'Ready to Go Global!',
                    'next_steps' => [
                        [
                            'title' => 'Create a Glossary',
                            'description' => 'Add brand-specific terminology',
                            'url' => '/glossary/create',
                            'icon' => '📚'
                        ],
                        [
                            'title' => 'Define Brand Voice',
                            'description' => 'Set your tone and style',
                            'url' => '/brand-voice/create',
                            'icon' => '🎨'
                        ],
                        [
                            'title' => 'Invite Team Members',
                            'description' => 'Collaborate with your team',
                            'url' => '/team/invite',
                            'icon' => '👥'
                        ],
                        [
                            'title' => 'Explore Dashboard',
                            'description' => 'View analytics and insights',
                            'url' => '/dashboard',
                            'icon' => '📊'
                        ]
                    ],
                    'resources' => [
                        ['title' => 'Documentation', 'url' => '/docs'],
                        ['title' => 'Video Tutorials', 'url' => '/tutorials'],
                        ['title' => 'Community Forum', 'url' => '/community'],
                        ['title' => 'Support', 'url' => '/support']
                    ]
                ]
            ]
        ];
    }
}
