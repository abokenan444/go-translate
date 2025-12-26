<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-indigo-600">Cultural Translate</a>
                <a href="/certified-translation" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Get Certified Translation
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Certificate Verification</h1>
            <p class="text-xl text-gray-600">Verify the authenticity of certified translations</p>
        </div>

        @if(isset($found) && $found)
            <!-- Certificate Found -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
                <div class="flex items-center justify-center mb-8">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-center text-green-800 mb-8">✓ Certificate Verified</h2>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Certificate Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Certificate ID</p>
                            <p class="text-lg font-bold text-gray-900 font-mono">{{ $certificate_id }}</p>
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Document Type</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $document_type }}</p>
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Source Language</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $source_language }}</p>
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Target Language</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $target_language }}</p>
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Translation Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $translation_date }}</p>
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            @if($status === 'valid')
                                <p class="text-lg font-semibold text-green-600 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Valid
                                </p>
                            @elseif($status === 'frozen')
                                <p class="text-lg font-semibold text-yellow-600 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Frozen (Temporarily Suspended)
                                </p>
                            @elseif($status === 'revoked')
                                <p class="text-lg font-semibold text-red-600 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Revoked (Permanently Invalid)
                                </p>
                            @else
                                <p class="text-lg font-semibold text-gray-600 capitalize">{{ $status }}</p>
                            @endif
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Delivery Method</p>
                            <p class="text-lg font-semibold text-gray-900 capitalize">{{ $delivery_method }}</p>
                        </div>

                        @if($delivery_method === 'physical' && isset($shipping_status))
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-sm text-gray-500 mb-1">Shipping Status</p>
                            <p class="text-lg font-semibold text-indigo-600 capitalize">{{ $shipping_status }}</p>
                        </div>
                        @endif

                        @if(isset($tracking_number) && $tracking_number)
                        <div class="bg-white rounded-xl p-4 shadow-sm md:col-span-2">
                            <p class="text-sm text-gray-500 mb-1">Tracking Number</p>
                            <p class="text-lg font-bold text-gray-900 font-mono">{{ $tracking_number }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if(isset($revocation_info) && $revocation_info)
                <!-- Revocation Information -->
                <div class="bg-red-50 rounded-2xl p-6 border-2 border-red-300 mb-8">
                    <div class="flex items-start gap-4">
                        <svg class="w-8 h-8 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-900 mb-3 text-lg">⚠ {{ $status === 'frozen' ? 'Temporarily Suspended' : 'Permanently Revoked' }}</h4>
                            <div class="space-y-2 text-sm">
                                <p class="text-gray-700">
                                    <strong>Action Date:</strong> {{ $revocation_info['effective_from'] ?? 'N/A' }}
                                </p>
                                @if(isset($revocation_info['legal_reference']))
                                <p class="text-gray-700">
                                    <strong>Legal Reference:</strong> {{ $revocation_info['legal_reference'] }}
                                </p>
                                @endif
                                @if(isset($revocation_info['jurisdiction']))
                                <p class="text-gray-700">
                                    <strong>Jurisdiction:</strong> {{ $revocation_info['jurisdiction'] }}
                                </p>
                                @endif
                                <div class="mt-4 p-4 bg-white rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Ledger Hash (Tamper-Evident):</p>
                                    <p class="font-mono text-xs text-gray-700 break-all">{{ $revocation_info['ledger_hash'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border-2 border-indigo-200">
                    <div class="flex items-start gap-4">
                        <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Verification Information</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                This certificate has been verified as authentic and was issued by Cultural Translate. 
                                The translation has been certified by our professional translators and includes dual stamp certification. 
                                This document is accepted by embassies, universities, courts, and government institutions worldwide.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500 mb-4">Need to verify another certificate?</p>
                    <a href="/certified-translation" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Get Certified Translation
                    </a>
                </div>
            </div>
        @else
            <!-- Certificate Not Found -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
                <div class="flex items-center justify-center mb-8">
                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-center text-red-800 mb-6">Certificate Not Found</h2>

                <div class="bg-red-50 rounded-2xl p-6 border-2 border-red-200 mb-8">
                    <p class="text-center text-gray-700 mb-4">
                        The certificate ID <span class="font-mono font-bold">{{ $certificate_id }}</span> could not be found in our system.
                    </p>
                    <p class="text-center text-sm text-gray-600">
                        This could mean:
                    </p>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1">•</span>
                            <span>The certificate ID was entered incorrectly</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1">•</span>
                            <span>The certificate has not been issued yet</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1">•</span>
                            <span>The certificate may be fraudulent</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border-2 border-indigo-200 mb-8">
                    <h4 class="font-bold text-gray-900 mb-3">What to do next:</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Double-check the certificate ID and try again</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Contact the person who provided the certificate</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Contact our support team if you believe this is an error</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="/certified-translation" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Get Certified Translation
                    </a>
                    <a href="/contact" class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">© 2025 Cultural Translate. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
