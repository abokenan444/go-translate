<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
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
                @foreach($plans as $plan)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 {{ $plan->is_popular ? 'border-blue-500 transform scale-105' : 'border-gray-200' }} hover:border-blue-500 transition-all {{ $plan->is_popular ? 'relative' : '' }}">
                    @if($plan->is_popular)
                    <div class="absolute top-0 right-0 bg-blue-500 text-white px-4 py-1 text-sm font-semibold rounded-bl-lg">
                        POPULAR
                    </div>
                    @endif
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <div class="mb-6">
                            @if($plan->is_custom || $plan->price == 0 && $plan->slug == 'custom')
                                <span class="text-3xl font-bold text-gray-900">اتصل بنا</span>
                            @else
                                <span class="text-5xl font-bold text-gray-900">${{ number_format($plan->price, 0) }}</span>
                                <span class="text-gray-600">/{{ $plan->billing_period == 'monthly' ? 'month' : 'year' }}</span>
                            @endif
                        </div>
                        @if($plan->description)
                        <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                        @endif
                        <ul class="space-y-4 mb-8">
                            @php
                                $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
                                $features = is_array($features) ? $features : [];
                            @endphp
                            @foreach($features as $feature)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @auth
                            @if($plan->is_custom)
                                <a href="{{ route('contact') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    اتصل بنا
                                </a>
                            @else
                                <form action="{{ route('stripe.checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="price_id" value="{{ config('services.stripe.prices.' . $plan->slug) }}">
                                    <input type="hidden" name="plan_name" value="{{ $plan->slug }}">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors {{ $plan->is_popular ? 'shadow-lg' : '' }}">
                                        Get Started
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors {{ $plan->is_popular ? 'shadow-lg' : '' }}">
                                Sign In to Subscribe
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
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
