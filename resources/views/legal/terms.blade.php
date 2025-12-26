@extends('layouts.app')

@section('title', 'Terms of Service - Cultural Translate Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Terms of Service</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Legal Agreement • Enterprise-Grade
                </p>
                <p class="text-sm text-indigo-200 mt-4">
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
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">1</span>
                    Introduction
                </h2>
                <p class="text-gray-700 leading-relaxed mb-8">
                    These Terms of Service ("Terms") govern access to and use of the <strong>Cultural Translate Platform</strong> ("Platform", "we", "us", "our"), including all services, applications, APIs, verification systems, certification workflows, and governance frameworks provided through the Platform.
                </p>
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 rounded-r-xl mb-8">
                    <p class="text-indigo-900 font-semibold">
                        ⚖️ By creating an account or using the Platform, you agree to be legally bound by these Terms.
                    </p>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">2</span>
                    Account Types & Eligibility
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    The Platform provides multiple account types, each subject to specific eligibility and verification requirements:
                </p>
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-2">✓ Customer Account</h4>
                        <p class="text-sm text-gray-600">Open registration</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-2">✓ Affiliate Account</h4>
                        <p class="text-sm text-gray-600">Commission-based</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-300">
                        <h4 class="font-semibold text-gray-900 mb-2">🔐 Translator Account</h4>
                        <p class="text-sm text-gray-600">Verification required</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-300">
                        <h4 class="font-semibold text-gray-900 mb-2">🔐 Partner Account</h4>
                        <p class="text-sm text-gray-600">Verification required</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-300">
                        <h4 class="font-semibold text-gray-900 mb-2">🏛️ Government Account</h4>
                        <p class="text-sm text-gray-600">Strict verification required</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-300">
                        <h4 class="font-semibold text-gray-900 mb-2">🎓 University Account</h4>
                        <p class="text-sm text-gray-600">Verification required</p>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    We reserve the right to approve, suspend, or revoke any account at our sole discretion, particularly for verified or institutional accounts.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">3</span>
                    Verification & Approval
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Certain account types require identity, credential, and/or institutional verification.
                </p>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Verification may include, but is not limited to:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Official identification</li>
                    <li>Professional licenses</li>
                    <li>Government or institutional authorization</li>
                    <li>Supporting legal documentation</li>
                </ul>
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-xl mb-8">
                    <p class="text-red-900 font-semibold">
                        ⚠️ Submission of false, misleading, or incomplete information may result in immediate suspension or termination.
                    </p>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">4</span>
                    Nature of Services
                </h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 mt-8">4.1 AI & Human Review</h3>
                <p class="text-gray-700 leading-relaxed mb-4">The Platform provides:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>AI-assisted cultural translation</li>
                    <li>Optional human expert review</li>
                    <li>Certification under the CTS™ Governance Framework, where applicable</li>
                </ul>
                <p class="text-gray-700 leading-relaxed mb-6">
                    AI outputs are assistive in nature. Human review availability depends on account type and service level.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-4">4.2 Certification & Verification</h3>
                <p class="text-gray-700 leading-relaxed mb-4">Certificates issued through the Platform:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Reflect compliance with internal governance processes</li>
                    <li>Are recorded in an append-only decision ledger</li>
                    <li>May be validated publicly via the verification page</li>
                </ul>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-r-xl mb-8">
                    <p class="text-yellow-900">
                        Certificates do not constitute notarization, legal advice, or sovereign governmental endorsement, unless explicitly stated by a competent authority.
                    </p>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">5</span>
                    Governance, Suspension & Revocation
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">The Platform maintains authority to:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Freeze certificates under review</li>
                    <li>Revoke certificates for compliance, legal, or integrity reasons</li>
                    <li>Restore certificates upon resolution</li>
                </ul>
                <p class="text-gray-700 leading-relaxed">
                    All such actions are logged and auditable.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">6</span>
                    Government & Institutional Use
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">Government and institutional services operate under:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Restricted subdomains</li>
                    <li>Enhanced access controls</li>
                    <li>Jurisdiction-specific governance</li>
                </ul>
                <p class="text-gray-700 leading-relaxed">
                    Use by governmental entities may be subject to separate contractual agreements and Service Level Agreements (SLAs).
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">7</span>
                    Fees & Payments
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">Fees vary by account type, service scope, and jurisdiction.</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                    <li>Subscription fees are billed in advance</li>
                    <li>Transactional fees apply to certified documents and reviews</li>
                    <li>Government and enterprise pricing may be contract-based</li>
                </ul>
                <p class="text-gray-700 leading-relaxed">
                    All prices are exclusive of applicable taxes unless stated otherwise.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">8</span>
                    Intellectual Property
                </h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    All Platform software, standards, frameworks, trademarks, and content are the exclusive property of Cultural Translate Platform.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Users retain ownership of submitted content but grant the Platform a limited, non-exclusive license to process it for service delivery.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">9</span>
                    Limitation of Liability
                </h2>
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-xl mb-8">
                    <p class="text-gray-700 leading-relaxed mb-3">
                        To the maximum extent permitted by law, the Platform shall not be liable for:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Indirect or consequential damages</li>
                        <li>Decisions made by third parties relying on translations or certificates</li>
                        <li>Jurisdictional acceptance or rejection of documents</li>
                    </ul>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">10</span>
                    Governing Law
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    These Terms are governed by the <strong>laws of the Netherlands</strong>, with applicable European Union regulations.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Any disputes shall be subject to the exclusive jurisdiction of competent courts within the EU.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">11</span>
                    Amendments
                </h2>
                <p class="text-gray-700 leading-relaxed">
                    We may update these Terms from time to time. Continued use of the Platform constitutes acceptance of updated Terms.
                </p>

            </div>
        </div>

        <!-- Download PDF Button -->
        <div class="text-center mt-8">
            <a href="#" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Terms of Service (PDF)
            </a>
        </div>
    </div>
</div>
@endsection
