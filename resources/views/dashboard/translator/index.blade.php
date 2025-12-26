@extends('dashboard.layout')

@section('title', 'Translator Dashboard')
@section('dashboard-title', 'Translator Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Translator Dashboard</h1>
        <p class="text-indigo-100">Manage your translation projects and certifications</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Completed Projects</h3>
        <p class="text-3xl font-bold text-green-600">0</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Active Projects</h3>
        <p class="text-3xl font-bold text-blue-600">0</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-semibold mb-2">Rating</h3>
        <p class="text-3xl font-bold text-yellow-600">0.0 ⭐</p>
    </div>

    <div class="col-span-3 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Translator Tools</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="#" class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center">
                <div class="text-blue-600 text-3xl mb-2">📝</div>
                <div class="font-semibold">New Projects</div>
            </a>
            <a href="#" class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center">
                <div class="text-green-600 text-3xl mb-2">✓</div>
                <div class="font-semibold">Completed</div>
            </a>
            <a href="#" class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center">
                <div class="text-purple-600 text-3xl mb-2">🎓</div>
                <div class="font-semibold">Certifications</div>
            </a>
            <a href="#" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center">
                <div class="text-gray-600 text-3xl mb-2">⚙️</div>
                <div class="font-semibold">Settings</div>
            </a>
        </div>
    </div>
</div>
@endsection
