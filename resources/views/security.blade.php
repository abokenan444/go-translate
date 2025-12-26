@extends('layouts.app')

@section('title', 'Security - CulturalTranslate')
@section('description', 'Learn about CulturalTranslate\'s robust security practices, data protection, and compliance certifications to ensure your data is safe and private.')

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl lg:text-6xl">
                    Security and Trust
                </h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                    Your data is our top priority. We are committed to maintaining the highest standards of security and compliance.
                </p>
            </div>

            <!-- Security Pillars Section -->
            <div class="mt-20">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">
                    Our Core Security Pillars
                </h2>
                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Pillar 1: Data Encryption -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <!-- Icon: Lock -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Data Encryption</h3>
                        <p class="mt-4 text-base text-gray-500 dark:text-gray-400">
                            All data is encrypted both in transit (TLS 1.2+) and at rest (AES-256) using industry-leading cryptographic standards.
                        </p>
                    </div>

                    <!-- Pillar 2: Access Control -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <!-- Icon: Fingerprint -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.549A16.41 16.41 0 0112 4c3.732 0 7.21 1.186 10.043 3.253-1.03 1.688-2.07 3.377-3.11 5.065M12 11a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Strict Access Control</h3>
                        <p class="mt-4 text-base text-gray-500 dark:text-gray-400">
                            We enforce the principle of least privilege, multi-factor authentication (MFA), and regular access reviews for all internal systems.
                        </p>
                    </div>

                    <!-- Pillar 3: Continuous Monitoring -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <!-- Icon: Eye -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Continuous Monitoring</h3>
                        <p class="mt-4 text-base text-gray-500 dark:text-gray-400">
                            Our infrastructure is monitored 24/7 for suspicious activity, vulnerabilities, and performance issues, with automated alerting.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Compliance and Certifications Section -->
            <div class="mt-20 pt-16 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">
                    Compliance and Certifications
                </h2>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Certification 1: ISO 27001 -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.618a2.125 2.125 0 013 3L12 21l-7.618-7.618a2.125 2.125 0 013-3L12 15l4.618-4.618z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">ISO 27001 Certified</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">
                                Demonstrates our commitment to a systematic approach to managing sensitive company and customer information.
                            </p>
                        </div>
                    </div>

                    <!-- Certification 2: GDPR Compliant -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">GDPR Compliant</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">
                                We fully comply with the General Data Protection Regulation, ensuring the privacy and protection of EU citizens' data.
                            </p>
                        </div>
                    </div>

                    <!-- Certification 3: SOC 2 Type II -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.618a2.125 2.125 0 013 3L12 21l-7.618-7.618a2.125 2.125 0 013-3L12 15l4.618-4.618z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">SOC 2 Type II</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">
                                Our systems and controls are audited annually by an independent third party against the AICPA Trust Services Criteria.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report a Vulnerability Section -->
            <div class="mt-20 pt-16 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-indigo-600 dark:bg-indigo-700 rounded-lg p-10 shadow-2xl text-center">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        Found a vulnerability?
                    </h2>
                    <p class="mt-4 text-xl text-indigo-200">
                        We appreciate the work of security researchers. Please report any potential issues through our responsible disclosure program.
                    </p>
                    <div class="mt-8">
                        <a href="/security/vulnerability-report" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition duration-150 ease-in-out">
                            Report a Vulnerability
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
