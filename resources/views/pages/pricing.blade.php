<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-indigo-600">CulturalTranslate</a>
                <a href="/" class="text-gray-600 hover:text-gray-900">← Back to Home</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Choose the perfect plan for your translation needs. All plans include 14-day free trial.</p>
        </div>
    </section>
    
    <!-- Pricing Cards -->
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                
                @foreach($plans as $index => $plan)
                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 {{ $plan->is_popular ? 'border-indigo-500 transform scale-105 shadow-2xl' : 'border-gray-200' }} hover:border-indigo-500 transition {{ $plan->is_popular ? 'relative' : '' }}">
                    
                    @if($plan->is_popular)
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-semibold">MOST POPULAR</span>
                    </div>
                    @endif
                    
                    <div class="text-center mb-8 {{ $plan->is_popular ? 'mt-4' : '' }}">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                        
                        @if($plan->is_custom)
                        <div class="text-4xl font-bold text-gray-900 mb-2">Custom</div>
                        <p class="text-sm text-gray-500">Contact us for pricing</p>
                        @else
                        <div class="text-5xl font-bold {{ $plan->is_popular ? 'text-indigo-600' : 'text-gray-900' }} mb-2">
                            ${{ number_format($plan->price, 0) }}
                            <span class="text-xl text-gray-600">/{{ $plan->billing_period === 'monthly' ? 'mo' : 'yr' }}</span>
                        </div>
                        <p class="text-sm text-gray-500">Billed {{ $plan->billing_period }}</p>
                        @endif
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>{{ number_format($plan->tokens_limit / 1000) }}K characters/month</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>{{ number_format($plan->tokens_limit / 10) }} API calls</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>{{ $plan->max_team_members == 999999999 ? 'Unlimited' : $plan->max_team_members }} team member{{ $plan->max_team_members > 1 ? 's' : '' }}</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>{{ $plan->max_projects == 999999999 ? 'Unlimited' : $plan->max_projects }} project{{ $plan->max_projects > 1 ? 's' : '' }}</span>
                        </li>
                        @if($plan->api_access)
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>API Access</span>
                        </li>
                        @endif
                        @if($plan->priority_support)
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>Priority Support</span>
                        </li>
                        @endif
                        @if($plan->custom_integrations)
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check {{ $plan->is_popular ? 'text-indigo-500' : 'text-green-500' }} mr-3"></i>
                            <span>Custom Integrations</span>
                        </li>
                        @endif
                    </ul>
                    
                    @if($plan->is_custom)
                    <a href="/contact" class="block w-full bg-gray-900 text-white text-center py-3 rounded-lg hover:bg-gray-800 transition font-semibold">
                        Contact Sales
                    </a>
                    @else
                    <a href="/register?plan={{ strtolower($plan->slug) }}" class="block w-full {{ $plan->is_popular ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white text-center py-3 rounded-lg transition font-semibold">
                        Start Free Trial
                    </a>
                    @endif
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
                    <p class="text-gray-600">We accept all major credit cards (Visa, Mastercard, American Express) and PayPal.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                    <p class="text-gray-600">Yes! All plans come with a 14-day free trial. No credit card required.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens if I exceed my character limit?</h3>
                    <p class="text-gray-600">You'll receive a notification when you reach 80% of your limit. You can either upgrade your plan or purchase additional characters.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 bg-indigo-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
            <p class="text-xl text-indigo-100 mb-8">Join thousands of users who trust CulturalTranslate for their translation needs.</p>
            <a href="/register" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Start Your Free Trial
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
        </div>
    </footer>
    
</body>
</html>
