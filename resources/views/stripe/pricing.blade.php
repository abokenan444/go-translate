<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">CulturalTranslate</a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero -->
    <section class="py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Choose the perfect plan for your translation needs. All plans include 14-day free trial.</p>
        </div>
    </section>
    
    <!-- Pricing Cards -->
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border-2 {{ isset($plan['is_popular']) && $plan['is_popular'] ? 'border-indigo-500 transform lg:scale-105 shadow-2xl' : 'border-gray-200' }} hover:border-indigo-500 transition {{ isset($plan['is_popular']) && $plan['is_popular'] ? 'relative' : '' }}">
                    
                    @if(isset($plan['is_popular']) && $plan['is_popular'])
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-semibold">MOST POPULAR</span>
                    </div>
                    @endif
                    
                    <div class="text-center mb-8 {{ $plan['name'] === 'Professional' ? 'mt-4' : '' }}">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                        
                        <div class="text-4xl md:text-5xl font-bold {{ isset($plan['is_popular']) && $plan['is_popular'] ? 'text-indigo-600' : 'text-gray-900' }} mb-2">
                            @php
                                $currencySymbols = [
                                    'USD' => '$',
                                    'EUR' => '€',
                                    'GBP' => '£',
                                    'JPY' => '¥'
                                ];
                                $symbol = $currencySymbols[$plan['currency']] ?? $plan['currency'];
                            @endphp
                            {{ $symbol }}{{ number_format($plan['price'], 2) }}
                            <span class="text-xl text-gray-600">/mo</span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $plan['tokens'] }} tokens/month</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-start text-gray-700">
                            <i class="fas fa-check {{ isset($plan['is_popular']) && $plan['is_popular'] ? 'text-indigo-500' : 'text-green-500' }} mr-3 mt-1"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    
                    @auth
                        <form action="{{ route('stripe.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $plan['slug'] }}">
                            <button type="submit" class="block w-full {{ isset($plan['is_popular']) && $plan['is_popular'] ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white text-center py-3 rounded-lg transition font-semibold">
                                Subscribe Now
                            </button>
                        </form>
                    @else
                        <a href="{{ route('register') }}?plan={{ $plan['slug'] }}" class="block w-full {{ isset($plan['is_popular']) && $plan['is_popular'] ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white text-center py-3 rounded-lg transition font-semibold">
                            Start Free Trial
                        </a>
                    @endauth
                </div>
                @endforeach
                
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan later?</h3>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">We accept all major credit cards (Visa, Mastercard, American Express) through Stripe secure checkout.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                    <p class="text-gray-600">Yes! All plans come with a 14-day free trial. No credit card required to start.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens if I exceed my token limit?</h3>
                    <p class="text-gray-600">You'll receive a notification when you reach 80% of your limit. You can either upgrade your plan or purchase additional tokens.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I cancel anytime?</h3>
                    <p class="text-gray-600">Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
            <p class="text-xl text-indigo-100 mb-8">Experience culturally intelligent translation with CTS™ certified communication.</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Start Your Free Trial
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">CulturalTranslate</h3>
                    <p class="text-sm">AI-powered translation with cultural intelligence.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/features" class="hover:text-white">Features</a></li>
                        <li><a href="/pricing" class="hover:text-white">Pricing</a></li>
                        <li><a href="/api" class="hover:text-white">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/about" class="hover:text-white">About</a></li>
                        <li><a href="/contact" class="hover:text-white">Contact</a></li>
                        <li><a href="/blog" class="hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/privacy" class="hover:text-white">Privacy</a></li>
                        <li><a href="/terms" class="hover:text-white">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
</body>
</html>
