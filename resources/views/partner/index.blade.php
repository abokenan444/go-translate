@extends('layouts.app')

@section('title', 'Partner Portal - Cultural Translate')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Partner Portal</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to the Partner Portal</h2>
            <p class="text-lg text-gray-700 mb-6">
                Join our global network of certified translation partners and grow your business with access to exclusive tools, resources, and client referrals.
            </p>
            
            <div class="grid md:grid-cols-3 gap-6 mt-8">
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✓ Partner Dashboard</h3>
                    <p class="text-gray-600">Access your dedicated dashboard with analytics and tools</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✓ Client Referrals</h3>
                    <p class="text-gray-600">Receive qualified leads and client referrals</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✓ CTS™ Certification</h3>
                    <p class="text-gray-600">Get certified in our Cultural Translation Standard</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✓ Marketing Support</h3>
                    <p class="text-gray-600">Access marketing materials and co-branding resources</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✓ Revenue Sharing</h3>
                    <p class="text-gray-600">Competitive revenue sharing model</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">✓ Training & Support</h3>
                    <p class="text-gray-600">Ongoing training and dedicated support team</p>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Ready to become a partner?</h3>
            <p class="text-gray-700 mb-6">Apply now and join our growing network of translation professionals</p>
            <a href="{{ route('partner.application') }}" class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                Apply Now
            </a>
        </div>
    </div>
</div>
@endsection
