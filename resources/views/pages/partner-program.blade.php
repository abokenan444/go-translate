@extends('layouts.app')
@section('title', 'Partner Program - Join Our Network')
@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Partner Program</h1>
        <div class="bg-white rounded-lg shadow-lg p-8">
            <p class="text-lg text-gray-700 mb-6">
                Join our global network of certified translation partners and grow your business 
                with access to exclusive tools, resources, and client referrals.
            </p>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Partner Benefits</h2>
            <ul class="space-y-3 text-gray-700 mb-8">
                <li>✓ Access to partner dashboard</li>
                <li>✓ Client referrals and leads</li>
                <li>✓ CTS™ certification training</li>
                <li>✓ Marketing materials and support</li>
                <li>✓ Competitive revenue sharing</li>
            </ul>
            <a href="{{ route('partner.application') }}" class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                Apply Now
            </a>
        </div>
    </div>
</div>
@endsection
