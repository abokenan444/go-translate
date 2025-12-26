@extends('layouts.app')
@section('title', 'Affiliate Program - Cultural Translate')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Affiliate Program</h1>
            <p class="text-2xl text-gray-600">Coming Soon</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
            <p class="text-lg text-gray-700 mb-8">
                This page is currently under development. Check back soon for updates!
            </p>
            <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-lg">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
