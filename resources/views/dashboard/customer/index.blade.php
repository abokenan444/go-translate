@extends('dashboard.layout')

@section('title', 'Customer Dashboard')
@section('dashboard-title', 'Customer Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Welcome Card -->
    <div class="col-span-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ $user->name }}!</h1>
        <p class="text-purple-100">Manage your translations and documents from your dashboard</p>
    </div>

    <!-- Stats Cards -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Translations</h3>
        <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_translations']) }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ $stats['this_month'] }} this month</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Active Orders</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['active_orders'] }}</p>
        <p class="text-sm text-gray-500 mt-1">In progress</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Completed</h3>
        <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ number_format($stats['characters_used']) }} characters</p>
    </div>

    <!-- Quick Actions -->
    <div class="col-span-3 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <a href="{{ route('dashboard') }}" class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center transition">
                <div class="text-purple-600 text-3xl mb-2">📄</div>
                <div class="font-semibold">New Translation</div>
            </a>
            <a href="{{ route('dashboard') }}#history" class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center transition">
                <div class="text-blue-600 text-3xl mb-2">📋</div>
                <div class="font-semibold">My History</div>
            </a>
            <a href="{{ url('/pricing-plans') }}" class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center transition">
                <div class="text-green-600 text-3xl mb-2">💎</div>
                <div class="font-semibold">Upgrade Plan</div>
            </a>
            <a href="{{ route('tickets.index') }}" class="bg-orange-100 hover:bg-orange-200 p-4 rounded-lg text-center transition">
                <div class="text-orange-600 text-3xl mb-2">🎫</div>
                <div class="font-semibold">Support Tickets</div>
            </a>
            <a href="{{ route('dashboard') }}#settings" class="bg-yellow-100 hover:bg-yellow-200 p-4 rounded-lg text-center transition">
                <div class="text-yellow-600 text-3xl mb-2">⚙️</div>
                <div class="font-semibold">Settings</div>
            </a>
        </div>
    </div>
</div>
@endsection
