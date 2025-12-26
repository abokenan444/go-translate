@extends('dashboard.layout')

@section('title', 'Government Dashboard')
@section('dashboard-title', 'Government Portal Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Welcome Card -->
    <div class="col-span-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Government Portal</h1>
        <p class="text-blue-100">Secure document translation for government entities</p>
    </div>

    <!-- Stats Cards -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Official Documents</h3>
        <p class="text-3xl font-bold text-blue-600">0</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Certified Translations</h3>
        <p class="text-3xl font-bold text-green-600">0</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Pending Approval</h3>
        <p class="text-3xl font-bold text-yellow-600">0</p>
    </div>

    <!-- Quick Actions -->
    <div class="col-span-3 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Government Services</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="#" class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center">
                <div class="text-blue-600 text-3xl mb-2">🏛️</div>
                <div class="font-semibold">Official Documents</div>
            </a>
            <a href="#" class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center">
                <div class="text-green-600 text-3xl mb-2">✓</div>
                <div class="font-semibold">Certified Translations</div>
            </a>
            <a href="#" class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center">
                <div class="text-purple-600 text-3xl mb-2">📊</div>
                <div class="font-semibold">Reports</div>
            </a>
            <a href="#" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center">
                <div class="text-gray-600 text-3xl mb-2">⚙️</div>
                <div class="font-semibold">Settings</div>
            </a>
        </div>
    </div>
</div>
@endsection
