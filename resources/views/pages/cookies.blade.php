@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('Cookie Policy') }}
            </h1>
            <p class="text-gray-600">
                {{ __('Last updated: December 5, 2025') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 prose prose-lg max-w-none">
            <h2>{{ __('What Are Cookies') }}</h2>
            <p>
                {{ __('Cookies are small text files that are placed on your computer or mobile device when you visit our website. They are widely used to make websites work more efficiently and provide information to website owners.') }}
            </p>

            <h2>{{ __('How We Use Cookies') }}</h2>
            <p>{{ __('We use cookies for the following purposes:') }}</p>
            <ul>
                <li><strong>{{ __('Essential Cookies') }}</strong> - {{ __('Required for the website to function properly') }}</li>
                <li><strong>{{ __('Analytics Cookies') }}</strong> - {{ __('Help us understand how visitors use our website') }}</li>
                <li><strong>{{ __('Preference Cookies') }}</strong> - {{ __('Remember your settings and preferences') }}</li>
                <li><strong>{{ __('Security Cookies') }}</strong> - {{ __('Help keep our platform secure') }}</li>
            </ul>

            <h2>{{ __('Types of Cookies We Use') }}</h2>
            
            <h3>{{ __('Session Cookies') }}</h3>
            <p>{{ __('These cookies are temporary and expire when you close your browser.') }}</p>

            <h3>{{ __('Persistent Cookies') }}</h3>
            <p>{{ __('These cookies remain on your device until they expire or you delete them.') }}</p>

            <h2>{{ __('Third-Party Cookies') }}</h2>
            <p>{{ __('We may use third-party services that set cookies on our website:') }}</p>
            <ul>
                <li>{{ __('Google Analytics - for website analytics') }}</li>
                <li>{{ __('Stripe - for payment processing') }}</li>
                <li>{{ __('OpenAI - for AI translation services') }}</li>
            </ul>

            <h2>{{ __('Managing Cookies') }}</h2>
            <p>
                {{ __('You can control and manage cookies in your browser settings. However, disabling cookies may affect the functionality of our website.') }}
            </p>

            <h2>{{ __('Contact Us') }}</h2>
            <p>
                {{ __('If you have questions about our cookie policy, please contact us at:') }}
                <a href="mailto:privacy@culturaltranslate.com" class="text-indigo-600 hover:underline">privacy@culturaltranslate.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
