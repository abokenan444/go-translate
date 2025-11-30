<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(isset($footerMenuItems) && $footerMenuItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Dynamic Footer Menu from Database -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2">
                        @foreach($footerMenuItems as $menuItem)
                            <li>
                                <a href="{{ $menuItem->url }}" 
                                   class="text-gray-400 hover:text-white"
                                   @if($menuItem->open_new_tab) target="_blank" @endif>
                                    {{ __($menuItem->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Additional columns can be added here -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Product') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/features" class="text-gray-400 hover:text-white">{{ __('Features') }}</a></li>
                        <li><a href="/pricing" class="text-gray-400 hover:text-white">{{ __('Pricing') }}</a></li>
                        <li><a href="/api-docs" class="text-gray-400 hover:text-white">{{ __('API Docs') }}</a></li>
                        <li>
                            <a href="/demo" class="text-gray-100 inline-flex items-center gap-2 px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-500 transition">
                                <span class="inline-block w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path d="M13 3L4 14h7l-1 7 9-11h-7l1-7z" />
                                </svg>
                                {{ __('Live Demo') }}
                            </a>
                        </li>
                        <li><a href="/integrations" class="text-gray-400 hover:text-white">{{ __('Integrations') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Company') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/about" class="text-gray-400 hover:text-white">{{ __('About') }}</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-white">{{ __('Blog') }}</a></li>
                        <li><a href="/careers" class="text-gray-400 hover:text-white">{{ __('Careers') }}</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white">{{ __('Contact') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Resources') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/help-center" class="text-gray-400 hover:text-white">{{ __('Help Center') }}</a></li>
                        <li><a href="/guides" class="text-gray-400 hover:text-white">{{ __('Guides') }}</a></li>
                        <li><a href="/use-cases" class="text-gray-400 hover:text-white">{{ __('Case Studies') }}</a></li>
                        <li><a href="/status" class="text-gray-400 hover:text-white">{{ __('Status') }}</a></li>
                        <li>
                            <a href="/demo" class="text-gray-100 inline-flex items-center gap-2 px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-500 transition">
                                <span class="inline-block w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path d="M13 3L4 14h7l-1 7 9-11h-7l1-7z" />
                                </svg>
                                {{ __('Live Demo') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            {{-- Fallback to default footer if no items in database --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Product Column -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Product') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/features" class="text-gray-400 hover:text-white">{{ __('Features') }}</a></li>
                        <li><a href="/pricing" class="text-gray-400 hover:text-white">{{ __('Pricing') }}</a></li>
                        <li><a href="/api-docs" class="text-gray-400 hover:text-white">{{ __('API Docs') }}</a></li>
                        <li><a href="/demo" class="text-gray-400 hover:text-white">{{ __('Demo') }}</a></li>
                        <li><a href="/integrations" class="text-gray-400 hover:text-white">{{ __('Integrations') }}</a></li>
                    </ul>
                </div>

                <!-- Company Column -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Company') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/about" class="text-gray-400 hover:text-white">{{ __('About') }}</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-white">{{ __('Blog') }}</a></li>
                        <li><a href="/careers" class="text-gray-400 hover:text-white">{{ __('Careers') }}</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white">{{ __('Contact') }}</a></li>
                    </ul>
                </div>

                <!-- Resources Column -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Resources') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/help-center" class="text-gray-400 hover:text-white">{{ __('Help Center') }}</a></li>
                        <li><a href="/guides" class="text-gray-400 hover:text-white">{{ __('Guides') }}</a></li>
                        <li><a href="/use-cases" class="text-gray-400 hover:text-white">{{ __('Case Studies') }}</a></li>
                        <li><a href="/status" class="text-gray-400 hover:text-white">{{ __('Status') }}</a></li>
                        <li><a href="/demo" class="text-gray-400 hover:text-white">{{ __('Demo') }}</a></li>
                    </ul>
                </div>

                <!-- Legal Column -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Legal') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/privacy-policy" class="text-gray-400 hover:text-white">{{ __('Privacy') }}</a></li>
                        <li><a href="/terms-of-service" class="text-gray-400 hover:text-white">{{ __('Terms') }}</a></li>
                        <li><a href="/security" class="text-gray-400 hover:text-white">{{ __('Security') }}</a></li>
                        <li><a href="/gdpr" class="text-gray-400 hover:text-white">{{ __('GDPR') }}</a></li>
                    </ul>
                </div>
            </div>
        @endif

        <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} CulturalTranslate. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</footer>
