@extends('dashboard.layout')

@section('title', 'Partner Dashboard')
@section('dashboard-title', 'Partner Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-3 bg-gradient-to-r from-green-600 to-teal-600 text-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Partner Dashboard</h1>
        <p class="text-green-100">Manage your partnership and track commissions</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Revenue</h3>
        <p class="text-3xl font-bold text-green-600">$0</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Active Clients</h3>
        <p class="text-3xl font-bold text-blue-600">0</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Commission Rate</h3>
        <p class="text-3xl font-bold text-purple-600">15%</p>
    </div>

    <div class="col-span-3 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Partner Tools</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="#" class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center">
                <div class="text-green-600 text-3xl mb-2">👥</div>
                <div class="font-semibold">My Clients</div>
            </a>
            <a href="#" class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center">
                <div class="text-blue-600 text-3xl mb-2">💰</div>
                <div class="font-semibold">Earnings</div>
            </a>
            <a href="#" class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center">
                <div class="text-purple-600 text-3xl mb-2">📊</div>
                <div class="font-semibold">Analytics</div>
            </a>
            <a href="#" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center">
                <div class="text-gray-600 text-3xl mb-2">⚙️</div>
                <div class="font-semibold">Settings</div>
            </a>
        </div>
    </div>
</div>
@endsection
