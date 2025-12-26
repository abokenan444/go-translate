<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GDPR Compliance - Cultural Translate</title>
    <meta name="description" content="Cultural Translate's commitment to GDPR compliance and data protection">
    <meta name="keywords" content="GDPR, data protection, privacy, compliance, cultural translate">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #0D0D0D 0%, #1a1a2e 100%);
            min-height: 100vh;
        }
        
        .content-wrapper {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            background: rgba(108, 99, 255, 0.2);
            color: #a5b4fc;
            border: 1px solid rgba(108, 99, 255, 0.3);
        }
        
        .contact-card {
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.1) 0%, rgba(90, 82, 213, 0.05) 100%);
            border: 1px solid rgba(108, 99, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/50 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white">
                        Cultural Translate
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition">Home</a>
                    <a href="{{ route('features') }}" class="text-gray-300 hover:text-white transition">Features</a>
                    <a href="{{ url('/pricing-plans') }}" class="text-gray-300 hover:text-white transition">Pricing</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="badge">GDPR Compliant</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    GDPR Compliance
                </h1>
                <p class="text-xl text-gray-300 mb-2">
                    Cultural Translate's commitment to GDPR compliance and data protection
                </p>
                <p class="text-sm text-gray-400">
                    Last Updated: November 26, 2025
                </p>
            </div>

            <!-- Content Wrapper -->
            <div class="content-wrapper rounded-2xl p-8 md:p-12">
                <!-- Introduction -->
                <div class="mb-12">
                    <p class="text-lg text-gray-300 leading-relaxed">
                        Cultural Translate is committed to protecting your personal data and complying with the General Data Protection Regulation (GDPR). This page outlines our GDPR compliance measures and your rights under GDPR.
                    </p>
                </div>

                <!-- Section 1: Our Commitment -->
                <section class="mb-12">
                    <h2 class="text-3xl font-bold text-white mb-6">1. Our Commitment to GDPR</h2>
                    <p class="text-gray-300 mb-6">We are committed to:</p>
                    
                    <div class="grid gap-4">
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Transparency
                            </h3>
                            <p class="text-gray-400">Clear communication about data processing</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Lawfulness
                            </h3>
                            <p class="text-gray-400">Processing data only on legal grounds</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Purpose Limitation
                            </h3>
                            <p class="text-gray-400">Using data only for specified purposes</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Data Minimization
                            </h3>
                            <p class="text-gray-400">Collecting only necessary data</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Accuracy
                            </h3>
                            <p class="text-gray-400">Keeping data accurate and up-to-date</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Storage Limitation
                            </h3>
                            <p class="text-gray-400">Retaining data only as long as necessary</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Integrity and Confidentiality
                            </h3>
                            <p class="text-gray-400">Protecting data with appropriate security</p>
                        </div>
                        
                        <div class="section-card">
                            <h3 class="text-xl font-semibold text-white mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Accountability
                            </h3>
                            <p class="text-gray-400">Demonstrating compliance with GDPR</p>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Your Rights -->
                <section class="mb-12">
                    <h2 class="text-3xl font-bold text-white mb-6">2. Your GDPR Rights</h2>
                    <p class="text-gray-300 mb-6">Under GDPR, you have the following rights:</p>
                    
                    <!-- Right to Access -->
                    <div class="section-card mb-4">
                        <h3 class="text-2xl font-semibold text-white mb-3">
                            2.1 Right to Access (Article 15)
                        </h3>
                        <p class="text-gray-300 mb-3">You have the right to:</p>
                        <ul class="list-disc list-inside text-gray-400 space-y-2 mb-4">
                            <li>Confirm whether we process your personal data</li>
                            <li>Access your personal data</li>
                            <li>Receive information about processing activities</li>
                        </ul>
                        <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-lg p-4">
                            <p class="text-sm text-gray-300">
                                <strong class="text-white">How to exercise:</strong> Email 
                                <a href="mailto:privacy@culturaltranslate.com" class="text-indigo-400 hover:text-indigo-300 underline">privacy@culturaltranslate.com</a> 
                                or use your account dashboard.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Right to Rectification -->
                    <div class="section-card mb-4">
                        <h3 class="text-2xl font-semibold text-white mb-3">
                            2.2 Right to Rectification (Article 16)
                        </h3>
                        <p class="text-gray-300 mb-3">You have the right to:</p>
                        <ul class="list-disc list-inside text-gray-400 space-y-2 mb-4">
                            <li>Correct inaccurate personal data</li>
                            <li>Complete incomplete personal data</li>
                        </ul>
                        <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-lg p-4">
                            <p class="text-sm text-gray-300">
                                <strong class="text-white">How to exercise:</strong> Update your account settings or contact us.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Right to Erasure -->
                    <div class="section-card mb-4">
                        <h3 class="text-2xl font-semibold text-white mb-3">
                            2.3 Right to Erasure (Article 17)
                        </h3>
                        <p class="text-gray-300 mb-3">You have the right to request deletion of your personal data when:</p>
                        <ul class="list-disc list-inside text-gray-400 space-y-2 mb-4">
                            <li>Data is no longer necessary for the purpose collected</li>
                            <li>You withdraw consent (where consent was the legal basis)</li>
                            <li>You object to processing and there are no overriding legitimate grounds</li>
                        </ul>
                        <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-lg p-4">
                            <p class="text-sm text-gray-300">
                                <strong class="text-white">How to exercise:</strong> Delete your account or email 
                                <a href="mailto:privacy@culturaltranslate.com" class="text-indigo-400 hover:text-indigo-300 underline">privacy@culturaltranslate.com</a>.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Section 3: DPO -->
                <section class="mb-12">
                    <h2 class="text-3xl font-bold text-white mb-6">3. Data Protection Officer (DPO)</h2>
                    <p class="text-gray-300 mb-6">
                        We have appointed a Data Protection Officer to oversee GDPR compliance:
                    </p>
                    
                    <div class="contact-card mb-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-400">Email</p>
                                <a href="mailto:dpo@culturaltranslate.com" class="text-lg text-indigo-400 hover:text-indigo-300 font-semibold">
                                    dpo@culturaltranslate.com
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-card">
                        <h4 class="text-lg font-semibold text-white mb-3">Responsibilities:</h4>
                        <ul class="list-disc list-inside text-gray-400 space-y-2">
                            <li>Monitor GDPR compliance</li>
                            <li>Advise on data protection obligations</li>
                            <li>Cooperate with supervisory authorities</li>
                            <li>Act as contact point for data subjects</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 4: Contact -->
                <section class="mb-8">
                    <h2 class="text-3xl font-bold text-white mb-6">4. Contact Us</h2>
                    <p class="text-gray-300 mb-6">
                        For GDPR-related questions or to exercise your rights:
                    </p>
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="contact-card">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-indigo-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Data Protection Officer</p>
                                    <a href="mailto:dpo@culturaltranslate.com" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                                        dpo@culturaltranslate.com
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-indigo-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Privacy Team</p>
                                    <a href="mailto:privacy@culturaltranslate.com" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                                        privacy@culturaltranslate.com
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-400 mb-1">Response Time</p>
                                <p class="text-sm text-gray-300">We will respond to your request within 30 days.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black/50 backdrop-blur-md border-t border-white/10 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    © 2025 Cultural Translate. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition">Privacy Policy</a>
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition">Terms of Service</a>
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
