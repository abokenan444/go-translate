<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-sky-600">
                        Cultural Translate
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    @if(isset($headerMenuItems) && $headerMenuItems->count() > 0)
                        @foreach($headerMenuItems as $menuItem)
                            <a href="{{ $menuItem->url }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                               @if($menuItem->open_new_tab) target="_blank" @endif>
                                {{ __($menuItem->title) }}
                            </a>
                        @endforeach
                    @else
                        {{-- Fallback to default menu if no items in database --}}
                        <a href="/" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Home') }}
                        </a>
                        <a href="/features" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Features') }}
                        </a>
                        <a href="/pricing" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Pricing') }}
                        </a>
                        <a href="/use-cases" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Use Cases') }}
                        </a>
                        <a href="/api-docs" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('API Docs') }}
                        </a>
                        <a href="/about" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('About') }}
                        </a>
                        <a href="/contact" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Contact') }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="flex items-center">
                <!-- Language Switcher - Always Visible -->
                <x-language-switcher />
                
                <!-- Auth Buttons - Hidden on mobile, visible on desktop -->
                <div class="hidden sm:flex sm:items-center sm:ml-4">
                    <a href="/login" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700">
                        {{ __('Login') }}
                    </a>
                    <a href="/register" class="ml-2 px-4 py-2 border border-sky-600 text-sm font-medium rounded-md text-sky-600 bg-white hover:bg-sky-50">
                        {{ __('Sign Up') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
