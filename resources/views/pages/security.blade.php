@extends('layouts.app')

@section('title', 'Security - CulturalTranslate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">Security at CulturalTranslate</h1>
                <p class="text-xl opacity-90">Your data security and privacy are our top priorities</p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <!-- Security Overview -->
        <div class="max-w-5xl mx-auto mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold mb-6">Our Commitment to Security</h2>
                <p class="text-gray-700 text-lg mb-4">
                    At CulturalTranslate, we implement industry-leading security measures to protect your data. 
                    We follow best practices and comply with international security standards to ensure your 
                    translations and personal information remain safe and confidential.
                </p>
            </div>
        </div>

        <!-- Security Features Grid -->
        <div class="max-w-5xl mx-auto mb-16">
            <h2 class="text-4xl font-bold mb-8 text-center">Security Features</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">End-to-End Encryption</h3>
                    <p class="text-gray-600">All data transmitted between your device and our servers is encrypted using TLS 1.3 with 256-bit encryption.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">SOC 2 Compliance</h3>
                    <p class="text-gray-600">We are SOC 2 Type II certified, demonstrating our commitment to security, availability, and confidentiality.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Access Control</h3>
                    <p class="text-gray-600">Multi-factor authentication (MFA) and role-based access control (RBAC) to protect your account.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Regular Security Audits</h3>
                    <p class="text-gray-600">Third-party security audits and penetration testing conducted quarterly to identify and fix vulnerabilities.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Data Backup</h3>
                    <p class="text-gray-600">Automated daily backups with 30-day retention and geo-redundant storage across multiple regions.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">GDPR Compliant</h3>
                    <p class="text-gray-600">Full compliance with GDPR and other international data protection regulations.</p>
                </div>
            </div>
        </div>

        <!-- Infrastructure Security -->
        <div class="max-w-5xl mx-auto mb-16">
            <h2 class="text-4xl font-bold mb-8">Infrastructure Security</h2>
            
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">✓</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Cloud Infrastructure</h4>
                            <p class="text-gray-600">Hosted on enterprise-grade cloud infrastructure with 99.99% uptime SLA</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">✓</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">DDoS Protection</h4>
                            <p class="text-gray-600">Advanced DDoS mitigation to ensure service availability</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">✓</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Firewall Protection</h4>
                            <p class="text-gray-600">Multi-layer firewall protection with intrusion detection systems</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">✓</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">24/7 Monitoring</h4>
                            <p class="text-gray-600">Real-time security monitoring and incident response team</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">✓</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Secure Development</h4>
                            <p class="text-gray-600">Secure SDLC practices with code review and vulnerability scanning</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certifications -->
        <div class="max-w-5xl mx-auto mb-16">
            <h2 class="text-4xl font-bold mb-8 text-center">Certifications & Compliance</h2>
            
            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="text-4xl mb-2">🔒</div>
                    <h4 class="font-bold">SOC 2 Type II</h4>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="text-4xl mb-2">🇪🇺</div>
                    <h4 class="font-bold">GDPR</h4>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="text-4xl mb-2">🔐</div>
                    <h4 class="font-bold">ISO 27001</h4>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="text-4xl mb-2">✓</div>
                    <h4 class="font-bold">HIPAA Ready</h4>
                </div>
            </div>
        </div>

        <!-- Responsible Disclosure -->
        <div class="max-w-5xl mx-auto">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-12 text-white">
                <h2 class="text-3xl font-bold mb-4">Responsible Disclosure</h2>
                <p class="text-lg mb-6 opacity-90">
                    If you discover a security vulnerability, please report it to us responsibly. 
                    We appreciate the security research community's efforts to keep our platform secure.
                </p>
                <div class="bg-white/10 rounded-lg p-6">
                    <p class="mb-2"><strong>Report security issues to:</strong></p>
                    <p class="text-xl">security@culturaltranslate.com</p>
                    <p class="mt-4 text-sm opacity-75">We commit to responding within 24 hours and providing updates every 48 hours.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
