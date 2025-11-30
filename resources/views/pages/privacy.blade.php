<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Privacy Policy') }} - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    
    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('Privacy Policy') }}</h1>
        
        <div class="bg-white rounded-lg shadow-sm p-8 space-y-6">
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('1. Information We Collect') }}</h2>
                <p class="text-gray-700">{{ __('We collect information you provide directly to us, including name, email, and usage data.') }}</p>
            </section>
            
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('2. How We Use Your Information') }}</h2>
                <p class="text-gray-700">{{ __('We use the information we collect to provide, maintain, and improve our services.') }}</p>
            </section>
            
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('3. Data Security') }}</h2>
                <p class="text-gray-700">{{ __('We implement appropriate security measures to protect your personal information.') }}</p>
            </section>
            
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('4. Your Rights') }}</h2>
                <p class="text-gray-700">{{ __('You have the right to access, update, or delete your personal information.') }}</p>
            </section>
        </div>
    </div>
    
    @include('components.footer')
</body>
</html>
