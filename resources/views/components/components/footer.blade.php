<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Modern Footer with Clean Layout --}}
        {{-- Modern Footer with Clean Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            
            <!-- Product Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Product') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('features') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Features') }}</a></li>
                    <li><a href="{{ url('/pricing-plans') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Pricing') }}</a></li>
                    <li><a href="{{ route('api-docs') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('API Docs') }}</a></li>
                    <li>
                        <a href="{{ route('demo') }}" class="text-gray-100 inline-flex items-center gap-2 px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-500 transition">
                            <span class="inline-block w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                            {{ __('Live Demo') }}
                        </a>
                    </li>
                    <li><a href="{{ route('integrations') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Integrations') }}</a></li>
                    <li><a href="{{ route('use-cases') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Use Cases') }}</a></li>
                </ul>
            </div>

            <!-- Company Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Company') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('About Us') }}</a></li>
                    <li><a href="{{ route('blog') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Blog') }}</a></li>
                    <li><a href="{{ route('careers.index') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Careers') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Contact Us') }}</a></li>
                    <li><a href="{{ route('press') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Press Kit') }}</a></li>
                </ul>
            </div>

            <!-- Resources Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Resources') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('help-center') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Help Center') }}</a></li>
                    <li><a href="{{ route('guides') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Guides') }}</a></li>
                    <li><a href="{{ route('status') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('System Status') }}</a></li>
                    <li><a href="{{ route('community') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Community') }}</a></li>
                    <li><a href="{{ route('changelog') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Changelog') }}</a></li>
                </ul>
            </div>

            <!-- Documentation Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Documentation') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('docs.index') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Getting Started') }}</a></li>
                    <li><a href="{{ route('docs.show', 'installation') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Installation') }}</a></li>
                    <li><a href="{{ route('docs.show', 'api') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('API Reference') }}</a></li>
                    <li><a href="{{ route('docs.show', 'webhooks') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Webhooks') }}</a></li>
                    <li><a href="{{ route('docs.show', 'cli') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('CLI Tool') }}</a></li>
                </ul>
            </div>

            <!-- Legal Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Legal') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="{{ route('terms-of-service') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Terms of Service') }}</a></li>
                    <li><a href="{{ url('/security') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Security') }}</a></li>
                    <li><a href="{{ route('gdpr') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('GDPR') }}</a></li>
                    <li><a href="{{ route('cookies') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Cookies') }}</a></li>
                    <li><a href="{{ route('affiliate') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Affiliate Program') }}</a></li>
                    <li><a href="{{ route('enterprise.pricing') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('Enterprise') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} CulturalTranslate. {{ __('All rights reserved.') }}</p>
            <p class="text-sm mt-2">NL KvK 83656480</p>
        </div>
    </div>
</footer>
