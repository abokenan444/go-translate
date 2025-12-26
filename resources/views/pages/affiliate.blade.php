@extends('layouts.app')

@section('title', 'Affiliate Program - CulturalTranslate')

@section('content')
<div class="container mx-auto px-4 py-10">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Join Our Affiliate Program</h1>
        <p class="text-xl text-gray-600">Earn 20% recurring commission by referring customers to CulturalTranslate</p>
    </div>

    <!-- Benefits Grid -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="text-blue-600 text-3xl mb-4">💰</div>
            <h3 class="text-xl font-semibold mb-2">20% Commission</h3>
            <p class="text-gray-600">Earn recurring commissions on all payments from your referrals for their lifetime</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="text-blue-600 text-3xl mb-4">📊</div>
            <h3 class="text-xl font-semibold mb-2">Real-Time Analytics</h3>
            <p class="text-gray-600">Track your clicks, conversions, and earnings in real-time from your dashboard</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="text-blue-600 text-3xl mb-4">🎯</div>
            <h3 class="text-xl font-semibold mb-2">Marketing Materials</h3>
            <p class="text-gray-600">Access banners, landing pages, and promotional content to maximize conversions</p>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 mb-16">
        <h2 class="text-2xl font-bold text-center mb-8">How It Works</h2>
        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                <h4 class="font-semibold mb-2">Sign Up</h4>
                <p class="text-sm text-gray-600">Create your affiliate account and get your unique referral link</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                <h4 class="font-semibold mb-2">Share</h4>
                <p class="text-sm text-gray-600">Promote CulturalTranslate using your link on your website, blog, or social media</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                <h4 class="font-semibold mb-2">Earn</h4>
                <p class="text-sm text-gray-600">Get 20% commission on every payment from customers you refer</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                <h4 class="font-semibold mb-2">Get Paid</h4>
                <p class="text-sm text-gray-600">Receive monthly payouts directly to your account</p>
            </div>
        </div>
    </div>

    <!-- Commission Structure -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-16">
        <h2 class="text-2xl font-bold mb-6">Commission Structure</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monthly Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Your Commission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Annual Potential</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Starter</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$29/month</td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">$5.80/month</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$69.60/year</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Professional</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$99/month</td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">$19.80/month</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$237.60/year</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Business</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$299/month</td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">$59.80/month</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$717.60/year</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Enterprise</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$999+/month</td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">$199.80+/month</td>
                        <td class="px-6 py-4 text-sm text-gray-600">$2,397.60+/year</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-sm text-gray-500 mt-4">* Commissions are recurring for the lifetime of the customer subscription</p>
    </div>

    <!-- CTA -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-8 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Earning?</h2>
        <p class="text-xl mb-6 opacity-90">Join hundreds of affiliates earning passive income with CulturalTranslate</p>
        @auth
            <a href="/dashboard" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Go to Dashboard
            </a>
        @else
            <a href="/register" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Sign Up Now
            </a>
        @endauth
    </div>

    <!-- FAQ -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions</h2>
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-semibold mb-2">When do I get paid?</h4>
                <p class="text-gray-600">Commissions are paid monthly via PayPal or bank transfer. Minimum payout threshold is $50.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-semibold mb-2">How long do cookies last?</h4>
                <p class="text-gray-600">Our referral cookies last for 30 days, giving your referrals plenty of time to convert.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-semibold mb-2">Can I promote on social media?</h4>
                <p class="text-gray-600">Absolutely! Share your link on Twitter, LinkedIn, Facebook, YouTube, or any platform you prefer.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-semibold mb-2">Is there a limit to how much I can earn?</h4>
                <p class="text-gray-600">No limits! The more customers you refer, the more you earn. Top affiliates earn $5,000+ per month.</p>
            </div>
        </div>
    </div>
</div>
@endsection
