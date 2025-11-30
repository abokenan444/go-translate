<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Terms of Service') }} - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    
    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('Terms of Service') }}</h1>
        
        <div class="bg-white rounded-lg shadow-sm p-8 space-y-6">
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('1. Acceptance of Terms') }}</h2>
                <p class="text-gray-700">{{ __('By accessing our service, you agree to be bound by these terms.') }}</p>
            </section>
            
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('2. Use License') }}</h2>
                <p class="text-gray-700">{{ __('We grant you a limited license to use our translation services.') }}</p>
            </section>
            
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('3. Service Availability') }}</h2>
                <p class="text-gray-700">{{ __('We strive to maintain 99.9% uptime but do not guarantee uninterrupted service.') }}</p>
            </section>
        </div>
    </div>
    
    @include('components.footer')
</body>
</html>
