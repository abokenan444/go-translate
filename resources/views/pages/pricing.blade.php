@extends('layouts.app')
@section('title', 'Pricing Plans - AI Cultural Translation Service | CulturalTranslate')

@section('content')
    <!-- Hero -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">AI-Powered Cultural Translation Pricing Plans</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Choose the perfect translation plan for your business needs. Transparent pricing with no hidden fees. Start with our free plan or upgrade for unlimited translations and premium features.</p>
        </div>
    </section>
    
    <!-- Pricing Cards -->
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                @foreach($plans as $index => $plan)
                @php
                    $isPopular = $plan->is_popular ?? false;
                    $isCustom = $plan->is_custom ?? false;
                    $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
                    $features = is_array($features) ? $features : [];
                @endphp
                
                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 {{ $isPopular ? 'border-indigo-500 transform scale-105 shadow-2xl' : 'border-gray-200' }} hover:border-indigo-500 transition {{ $isPopular ? 'relative' : '' }}">
                    
                    @if($isPopular)
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-semibold">MOST POPULAR</span>
                    </div>
                    @endif
                    
                    <div class="text-center mb-8 {{ $isPopular ? 'mt-4' : '' }}">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-600 mb-4 min-h-[48px]">{{ $plan->description ?? '' }}</p>
                        
                        @if($isCustom)
                        <div class="text-4xl font-bold text-gray-900 mb-2">Custom</div>
                        <p class="text-sm text-gray-500">Contact us for pricing</p>
                        @elseif($plan->price == 0)
                        <div class="text-5xl font-bold text-gray-900 mb-2">Free</div>
                        <p class="text-sm text-gray-500">No credit card required</p>
                        @else
                        <div class="text-5xl font-bold {{ $isPopular ? 'text-indigo-600' : 'text-gray-900' }} mb-2">
                            @if($plan->currency == 'EUR')
                                €{{ number_format($plan->price ?? 0, 2) }}
                            @elseif($plan->currency == 'GBP')
                                £{{ number_format($plan->price ?? 0, 2) }}
                            @else
                                ${{ number_format($plan->price ?? 0, 2) }}
                            @endif
                            <span class="text-xl text-gray-600">/{{ $plan->billing_period == 'monthly' ? 'mo' : 'yr' }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <ul class="space-y-3 mb-8 min-h-[200px]">
                        @if(isset($plan->tokens_limit) && $plan->tokens_limit > 0)
                        <li class="flex items-start text-gray-700 text-sm">
                            <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                            <span>{{ $plan->tokens_limit >= 999999 ? 'Unlimited' : number_format($plan->tokens_limit) }} tokens/month</span>
                        </li>
                        @endif
                        
                        @if(isset($plan->max_projects) && $plan->max_projects > 0)
                        <li class="flex items-start text-gray-700 text-sm">
                            <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                            <span>{{ $plan->max_projects >= 999 ? 'Unlimited' : number_format($plan->max_projects) }} projects</span>
                        </li>
                        @endif
                        
                        @if(isset($plan->max_team_members) && $plan->max_team_members > 0)
                        <li class="flex items-start text-gray-700 text-sm">
                            <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                            <span>{{ $plan->max_team_members >= 999 ? 'Unlimited' : number_format($plan->max_team_members) }} team members</span>
                        </li>
                        @endif
                        
                        @if($plan->api_access)
                        <li class="flex items-start text-gray-700 text-sm">
                            <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                            <span>API Access</span>
                        </li>
                        @endif
                        
                        @if($plan->priority_support)
                        <li class="flex items-start text-gray-700 text-sm">
                            <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                            <span>Priority Support</span>
                        </li>
                        @endif
                        
                        @if($plan->custom_integrations)
                        <li class="flex items-start text-gray-700 text-sm">
                            <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                            <span>Custom Integrations</span>
                        </li>
                        @endif
                        
                        @if(!empty($features))
                            @foreach($features as $feature)
                            <li class="flex items-start text-gray-700 text-sm">
                                <i class="fas fa-check {{ $isPopular ? 'text-indigo-500' : 'text-green-500' }} mr-2 mt-1 flex-shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                    
                    @if($isCustom)
                    <a href="/contact" class="block w-full bg-gray-900 text-white text-center py-3 rounded-lg hover:bg-gray-800 transition font-semibold shadow-md hover:shadow-lg">
                        Contact Sales
                    </a>
                    @else
                    <a href="/register?plan={{ $plan->slug }}" class="block w-full {{ $isPopular ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white text-center py-3 rounded-lg transition font-semibold shadow-md hover:shadow-lg">
                        Get Started
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
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions About Our Translation Pricing</h2>
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my translation plan later?</h3>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your translation subscription at any time. Changes will be reflected in your next billing cycle with no penalties or fees.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">We accept all major credit cards (Visa, Mastercard, American Express), PayPal, and bank transfers for enterprise plans. All payments are processed securely.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial for paid plans?</h3>
                    <p class="text-gray-600">We offer a free plan with 1,000 tokens per month. You can use this to test our core features. For more advanced features, you can upgrade to a paid plan at any time.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What are tokens and how are they counted?</h3>
                    <p class="text-gray-600">Tokens are units used to measure text length. 1 token is approximately 4 characters. Your usage is calculated based on the number of tokens in the source text.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
