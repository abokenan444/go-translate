@extends('layouts.app')

@section('title', 'Certified Official Document Translation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">
                Certified Official Document Translation
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Get your official documents professionally translated and certified for use with embassies, government authorities, and international organizations.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('official.documents.upload') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                    Upload Document Now
                </a>
                <a href="{{ route('official.documents.my-documents') }}" class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-50 transition">
                    My Documents
                </a>
            </div>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="text-5xl mb-4">🔒</div>
                <h3 class="font-bold text-2xl mb-3 text-gray-900">Certified & Secure</h3>
                <p class="text-gray-600">Each translation receives a unique certificate ID with QR code verification for authenticity.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="text-5xl mb-4">⚡</div>
                <h3 class="font-bold text-2xl mb-3 text-gray-900">Fast Turnaround</h3>
                <p class="text-gray-600">Most documents processed within 24-48 hours with automated AI-assisted translation.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="text-5xl mb-4">💰</div>
                <h3 class="font-bold text-2xl mb-3 text-gray-900">Transparent Pricing</h3>
                <p class="text-gray-600">{{ number_format($pricing['default_price'], 2) }} {{ $pricing['currency'] }} per document. Simple, clear pricing.</p>
            </div>
        </div>

        <!-- Supported Documents -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-16">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">Supported Document Types</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($documentTypes as $key => $name)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <span class="text-blue-600">✓</span>
                        <span class="text-gray-700">{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- How It Works -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">How It Works</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="font-bold text-lg mb-2">Upload</h3>
                    <p class="text-gray-600">Upload your PDF document</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="font-bold text-lg mb-2">Pay</h3>
                    <p class="text-gray-600">Secure payment via Stripe</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="font-bold text-lg mb-2">Process</h3>
                    <p class="text-gray-600">AI translation + human review</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                    <h3 class="font-bold text-lg mb-2">Download</h3>
                    <p class="text-gray-600">Get certified PDF with seal</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl p-12">
            <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl mb-8 opacity-90">Upload your document now and receive your certified translation within 24-48 hours.</p>
            <a href="{{ route('official.documents.upload') }}" class="bg-white text-blue-600 px-10 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition inline-block">
                Upload Your Document
            </a>
        </div>
    </div>
</div>
@endsection
