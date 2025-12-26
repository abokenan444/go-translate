<div x-data="subscriptionTab()" x-init="loadSubscription()" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Current Plan -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm opacity-90 mb-2">Current Plan</div>
                <h2 class="text-3xl font-bold" x-text="subscription.plan_name"></h2>
                <p class="mt-2 opacity-90" x-text="formatPrice(subscription.price, subscription.currency) + ' / ' + subscription.billing_cycle"></p>
            </div>
            <div class="text-right" x-show="subscription.renews_at">
                <div class="text-sm opacity-90 mb-2">Renews on</div>
                <div class="text-xl font-semibold" x-text="formatDate(subscription.renews_at)"></div>
            </div>
            <div class="text-right" x-show="!subscription.renews_at && subscription.is_trial">
                <div class="text-sm opacity-90 mb-2">Trial Ends</div>
                <div class="text-xl font-semibold" x-text="formatDate(subscription.trial_ends_at)"></div>
            </div>
        </div>
    </div>
    
    <!-- Usage Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-600 mb-2">Characters Used</div>
            <div class="text-2xl font-bold text-gray-900 mb-2" x-text="formatNumber(usage.characters_used)"></div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-indigo-600" :style="`width: ${(usage.characters_used / usage.characters_limit * 100)}%`"></div>
            </div>
            <div class="text-xs text-gray-500">of <span x-text="formatNumber(usage.characters_limit)"></span> limit</div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-600 mb-2">API Calls</div>
            <div class="text-2xl font-bold text-gray-900 mb-2" x-text="formatNumber(usage.api_calls)"></div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-purple-600" :style="`width: ${(usage.api_calls / usage.api_limit * 100)}%`"></div>
            </div>
            <div class="text-xs text-gray-500">of <span x-text="formatNumber(usage.api_limit)"></span> limit</div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-600 mb-2">Team Members</div>
            <div class="text-2xl font-bold text-gray-900 mb-2" x-text="usage.team_members"></div>
            <div class="text-xs text-gray-500">of <span x-text="usage.team_limit"></span> seats</div>
        </div>
    </div>
    
    <!-- Pricing Plans Section (من صفحة /plans) -->
    <div class="py-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
            <p class="text-xl text-gray-600">Choose the perfect plan for your translation needs. All plans include 14-day free trial.</p>
        </div>
        
        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- Free Plan -->
            <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 p-8 hover:shadow-xl transition">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Free</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$0.00</span>
                    <span class="text-gray-600">/mo</span>
                </div>
                <p class="text-sm text-gray-600 mb-6">10K tokens/month</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">10K translation tokens/month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 1 team members</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 1 projects</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">10 translations per day</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Basic AI models</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Community support</span>
                    </li>
                </ul>
                <form action="{{ route('stripe.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="free">
                    <button type="submit" class="w-full bg-gray-900 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Subscribe Now
                    </button>
                </form>
            </div>
            
            <!-- Basic Plan -->
            <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 p-8 hover:shadow-xl transition">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Basic</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$29.00</span>
                    <span class="text-gray-600">/mo</span>
                </div>
                <p class="text-sm text-gray-600 mb-6">100K tokens/month</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">100K translation tokens/month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">API access</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 5 team members</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 5 projects</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">GPT-4 access</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Email support</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">5 certified documents/month</span>
                    </li>
                </ul>
                <form action="{{ route('stripe.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="basic">
                    <button type="submit" class="w-full bg-gray-900 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Subscribe Now
                    </button>
                </form>
            </div>
            
            <!-- Professional Plan (MOST POPULAR) -->
            <div class="bg-white rounded-2xl shadow-xl border-4 border-indigo-600 p-8 relative hover:shadow-2xl transition">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <span class="bg-indigo-600 text-white px-4 py-1 rounded-full text-xs font-semibold uppercase">Most Popular</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Professional</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-indigo-600">$99.00</span>
                    <span class="text-gray-600">/mo</span>
                </div>
                <p class="text-sm text-gray-600 mb-6">500K tokens/month</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">500K translation tokens/month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">API access</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Priority support</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 10 team members</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 20 projects</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">GPT-4 Turbo access</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Advanced features</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">20 certified documents/month</span>
                    </li>
                </ul>
                <form action="{{ route('stripe.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="professional">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        Subscribe Now
                    </button>
                </form>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 p-8 hover:shadow-xl transition">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$499.00</span>
                    <span class="text-gray-600">/mo</span>
                </div>
                <p class="text-sm text-gray-600 mb-6">2.0M tokens/month</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">2.0M translation tokens/month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">API access</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Priority support</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Custom integrations</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 50 team members</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Up to 100 projects</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">All AI models</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">24/7 support</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Dedicated account manager</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">100 certified documents/month</span>
                    </li>
                </ul>
                <form action="{{ route('stripe.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="enterprise">
                    <button type="submit" class="w-full bg-gray-900 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Subscribe Now
                    </button>
                </form>
            </div>
            
        </div>
    </div>
    
    <!-- Billing History -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Billing History</h3>
            <button @click="downloadInvoices()" class="text-sm text-indigo-600 hover:text-indigo-700" x-show="invoices.length > 0">
                <i class="fas fa-download mr-1"></i> Download All
            </button>
        </div>
        <div class="divide-y divide-gray-200" x-show="invoices.length > 0">
            <template x-for="invoice in invoices" :key="invoice.id">
                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900" x-text="invoice.description"></div>
                        <div class="text-sm text-gray-500 mt-1" x-text="formatDate(invoice.date)"></div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900" x-text="'$' + invoice.amount"></div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full" 
                                  :class="{
                                      'bg-green-100 text-green-800': invoice.status === 'paid',
                                      'bg-yellow-100 text-yellow-800': invoice.status === 'pending',
                                      'bg-red-100 text-red-800': invoice.status === 'failed'
                                  }" 
                                  x-text="invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)"></span>
                        </div>
                        <button @click="downloadInvoice(invoice.id)" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="invoices.length === 0" class="p-12 text-center">
            <i class="fas fa-file-invoice text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No billing history yet</p>
        </div>
    </div>
    
    <!-- Payment Method -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" x-show="paymentMethod !== null">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                <div>
                    <div class="text-sm font-medium text-gray-900">•••• •••• •••• <span x-text="paymentMethod?.last4 || '0000'"></span></div>
                    <div class="text-xs text-gray-500">Expires <span x-text="(paymentMethod?.exp_month || '00') + '/' + (paymentMethod?.exp_year || '0000')"></span></div>
                </div>
            </div>
            <button @click="updatePaymentMethod()" class="text-sm text-indigo-600 hover:text-indigo-700">
                Update
            </button>
        </div>
    </div>
    
    <!-- Cancel Subscription -->
    <div class="bg-red-50 rounded-lg border border-red-200 p-6" x-show="subscription.plan_id !== null && subscription.price > 0">
        <h3 class="text-lg font-semibold text-red-900 mb-2">Cancel Subscription</h3>
        <p class="text-sm text-red-700 mb-4">Once you cancel, you'll lose access to all premium features at the end of your billing period.</p>
        <button @click="cancelSubscription()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            Cancel Subscription
        </button>
    </div>
    
</div>

<script>
function subscriptionTab() {
    return {
        subscription: {
            plan_id: null,
            plan_name: 'Free',
            price: 0,
            billing_cycle: 'month',
            renews_at: null,
            status: 'active',
            is_trial: false,
            trial_ends_at: null
        },
        usage: {
            characters_used: 0,
            characters_limit: 10000,
            api_calls: 0,
            api_limit: 100,
            team_members: 1,
            team_limit: 1
        },
        plans: [],
        invoices: [],
        paymentMethod: null,
        loading: false,
        
        async loadSubscription() {
            try {
                this.loading = true;
                
                console.log('Loading subscription data...');
                
                // Load subscription data
                const subResponse = await window.apiClient.getDashboardSubscription();
                console.log('Subscription response:', subResponse);
                
                if (subResponse.success && subResponse.data) {
                    this.subscription = {
                        plan_id: subResponse.data.plan_id,
                        plan_name: subResponse.data.plan_name || 'Free',
                        price: subResponse.data.price || 0,
                        billing_cycle: subResponse.data.billing_cycle || 'month',
                        renews_at: subResponse.data.renews_at,
                        status: subResponse.data.status || 'active',
                        is_trial: subResponse.data.is_trial || false,
                        trial_ends_at: subResponse.data.trial_ends_at
                    };
                }
                
                // Load usage data
                const usageResponse = await window.apiClient.getDashboardUsage();
                console.log('Usage response:', usageResponse);
                
                if (usageResponse.success && usageResponse.data) {
                    this.usage = usageResponse.data;
                }
                
                // Load plans
                await this.loadPlans();
                
                // Load invoices
                await this.loadInvoices();
                
            } catch (error) {
                console.error('Failed to load subscription:', error);
                window.showNotification('Failed to load subscription data', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async loadPlans() {
            try {
                const response = await window.apiClient.getPlans();
                if (response.success && response.data) {
                    this.plans = response.data;
                }
            } catch (error) {
                console.error('Failed to load plans:', error);
            }
        },
        
        async loadInvoices() {
            try {
                const response = await window.apiClient.getInvoices();
                if (response.success && response.data) {
                    this.invoices = response.data;
                }
            } catch (error) {
                console.error('Failed to load invoices:', error);
            }
        },
        
        async changePlan(planId) {
            if (confirm('Are you sure you want to change your plan?')) {
                try {
                    const response = await window.apiClient.changePlan(planId);
                    if (response.success) {
                        window.showNotification('Plan changed successfully', 'success');
                        await this.loadSubscription();
                    } else {
                        window.showNotification(response.message || 'Failed to change plan', 'error');
                    }
                } catch (error) {
                    console.error('Failed to change plan:', error);
                    window.showNotification('Failed to change plan', 'error');
                }
            }
        },
        
        async cancelSubscription() {
            if (confirm('Are you sure you want to cancel your subscription? You will lose access to premium features at the end of your billing period.')) {
                try {
                    const response = await window.apiClient.cancelSubscription();
                    if (response.success) {
                        window.showNotification('Subscription cancelled successfully', 'success');
                        await this.loadSubscription();
                    } else {
                        window.showNotification(response.message || 'Failed to cancel subscription', 'error');
                    }
                } catch (error) {
                    console.error('Failed to cancel subscription:', error);
                    window.showNotification('Failed to cancel subscription', 'error');
                }
            }
        },
        
        async updatePaymentMethod() {
            window.showNotification('Payment method update feature coming soon', 'info');
        },
        
        async downloadInvoice(invoiceId) {
            try {
                const response = await window.apiClient.downloadInvoice(invoiceId);
                if (response.success && response.data.url) {
                    window.open(response.data.url, '_blank');
                }
            } catch (error) {
                console.error('Failed to download invoice:', error);
                window.showNotification('Failed to download invoice', 'error');
            }
        },
        
        async downloadInvoices() {
            window.showNotification('Downloading all invoices...', 'info');
        },
        
        formatPrice(price, currency = 'USD') {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency
            }).format(price);
        },
        
        formatNumber(number) {
            return new Intl.NumberFormat('en-US').format(number);
        },
        
        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    };
}
</script>
