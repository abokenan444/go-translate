@extends('layouts.app')

@section('title', 'Verify Certificate - ' . $portal->country_name . ' Portal')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-800 to-green-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="text-sm text-green-200 mb-2">
                <a href="{{ route('gov.directory') }}" class="hover:text-white">Portals</a>
                <span class="mx-2">›</span>
                <a href="{{ route('gov.portal.index', ['country' => strtolower($portal->country_code)]) }}" class="hover:text-white">{{ $portal->country_name }}</a>
                <span class="mx-2">›</span>
                <span>Verify Certificate</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold">Certificate Verification</h1>
            <p class="text-green-100 mt-2">Verify the authenticity of a certified translation</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(isset($certificate))
            {{-- Certificate Verification Result --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Status Banner --}}
                @if($certificate->is_valid)
                    <div class="bg-green-50 border-b border-green-100 p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-green-800">Certificate Verified</h2>
                        <p class="text-green-600 mt-1">This is an authentic certified translation certificate</p>
                    </div>
                @else
                    <div class="bg-red-50 border-b border-red-100 p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-red-800">Verification Failed</h2>
                        <p class="text-red-600 mt-1">{{ $certificate->error_message ?? 'This certificate could not be verified' }}</p>
                    </div>
                @endif

                @if($certificate->is_valid)
                    {{-- Certificate Details --}}
                    <div class="p-6 md:p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Certificate Details</h3>
                        
                        <dl class="grid md:grid-cols-2 gap-x-8 gap-y-4">
                            <div>
                                <dt class="text-sm text-gray-500">Certificate ID</dt>
                                <dd class="font-mono font-medium text-gray-900">{{ $certificate->certificate_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Reference Number</dt>
                                <dd class="font-mono font-medium text-gray-900">{{ $certificate->reference_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Document Type</dt>
                                <dd class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $certificate->document_type)) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Certification Type</dt>
                                <dd class="font-medium text-gray-900">
                                    <span class="inline-flex items-center">
                                        {{ ucfirst($certificate->certification_type) }}
                                        @if($certificate->certification_type === 'notarized')
                                            <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">Notarized</span>
                                        @endif
                                        @if($certificate->has_apostille)
                                            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full">Apostille</span>
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Source Language</dt>
                                <dd class="font-medium text-gray-900">{{ strtoupper($certificate->source_language) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Target Language</dt>
                                <dd class="font-medium text-gray-900">{{ strtoupper($certificate->target_language) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Issue Date</dt>
                                <dd class="font-medium text-gray-900">{{ $certificate->issued_at->format('F j, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Valid Until</dt>
                                <dd class="font-medium {{ $certificate->expires_at && $certificate->expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                    @if($certificate->expires_at)
                                        {{ $certificate->expires_at->format('F j, Y') }}
                                        @if($certificate->expires_at->isPast())
                                            <span class="text-red-600 text-sm">(Expired)</span>
                                        @endif
                                    @else
                                        No expiration
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Translator Info --}}
                    <div class="border-t p-6 md:p-8 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Certified By</h3>
                        
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $certificate->translator_name ?? 'Certified Professional Translator' }}</p>
                                <p class="text-sm text-gray-500">
                                    @if($certificate->translator_credentials)
                                        {{ $certificate->translator_credentials }}
                                    @else
                                        Verified Partner Translator
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Verification Timestamp --}}
                    <div class="border-t p-6 md:p-8">
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>Verified at: {{ now()->format('F j, Y g:i A') }} ({{ config('app.timezone') }})</span>
                            <span>IP: {{ request()->ip() }}</span>
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- Verification Form --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="max-w-xl mx-auto text-center">
                        <svg class="mx-auto h-16 w-16 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Verify a Certificate</h2>
                        <p class="text-gray-600 mb-6">
                            Enter the certificate ID to verify the authenticity of a certified translation document.
                        </p>

                        <form action="{{ route('gov.portal.verify', ['country' => strtolower($portal->country_code)]) }}" method="GET">
                            <div class="flex gap-4">
                                <input type="text" 
                                       name="certificate" 
                                       placeholder="Enter certificate ID"
                                       value="{{ request('certificate') }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 font-mono"
                                       required>
                                <button type="submit" 
                                        class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                    Verify
                                </button>
                            </div>
                            <p class="mt-3 text-sm text-gray-500">
                                The certificate ID is found at the bottom of the translated document.
                            </p>
                        </form>

                        @if(request('certificate') && !isset($certificate))
                            <div class="mt-6 p-4 bg-red-50 rounded-lg">
                                <p class="text-red-600">
                                    <svg class="inline w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Certificate "{{ request('certificate') }}" could not be found. Please check the ID and try again.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- QR Code Scanner Option --}}
            <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">Scan QR Code</h3>
                            <p class="text-sm text-gray-500">Use your device's camera to scan the QR code on the certificate</p>
                        </div>
                        <button type="button" 
                                onclick="openQRScanner()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Scan
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Security Notice --}}
        <div class="mt-8 p-6 bg-blue-50 rounded-xl">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-900">Security Information</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        This verification system uses secure cryptographic signatures to ensure certificate authenticity. 
                        All certificates include tamper-proof digital watermarks and are stored in our secure database.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openQRScanner() {
    // QR Scanner implementation - would use a library like html5-qrcode
    alert('QR Scanner feature coming soon. Please enter the certificate ID manually.');
}
</script>
@endpush
@endsection
