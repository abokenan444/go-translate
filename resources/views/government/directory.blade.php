@extends('layouts.app')

@section('title', 'Government Translation Portals - Cultural Translate')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800">
    {{-- Hero Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Government Translation Portals
            </h1>
            <p class="text-xl text-blue-200 max-w-3xl mx-auto">
                Official certified translation services for government documents. 
                Select your country to access jurisdiction-specific translation requirements and services.
            </p>
        </div>
    </div>

    {{-- Portals Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @foreach($portals as $region => $regionPortals)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="bg-blue-600 rounded-lg px-4 py-2">{{ $region }}</span>
                    <span class="ml-4 text-blue-300 text-sm font-normal">
                        {{ $regionPortals->count() }} {{ Str::plural('country', $regionPortals->count()) }}
                    </span>
                </h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @foreach($regionPortals as $portal)
                        <a href="{{ route('gov.portal.index', ['country' => strtolower($portal->country_code)]) }}"
                           class="group bg-white rounded-lg p-4 shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                            <div class="flex flex-col items-center text-center">
                                {{-- Country Flag (using emoji or flag API) --}}
                                <div class="text-4xl mb-2">
                                    {{ $this->getCountryFlag($portal->country_code) }}
                                </div>
                                
                                {{-- Country Name --}}
                                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $portal->country_name }}
                                </h3>
                                
                                {{-- Native Name --}}
                                @if($portal->country_name_native && $portal->country_name_native !== $portal->country_name)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $portal->country_name_native }}
                                    </p>
                                @endif

                                {{-- Requirements Badges --}}
                                <div class="flex flex-wrap justify-center gap-1 mt-2">
                                    @if($portal->requires_certified_translation)
                                        <span class="inline-block px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">
                                            Certified
                                        </span>
                                    @endif
                                    @if($portal->requires_apostille)
                                        <span class="inline-block px-2 py-0.5 text-xs bg-purple-100 text-purple-800 rounded">
                                            Apostille
                                        </span>
                                    @endif
                                    @if($portal->requires_notarization)
                                        <span class="inline-block px-2 py-0.5 text-xs bg-orange-100 text-orange-800 rounded">
                                            Notarized
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Info Section --}}
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Why Choose Our Government Translation Services?</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Certified Partners</h3>
                    <p class="text-gray-600">All translations are performed by certified sworn translators authorized in their respective jurisdictions.</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Full Compliance</h3>
                    <p class="text-gray-600">Documents meet all legal requirements for government submissions, including apostille and notarization when required.</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Verified</h3>
                    <p class="text-gray-600">Every translation includes QR code verification and a complete audit trail for authenticity.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@php
function getCountryFlag($countryCode) {
    // Convert country code to regional indicator symbols (emoji flags)
    $code = strtoupper($countryCode);
    if (strlen($code) !== 2) return '🌐';
    
    $flagOffset = 0x1F1E6 - ord('A');
    return mb_chr(ord($code[0]) + $flagOffset) . mb_chr(ord($code[1]) + $flagOffset);
}
$this->getCountryFlag = fn($code) => getCountryFlag($code);
@endphp
@endsection
