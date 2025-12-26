@extends('dashboard.layout')

@section('title', 'Affiliate Dashboard')
@section('dashboard-title', 'Affiliate Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-3 bg-gradient-to-r from-orange-600 to-red-600 text-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Affiliate Dashboard</h1>
        <p class="text-orange-100">Track your referrals and earnings</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Earnings</h3>
        <p class="text-3xl font-bold text-green-600">${{ number_format($stats['total_earnings'], 2) }}</p>
        <p class="text-sm text-gray-500 mt-1">${{ number_format($stats['pending_earnings'], 2) }} pending</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Referrals</h3>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_referrals']) }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ $stats['conversions'] }} conversions</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Commission Rate</h3>
        <p class="text-3xl font-bold text-purple-600">{{ $stats['commission_rate'] }}%</p>
        <p class="text-sm text-gray-500 mt-1">${{ number_format($stats['this_month_earnings'], 2) }} this month</p>
    </div>

    <div class="col-span-3 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Affiliate Tools</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <a href="{{ route('dashboard') }}#links" class="bg-orange-100 hover:bg-orange-200 p-4 rounded-lg text-center transition">
                <div class="text-orange-600 text-3xl mb-2">🔗</div>
                <div class="font-semibold">My Links</div>
            </a>
            <a href="{{ route('dashboard') }}#payouts" class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center transition">
                <div class="text-green-600 text-3xl mb-2">💰</div>
                <div class="font-semibold">Earnings</div>
            </a>
            <a href="{{ route('dashboard') }}#referrals" class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center transition">
                <div class="text-blue-600 text-3xl mb-2">👥</div>
                <div class="font-semibold">Referrals</div>
            </a>
            <a href="{{ route('tickets.index') }}" class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center transition">
                <div class="text-purple-600 text-3xl mb-2">🎫</div>
                <div class="font-semibold">Support</div>
            </a>
            <a href="{{ route('dashboard') }}#settings" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                <div class="text-gray-600 text-3xl mb-2">⚙️</div>
                <div class="font-semibold">Settings</div>
            </a>
        </div>
    </div>
    
    @if($apiKey)
    <div class="col-span-3 bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-lg shadow border border-blue-200">
        <h3 class="font-bold text-lg mb-3 flex items-center">
            <i class="fas fa-key text-purple-600 mr-2"></i> Your API Key
        </h3>
        <div class="bg-white p-4 rounded-lg font-mono text-sm break-all">
            {{ $apiKey->key }}
        </div>
        <p class="text-sm text-gray-600 mt-2">
            <i class="fas fa-info-circle mr-1"></i>
            Use this key for tracking your referrals via API
        </p>
    </div>
    @endif
</div>
@endsection
