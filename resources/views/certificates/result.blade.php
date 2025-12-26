@extends('layouts.app')
@section('title', 'Certificate Verification Result')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            @if($found)
                <div class="bg-white rounded-2xl shadow-2xl p-12 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-24 w-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">✅ Certificate Verified!</h1>
                    <p class="text-xl text-gray-600 mb-8">This certificate is authentic and valid.</p>
                    
                    <!-- Certificate Details -->
                    <div class="bg-gray-50 rounded-xl p-6 text-left space-y-4">
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Certificate ID:</span>
                            <span class="text-gray-900 font-mono text-sm">{{ $certificate->cert_id }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Document ID:</span>
                            <span class="text-gray-900">{{ $certificate->document_id }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Issue Date:</span>
                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($certificate->issued_at ?? $certificate->created_at)->format('Y-m-d H:i') }} UTC</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Expiry Date:</span>
                            <span class="text-gray-900">{{ $certificate->expires_at ? \Carbon\Carbon::parse($certificate->expires_at)->format('Y-m-d') : 'No Expiry' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">Legal Status:</span>
                            @if($legalStatus === 'valid')
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">✅ Valid & Enforceable</span>
                                </span>
                            @elseif($legalStatus === 'frozen')
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">⚠️ Temporarily Suspended</span>
                                </span>
                            @else
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">❌ Revoked - Invalid</span>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Governance Context -->
                    <div class="mt-6 bg-blue-50 border-2 border-blue-200 rounded-xl p-6 text-left">
                        <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Issued Under Governance Framework
                        </h3>
                        <div class="space-y-3 text-sm text-blue-900">
                            <div class="flex justify-between py-2 border-b border-blue-200">
                                <span class="font-semibold">Standard:</span>
                                <span class="font-mono bg-blue-100 px-2 py-1 rounded">CTS™ v1.0</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-blue-200">
                                <span class="font-semibold">Jurisdiction:</span>
                                <span>Netherlands / European Union</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-blue-200">
                                <span class="font-semibold">Purpose:</span>
                                <span>Certified Cultural Translation</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-blue-200">
                                <span class="font-semibold">Review Process:</span>
                                <span>AI + Human Expert Review</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="font-semibold">Platform Operator:</span>
                                <span class="font-semibold text-blue-700">Cultural Translate Platform</span>
                            </div>
                            <div class="text-center mt-3 pt-3 border-t border-blue-200">
                                <p class="text-xs text-blue-800 italic">
                                    A Platform with Government-Grade Governance
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <p class="text-xs text-blue-700">
                                <strong>Legal States:</strong> Valid • Frozen (Under Review) • Revoked • Expired
                            </p>
                            <p class="text-xs text-blue-600 mt-2">
                                This certificate is subject to verification through our Authority Console and Decision Ledger system. Any changes to legal status are recorded in an append-only audit trail.
                            </p>
                        </div>
                        
                        <!-- Legal Notice -->
                        <div class="mt-4 pt-4 border-t-2 border-blue-300 bg-blue-100 rounded-lg p-4">
                            <p class="text-xs text-gray-700 leading-relaxed">
                                <span class="inline-flex items-center gap-1 font-semibold text-blue-900 mb-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                                    </svg>
                                    Legal Notice
                                </span>
                                <br>
                                This certificate has been issued under the <strong>CTS™ Governance Framework</strong> operated by Cultural Translate Platform. 
                                All certificate lifecycle actions — including issuance, suspension, revocation, and restoration — are recorded in an append-only decision ledger and may be audited by authorized authority roles. 
                                This verification page provides <strong>public authenticity validation only</strong> and does not, by itself, constitute legal advice, notarization, or sovereign governmental endorsement unless explicitly stated by a competent authority. 
                                Jurisdiction, enforceability, and legal effect may vary depending on applicable laws and the accepting institution.
                            </p>
                        </div>
                    </div>
                    
                    @if($legalStatus !== 'valid' && $revocation)
                        <div class="mt-6 bg-red-50 border-2 border-red-200 rounded-xl p-6 text-left">
                            <h3 class="text-lg font-bold text-red-900 mb-3 flex items-center gap-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                                ⚠️ Authority Action Recorded
                            </h3>
                            <div class="space-y-3 text-sm text-red-900">
                                <div class="bg-red-100 rounded-lg p-4">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-semibold">Action Type:</span>
                                        <span class="px-3 py-1 bg-red-200 rounded-full text-xs font-bold uppercase">
                                            {{ $revocation->action }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between border-t border-red-200 pt-2 mt-2">
                                        <span class="font-semibold">Effective From:</span>
                                        <span>{{ \Carbon\Carbon::parse($revocation->effective_from)->format('Y-m-d H:i') }} UTC</span>
                                    </div>
                                </div>
                                
                                @if($revocation->authority_reference_id ?? null)
                                <div class="bg-red-100 rounded-lg p-3 border-l-4 border-red-400">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-red-900">Authority Reference ID:</span>
                                        <span class="font-mono text-xs bg-red-200 px-2 py-1 rounded">{{ $revocation->authority_reference_id }}</span>
                                    </div>
                                    @if($revocation->authority_jurisdiction ?? null)
                                    <div class="flex justify-between mt-2">
                                        <span class="font-semibold text-red-800">Authority Jurisdiction:</span>
                                        <span class="text-xs">{{ $revocation->authority_jurisdiction }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                                
                                @if($revocation->legal_reference)
                                <div class="flex justify-between py-2 border-b border-red-200">
                                    <span class="font-semibold">Legal Reference:</span>
                                    <span class="font-mono text-xs">{{ $revocation->legal_reference }}</span>
                                </div>
                                @endif
                                
                                @if($revocation->jurisdiction_country)
                                <div class="flex justify-between py-2 border-b border-red-200">
                                    <span class="font-semibold">Jurisdiction:</span>
                                    <span>{{ strtoupper($revocation->jurisdiction_country) }}{{ $revocation->jurisdiction_purpose ? ' - ' . $revocation->jurisdiction_purpose : '' }}</span>
                                </div>
                                @endif
                                
                                @if($revocation->legal_basis_code)
                                <div class="flex justify-between py-2 border-b border-red-200">
                                    <span class="font-semibold">Legal Basis Code:</span>
                                    <span class="font-mono text-xs">{{ $revocation->legal_basis_code }}</span>
                                </div>
                                @endif
                                
                                <div class="mt-4 pt-4 border-t-2 border-red-300">
                                    <span class="font-semibold text-base">Reason for Action:</span>
                                    <p class="mt-2 bg-white p-3 rounded border border-red-200 text-red-800">{{ $revocation->reason }}</p>
                                </div>
                                
                                @if($revocation->ledger_event_id)
                                <div class="mt-4 pt-4 border-t border-red-200">
                                    <p class="text-xs text-red-700 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <strong>Audit Trail:</strong> Recorded in Decision Ledger (Event ID: {{ $revocation->ledger_event_id }})
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <a href="{{ route('verify.index') }}" class="mt-8 inline-block bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition">
                        Verify Another Certificate
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-2xl p-12 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-24 w-24 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">❌ Certificate Not Found</h1>
                    <p class="text-xl text-gray-600 mb-8">The certificate code you entered is invalid or has been revoked.</p>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8 text-left">
                        <p class="text-sm text-yellow-800">
                            <strong>Note:</strong> Please double-check the certificate code and try again. If you believe this is an error, contact our support team.
                        </p>
                    </div>
                    
                    <a href="{{ route('verify.index') }}" class="inline-block bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition">
                        Try Again
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
