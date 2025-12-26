@extends('layouts.app')

@section('title', 'Privacy Policy - Cultural Translate Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Privacy Policy</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    GDPR-Compliant • Government-Grade Protection
                </p>
                <p class="text-sm text-blue-200 mt-4">
                    Last Updated: {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8 md:p-12 prose prose-lg max-w-none">
                
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">1</span>
                    Introduction
                </h2>
                <p class="text-gray-700 leading-relaxed mb-8">
                    Cultural Translate Platform ("Cultural Translate", "we", "our", "the Platform") operates as a governed cultural translation, certification, and verification platform. We are committed to protecting personal data and institutional information in accordance with the <strong>EU General Data Protection Regulation (GDPR)</strong> and applicable European data protection laws.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">2</span>
                    Categories of Data Collected
                </h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 mt-8">2.1 User & Account Data</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Full name</li>
                    <li>Email address</li>
                    <li>Organization or institution</li>
                    <li>Country of residence</li>
                    <li>Account type and role</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-4">2.2 Verification & Institutional Data</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Government or institutional identifiers</li>
                    <li>Professional licenses or accreditations</li>
                    <li>Authorization documents</li>
                    <li>Domain ownership or invitation-based verification</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-4">2.3 Document & Certification Data</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Uploaded documents and translations</li>
                    <li>Certification metadata</li>
                    <li>Audit, dispute, and governance records</li>
                    <li>Jurisdiction, purpose, and legal basis</li>
                </ul>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">3</span>
                    Purpose of Processing
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">Data is processed strictly for:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Platform service delivery</li>
                    <li>Cultural translation and review</li>
                    <li>Certification and verification</li>
                    <li>Governance, audit, and compliance</li>
                    <li>Legal and regulatory obligations</li>
                </ul>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl mb-8">
                    <p class="text-blue-900 font-semibold">
                        ⚠️ Cultural Translate does not sell, rent, or commercially exploit personal or institutional data.
                    </p>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">4</span>
                    AI & Human Review
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">Translations may be produced using:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li><strong>Artificial Intelligence systems</strong></li>
                    <li><strong>Human expert review</strong> (where applicable)</li>
                </ul>
                <p class="text-gray-700 leading-relaxed">
                    AI outputs are assistive and governed by internal review, certification, and decision-recording mechanisms.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">5</span>
                    Data Security Measures
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">We apply:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Encryption in transit and at rest</li>
                    <li>Role-based access control</li>
                    <li>Immutable audit and decision ledgers</li>
                    <li>Segregation of government and institutional data</li>
                    <li>Restricted-access environments for sensitive entities</li>
                </ul>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">6</span>
                    Data Retention
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">Data is retained:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>For the duration required to deliver services</li>
                    <li>To satisfy audit, legal, and compliance requirements</li>
                    <li>In accordance with jurisdiction-specific rules</li>
                </ul>
                <p class="text-gray-700 leading-relaxed">
                    Deletion requests are honored where legally permissible.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">7</span>
                    Data Subject Rights
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">Under GDPR, users have the right to:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Access their data</li>
                    <li>Rectify inaccurate data</li>
                    <li>Request erasure or restriction</li>
                    <li>Object to processing</li>
                    <li>Request data portability</li>
                </ul>
                <p class="text-gray-700 leading-relaxed">
                    Requests can be submitted via official platform channels.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">8</span>
                    International Transfers
                </h2>
                <p class="text-gray-700 leading-relaxed">
                    Where processing occurs outside the EU, appropriate safeguards (including Standard Contractual Clauses) are applied.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">9</span>
                    Contact
                </h2>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-6">
                    <p class="text-lg text-gray-800">
                        Privacy inquiries: <a href="mailto:privacy@culturaltranslate.com" class="text-indigo-600 hover:text-indigo-800 font-semibold">privacy@culturaltranslate.com</a>
                    </p>
                </div>

            </div>
        </div>

        <!-- Download PDF Button -->
        <div class="text-center mt-8">
            <a href="#" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Privacy Policy (PDF)
            </a>
        </div>
    </div>
</div>
@endsection
