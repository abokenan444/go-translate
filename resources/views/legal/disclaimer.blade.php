@extends('layouts.app')

@section('title', 'Legal Disclaimer - Cultural Translate Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-slate-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-gray-700 to-slate-800 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Legal Disclaimer</h1>
                <p class="text-xl text-gray-200 max-w-3xl mx-auto">
                    Certificate Verification & Legal Notice
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-yellow-400">
            <div class="bg-yellow-50 border-b-2 border-yellow-400 p-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900">Important Legal Notice</h2>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <div class="prose prose-lg max-w-none">
                    <p class="text-xl text-gray-800 leading-relaxed mb-8 font-medium">
                        This certificate has been issued under the <strong>CTS™ Governance Framework</strong> operated by the <strong>Cultural Translate Platform</strong>.
                    </p>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Certificate Governance</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Certificate issuance, suspension, revocation, restoration, and audit actions are recorded in an <strong>append-only decision ledger</strong> and are subject to review by authorized authority roles.
                        </p>
                    </div>

                    <div class="bg-gray-50 border-l-4 border-gray-500 p-6 rounded-r-xl mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Verification Purpose</h3>
                        <p class="text-gray-700 leading-relaxed">
                            This verification page provides <strong>public authenticity validation only</strong> and does not constitute legal advice, notarization, or sovereign governmental endorsement unless explicitly stated by a competent authority.
                        </p>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-r-xl mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Jurisdictional Notice</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Jurisdiction, enforceability, and legal effect may vary depending on applicable laws and the accepting institution.
                        </p>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-xl">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Limitation of Liability</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Cultural Translate Platform makes no warranty regarding the acceptance, legal enforceability, or effect of this certificate outside the stated governance framework. Users and relying parties should verify applicability with relevant authorities in their jurisdiction.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="grid md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">What This Means</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    Certificates are genuine and verifiable within our governance system. They represent professional translation and cultural adaptation services under defined quality standards.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Need Help?</h3>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    For questions about certificate validity or legal standing in your jurisdiction:
                </p>
                <a href="mailto:legal@culturaltranslate.com" class="text-blue-600 hover:text-blue-800 font-semibold">
                    legal@culturaltranslate.com
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
