<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
                <p class="text-xl text-gray-600">Select the perfect plan for your cultural translation needs</p>
            </div>

            <!-- Pricing Cards -->
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Basic Plan -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-gray-200 hover:border-blue-500 transition-all">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Basic</h3>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-gray-900">$29</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">100,000 tokens/month</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Cultural translation AI</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Email support</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">API access</span>
                            </li>
                        </ul>
                        @auth
                            <form action="{{ route('stripe.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="price_id" value="{{ config('services.stripe.prices.basic') }}">
                                <input type="hidden" name="plan_name" value="basic">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Get Started
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                Sign In to Subscribe
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Pro Plan (Recommended) -->
                <div class="bg-white rounded-lg shadow-xl overflow-hidden border-4 border-blue-500 relative transform scale-105">
                    <div class="absolute top-0 right-0 bg-blue-500 text-white px-4 py-1 text-sm font-semibold rounded-bl-lg">
                        POPULAR
                    </div>
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Pro</h3>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-gray-900">$99</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">500,000 tokens/month</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Advanced cultural AI</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Priority support</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Custom cultural profiles</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Analytics dashboard</span>
                            </li>
                        </ul>
                        @auth
                            <form action="{{ route('stripe.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="price_id" value="{{ config('services.stripe.prices.pro') }}">
                                <input type="hidden" name="plan_name" value="pro">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                                    Get Started
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                                Sign In to Subscribe
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-gray-200 hover:border-blue-500 transition-all">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-gray-900">$299</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 font-medium">Unlimited tokens</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Enterprise AI features</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">24/7 dedicated support</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Custom integrations</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">SLA guarantee</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">On-premise deployment</span>
                            </li>
                        </ul>
                        @auth
                            <form action="{{ route('stripe.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="price_id" value="{{ config('services.stripe.prices.enterprise') }}">
                                <input type="hidden" name="plan_name" value="enterprise">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Get Started
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                Sign In to Subscribe
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Features Comparison -->
            <div class="mt-16 text-center">
                <p class="text-gray-600">All plans include cultural translation, multi-language support, and API access.</p>
                <p class="text-gray-600 mt-2">Need a custom plan? <a href="#" class="text-blue-600 hover:text-blue-700 underline">Contact us</a></p>
            </div>

            @auth
                <!-- Current Subscription Info -->
                @if(auth()->user()->subscription)
                    <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-2xl mx-auto">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Subscription</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Plan</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(auth()->user()->subscription->plan_name) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="font-medium {{ auth()->user()->subscription->isActive() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ auth()->user()->subscription->isActive() ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tokens Used</p>
                                <p class="font-medium text-gray-900">
                                    {{ number_format(auth()->user()->subscription->tokens_used) }} / {{ number_format(auth()->user()->subscription->tokens_limit) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Renews On</p>
                                <p class="font-medium text-gray-900">{{ auth()->user()->subscription->ends_at?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            @if(auth()->user()->subscription->isActive())
                                <form action="{{ route('stripe.portal') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Manage Subscription
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>
