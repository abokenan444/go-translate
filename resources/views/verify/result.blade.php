<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-3xl w-full">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Certificate Verification</h1>
                <p class="text-gray-400">Cultural Translate - Official Document Translation</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                @if(!$found)
                    <!-- Certificate Not Found -->
                    <div class="p-8">
                        <div class="text-center">
                            <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">Certificate Not Found</h2>
                            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                                <p class="text-red-800 font-medium mb-2">
                                    No certificate found for ID:
                                </p>
                                <p class="font-mono font-bold text-red-900 text-lg">{{ $certId }}</p>
                            </div>
                            <p class="text-gray-600 mb-6">
                                Please verify that you have entered the Certificate ID correctly as shown on the translated document.
                            </p>
                            <a href="{{ url('/') }}" class="inline-block bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                                Return to Homepage
                            </a>
                        </div>
                    </div>
                @else
                    @if($isValid)
                        <!-- Valid Certificate -->
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-8">
                            <div class="text-center text-white">
                                <div class="mx-auto w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 shadow-xl">
                                    <svg class="w-16 h-16 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold mb-3">✓ Valid Certificate</h2>
                                <p class="text-green-50 text-lg">
                                    This certified translation is authentic and has been verified.
                                </p>
                            </div>
                        </div>
                    @elseif($isExpired)
                        <!-- Expired Certificate -->
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-8">
                            <div class="text-center text-white">
                                <div class="mx-auto w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-16 h-16 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold mb-3">⚠ Certificate Expired</h2>
                                <p class="text-orange-50 text-lg">
                                    This certificate has surpassed its validity period.
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Invalid Certificate -->
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 p-8">
                            <div class="text-center text-white">
                                <div class="mx-auto w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold mb-3">✗ Invalid Certificate</h2>
                                <p class="text-red-50 text-lg">
                                    This certificate is not currently valid.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Certificate Details -->
                    <div class="p-8">
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Certificate ID</label>
                                <p class="text-lg font-mono font-bold text-gray-900 mt-1">{{ $certificate->cert_id }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Status</label>
                                <p class="text-lg font-bold mt-1
                                    @if($isValid) text-green-600
                                    @elseif($isExpired) text-yellow-600
                                    @else text-red-600
                                    @endif">
                                    @if($isValid) Valid
                                    @elseif($isExpired) Expired
                                    @else {{ ucfirst($certificate->status) }}
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Issued Date</label>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    {{ $certificate->issued_at ? $certificate->issued_at->format('F d, Y') : 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Document Type</label>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    {{ $document->document_type_name ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Translation</label>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="px-4 py-2 bg-white rounded-lg font-bold text-gray-900 border-2 border-gray-200">
                                        {{ strtoupper($document->source_language ?? '') }}
                                    </span>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    <span class="px-4 py-2 bg-white rounded-lg font-bold text-gray-900 border-2 border-gray-200">
                                        {{ strtoupper($document->target_language ?? '') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Issuer Information -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="font-bold text-lg mb-4 text-gray-900">Issuer Information</h3>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                <p class="font-semibold text-gray-900">Cultural Translate</p>
                                <p class="text-gray-600">Amsterdam, Netherlands</p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Certified translation service provider
                                </p>
                            </div>
                        </div>

                        <!-- Verification Note -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                            <div class="flex gap-4">
                                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Verification Note</h4>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        This verification confirms that the certified translation associated with the above Certificate ID was issued by Cultural Translate and has passed integrity checks against the original file hash. 
                                        <strong>Acceptance of this translation remains subject to the rules and requirements of the receiving authority</strong> (e.g., embassy, consulate, court, or government office).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-center md:text-left">
                            <p class="text-sm text-gray-600">
                                Powered by <span class="font-semibold text-gray-900">Cultural Translate</span>
                            </p>
                            <p class="text-xs text-gray-500">Amsterdam, Netherlands</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Visit Website
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ url('/official-documents') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Get Translation
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Info -->
            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    API Verification Available: 
                    <code class="bg-gray-800 px-2 py-1 rounded text-gray-300 text-xs">
                        GET {{ url('/api/certificates/' . ($certId ?? '{certId}')) }}
                    </code>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
