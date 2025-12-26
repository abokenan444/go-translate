@extends('layouts.app')

@section('title', 'Affiliate Program - Cultural Translate')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-green-600 via-teal-600 to-blue-500 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6">Affiliate Program</h1>
            <p class="text-xl mb-8">Earn up to 30% recurring commission by referring customers to Cultural Translate</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Join Now
                </a>
                <a href="#how-it-works" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Commission Tiers -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Commission Structure</h2>
            
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-green-500">
                    <div class="text-center">
                        <div class="text-5xl font-bold text-green-600 mb-2">20%</div>
                        <h3 class="text-xl font-bold mb-4">Starter</h3>
                        <p class="text-gray-600 mb-6">0-10 referrals/month</p>
                        <ul class="text-left space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>20% recurring commission</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>30-day cookie duration</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Monthly payouts</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-600 to-teal-600 text-white p-8 rounded-lg shadow-xl transform scale-105">
                    <div class="text-center">
                        <div class="bg-yellow-400 text-green-900 text-xs font-bold px-3 py-1 rounded-full inline-block mb-4">POPULAR</div>
                        <div class="text-5xl font-bold mb-2">25%</div>
                        <h3 class="text-xl font-bold mb-4">Professional</h3>
                        <p class="text-green-100 mb-6">11-50 referrals/month</p>
                        <ul class="text-left space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>25% recurring commission</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>60-day cookie duration</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Priority support</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Marketing materials</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-purple-500">
                    <div class="text-center">
                        <div class="text-5xl font-bold text-purple-600 mb-2">30%</div>
                        <h3 class="text-xl font-bold mb-4">Enterprise</h3>
                        <p class="text-gray-600 mb-6">50+ referrals/month</p>
                        <ul class="text-left space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>30% recurring commission</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>90-day cookie duration</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Dedicated account manager</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Custom landing pages</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works -->
<div class="py-16" id="how-it-works">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">How It Works</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">1</span>
                    </div>
                    <h3 class="font-bold mb-2">Sign Up</h3>
                    <p class="text-gray-600 text-sm">Create your free affiliate account in minutes</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h3 class="font-bold mb-2">Get Your Link</h3>
                    <p class="text-gray-600 text-sm">Receive your unique referral code and tracking links</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">3</span>
                    </div>
                    <h3 class="font-bold mb-2">Share & Promote</h3>
                    <p class="text-gray-600 text-sm">Share your link with your audience</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">4</span>
                    </div>
                    <h3 class="font-bold mb-2">Earn Commission</h3>
                    <p class="text-gray-600 text-sm">Get paid monthly for every successful referral</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefits -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Why Join Our Program?</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex gap-4">
                    <div class="text-green-600 text-3xl">💰</div>
                    <div>
                        <h3 class="font-bold mb-2">Recurring Revenue</h3>
                        <p class="text-gray-600">Earn commission for as long as your referrals remain customers</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <div class="text-green-600 text-3xl">📊</div>
                    <div>
                        <h3 class="font-bold mb-2">Real-time Tracking</h3>
                        <p class="text-gray-600">Monitor clicks, conversions, and earnings in your dashboard</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <div class="text-green-600 text-3xl">🎯</div>
                    <div>
                        <h3 class="font-bold mb-2">Marketing Materials</h3>
                        <p class="text-gray-600">Access banners, templates, and promotional content</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <div class="text-green-600 text-3xl">🤝</div>
                    <div>
                        <h3 class="font-bold mb-2">Dedicated Support</h3>
                        <p class="text-gray-600">Get help from our affiliate success team</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <div class="text-green-600 text-3xl">⚡</div>
                    <div>
                        <h3 class="font-bold mb-2">Fast Payouts</h3>
                        <p class="text-gray-600">Monthly payments via PayPal, bank transfer, or Stripe</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <div class="text-green-600 text-3xl">🌍</div>
                    <div>
                        <h3 class="font-bold mb-2">Global Program</h3>
                        <p class="text-gray-600">Promote to customers worldwide in multiple currencies</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-green-600 mb-2">$250K+</div>
                    <p class="text-gray-600">Paid to Affiliates</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-green-600 mb-2">500+</div>
                    <p class="text-gray-600">Active Affiliates</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-green-600 mb-2">95%</div>
                    <p class="text-gray-600">Payout Rate</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-green-600 mb-2">30 Days</div>
                    <p class="text-gray-600">Cookie Duration</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="font-bold mb-2">How do I get paid?</h3>
                    <p class="text-gray-600">We pay monthly via PayPal, bank transfer, or Stripe. Minimum payout is $50.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="font-bold mb-2">What is the cookie duration?</h3>
                    <p class="text-gray-600">Our cookies last 30-90 days depending on your tier, giving you credit for referrals even if they don't sign up immediately.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="font-bold mb-2">Can I promote on social media?</h3>
                    <p class="text-gray-600">Yes! You can promote on any platform including social media, blogs, YouTube, and email lists.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="font-bold mb-2">Is there a signup fee?</h3>
                    <p class="text-gray-600">No, our affiliate program is completely free to join with no hidden fees.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Earning?</h2>
        <p class="text-xl mb-8">Join hundreds of affiliates earning recurring commissions</p>
        <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
            Join Affiliate Program
        </a>
    </div>
</div>
@endsection
