@extends('layouts.app')

@section('title', $portal->country_name . ' Government Translation Portal - Cultural Translate')

@section('content')
<div class="min-h-screen">
    {{-- Portal Header --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    {{-- Country Flag --}}
                    <div class="text-6xl">
                        @php
                            $code = strtoupper($portal->country_code);
                            $flagOffset = 0x1F1E6 - ord('A');
                            $flag = mb_chr(ord($code[0]) + $flagOffset) . mb_chr(ord($code[1]) + $flagOffset);
                        @endphp
                        {{ $flag }}
                    </div>
                    
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold">
                            {{ $portal->country_name }}
                        </h1>
                        @if($portal->country_name_native && $portal->country_name_native !== $portal->country_name)
                            <p class="text-xl text-blue-200 mt-1">{{ $portal->country_name_native }}</p>
                        @endif
                        <p class="text-blue-200 mt-2">Official Government Document Translation Portal</p>
                    </div>
                </div>

                {{-- Portal Logo if available --}}
                @if($portal->logo_path)
                    <img src="{{ asset($portal->logo_path) }}" alt="{{ $portal->country_name }} Portal" class="h-16">
                @endif
            </div>
        </div>
    </div>

    {{-- Requirements Banner --}}
    <div class="bg-blue-50 border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap items-center gap-4 text-sm">
                <span class="font-semibold text-blue-900">Document Requirements:</span>
                
                @if($portal->requires_certified_translation)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Certified Translation Required
                    </span>
                @endif
                
                @if($portal->requires_notarization)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Notarization Required
                    </span>
                @endif
                
                @if($portal->requires_apostille)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Apostille Required
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Actions --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Submit Document Card --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Submit a Document for Translation</h2>
                        <p class="text-gray-600 mb-6">
                            Upload your official document and our certified translators will review and translate it 
                            according to {{ $portal->country_name }}'s requirements.
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <a href="{{ route('gov.portal.submit', ['country' => strtolower($portal->country_code)]) }}"
                               class="flex items-center justify-center px-6 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Submit Document
                            </a>

                            <a href="{{ route('gov.portal.pricing', ['country' => strtolower($portal->country_code)]) }}"
                               class="flex items-center justify-center px-6 py-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                View Pricing
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Track Document --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Track Your Document</h2>
                        <form action="#" method="GET" class="flex gap-4">
                            <input type="text" name="reference" placeholder="Enter reference number" 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="px-6 py-3 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 transition-colors">
                                Track
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Verify Document --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Verify a Certificate</h2>
                        <p class="text-gray-600 mb-4">
                            Verify the authenticity of a translated document certificate using its unique code.
                        </p>
                        <form action="#" method="GET" class="flex gap-4">
                            <input type="text" name="certificate" placeholder="Enter certificate ID" 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                Verify
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Quick Info --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Portal Information</h3>
                    
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Default Language</dt>
                            <dd class="font-medium text-gray-900">{{ strtoupper($portal->default_language) }}</dd>
                        </div>
                        
                        @if($portal->supported_languages)
                            <div>
                                <dt class="text-gray-500">Supported Languages</dt>
                                <dd class="font-medium text-gray-900">
                                    {{ collect($portal->supported_languages)->map(fn($l) => strtoupper($l))->join(', ') }}
                                </dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-gray-500">Currency</dt>
                            <dd class="font-medium text-gray-900">{{ $portal->currency_code }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-gray-500">Timezone</dt>
                            <dd class="font-medium text-gray-900">{{ $portal->timezone }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Contact --}}
                @if($portal->contact_email || $portal->contact_phone)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Contact</h3>
                        
                        @if($portal->contact_email)
                            <div class="flex items-center text-sm mb-3">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:{{ $portal->contact_email }}" class="text-blue-600 hover:underline">
                                    {{ $portal->contact_email }}
                                </a>
                            </div>
                        @endif
                        
                        @if($portal->contact_phone)
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:{{ $portal->contact_phone }}" class="text-blue-600 hover:underline">
                                    {{ $portal->contact_phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Quick Links --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('gov.portal.requirements', ['country' => strtolower($portal->country_code)]) }}" 
                               class="text-blue-600 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Document Requirements
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('gov.directory') }}" class="text-blue-600 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                All Country Portals
                            </a>
                        </li>
                        <li>
                            <a href="/help" class="text-blue-600 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Help Center
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Legal Disclaimer --}}
    @if($portal->legal_disclaimer)
        <div class="bg-gray-100 border-t">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-sm text-gray-600">
                    <h4 class="font-semibold text-gray-900 mb-2">Legal Notice</h4>
                    <p>{{ $portal->legal_disclaimer }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
