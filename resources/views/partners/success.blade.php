@extends('layouts.app')
@section('title', 'Application Submitted')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md mx-auto text-center p-8">
        <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold mb-4">Application Submitted!</h1>
        <p class="text-gray-600 mb-6">Thank you for your interest in becoming a partner. We'll review your application and get back to you within 2-3 business days.</p>
        <a href="{{ route('home') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg">Back to Home</a>
    </div>
</div>
@endsection
