<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - {{ $certificate->certificate_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">CTS Certificate Verification</h1>
                <p class="mt-2 text-gray-600">CulturalTranslate Standard™ Certification</p>
            </div>

            <!-- Verification Status -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-8">
                    @if($isValid)
                        <div class="flex items-center justify-center mb-6">
                            <div class="flex items-center space-x-3">
                                <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h2 class="text-2xl font-bold text-green-600">Valid Certificate</h2>
                                    <p class="text-gray-600">This certificate is authentic and verified</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-center mb-6">
                            <div class="flex items-center space-x-3">
                                <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h2 class="text-2xl font-bold text-red-600">Invalid Certificate</h2>
                                    <p class="text-gray-600">This certificate has expired or been revoked</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Certificate Details -->
                    <div class="border-t border-gray-200 pt-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Certificate ID</dt>
                                <dd class="mt-1 text-lg font-mono text-gray-900">{{ $certificate->certificate_id }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">CTS Level</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($certificate->cts_level === 'CTS-A') bg-green-100 text-green-800
                                        @elseif($certificate->cts_level === 'CTS-B') bg-blue-100 text-blue-800
                                        @elseif($certificate->cts_level === 'CTS-C') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $certificate->cts_level }}
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cultural Impact Score</dt>
                                <dd class="mt-1">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900">{{ $certificate->cultural_impact_score }}</span>
                                        <span class="text-gray-500 ml-1">/100</span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            @if($certificate->cultural_impact_score >= 85) bg-green-500
                                            @elseif($certificate->cultural_impact_score >= 65) bg-blue-500
                                            @elseif($certificate->cultural_impact_score >= 40) bg-yellow-500
                                            @else bg-red-500
                                            @endif"
                                            style="width: {{ $certificate->cultural_impact_score }}%">
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Issued Date</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ $certificate->issued_at->format('F d, Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Languages</dt>
                                <dd class="mt-1 text-lg text-gray-900">
                                    {{ strtoupper($certificate->source_language) }} → {{ strtoupper($certificate->target_language) }}
                                </dd>
                            </div>

                            @if($certificate->target_country)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Target Country</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ strtoupper($certificate->target_country) }}</dd>
                            </div>
                            @endif

                            @if($certificate->use_case)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Use Case</dt>
                                <dd class="mt-1 text-lg text-gray-900 capitalize">{{ $certificate->use_case }}</dd>
                            </div>
                            @endif

                            @if($certificate->domain)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Domain</dt>
                                <dd class="mt-1 text-lg text-gray-900 capitalize">{{ $certificate->domain }}</dd>
                            </div>
                            @endif>
                        </dl>
                    </div>

                    <!-- Verification Stats -->
                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>Verified {{ $verificationCount }} times</span>
                            @if($lastVerifiedAt)
                            <span>Last verified: {{ $lastVerifiedAt->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-t border-gray-200 mt-6 pt-6 flex justify-center space-x-4">
                        @if($certificate->certificate_pdf_path)
                        <a href="{{ route('cts-verify.download', $certificate->certificate_id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Certificate
                        </a>
                        @endif

                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            @if($certificate->qr_code_path)
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification QR Code</h3>
                <img src="{{ Storage::url($certificate->qr_code_path) }}" 
                     alt="QR Code" 
                     class="mx-auto w-48 h-48 border-2 border-gray-200 p-2">
                <p class="mt-4 text-sm text-gray-600">Scan to verify this certificate</p>
            </div>
            @endif

            <!-- Back Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('cts-verify.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← Verify Another Certificate
                </a>
            </div>
        </div>
    </div>
</body>
</html>
