@extends('layouts.app')

@section('title', 'Certificate Verification - Cultural Translate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Certificate Verification</h1>
            <p class="text-2xl text-gray-600">Instantly verify the authenticity of CTS™ certified translations</p>
        </div>

        <!-- Verification Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12 max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Verify Your Certificate</h2>
            <p class="text-lg text-gray-700 mb-8 text-center">
                Enter the certificate ID or scan the QR code to verify your CTS™ certified translation
            </p>
            
            <form action="#" method="POST" class="space-y-6" onsubmit="alert('Certificate verification feature coming soon!'); return false;">
                @csrf
                
                <div>
                    <label for="certificate_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Certificate ID
                    </label>
                    <input 
                        type="text" 
                        id="certificate_id" 
                        name="certificate_id" 
                        placeholder="e.g., CTS-2024-ABC123456"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-green-600 text-white font-bold rounded-lg hover:from-blue-700 hover:to-green-700 transition text-lg"
                >
                    Verify Certificate
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600 mb-4">Or scan the QR code on your certificate</p>
                <div class="inline-block p-4 bg-gray-100 rounded-lg">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">How Certificate Verification Works</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Enter Certificate ID</h3>
                    <p class="text-gray-600">Type the unique certificate ID found on your CTS™ certified document</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Instant Verification</h3>
                    <p class="text-gray-600">Our system instantly checks the certificate against our secure database</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-purple-600">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">View Details</h3>
                    <p class="text-gray-600">See complete certificate details including translator, date, and authenticity status</p>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Verification Features</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="border-l-4 border-green-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">✓ Instant Verification</h3>
                    <p class="text-gray-700">Verify certificates in real-time, 24/7, from anywhere in the world</p>
                </div>

                <div class="border-l-4 border-blue-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">✓ Blockchain-Secured</h3>
                    <p class="text-gray-700">All certificates are secured on blockchain for tamper-proof verification</p>
                </div>

                <div class="border-l-4 border-purple-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">✓ QR Code Support</h3>
                    <p class="text-gray-700">Scan QR codes directly from physical or digital certificates</p>
                </div>

                <div class="border-l-4 border-orange-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">✓ Complete Details</h3>
                    <p class="text-gray-700">View translator credentials, certification date, and document metadata</p>
                </div>

                <div class="border-l-4 border-red-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">✓ Mobile-Friendly</h3>
                    <p class="text-gray-700">Verify certificates on any device - desktop, tablet, or smartphone</p>
                </div>

                <div class="border-l-4 border-pink-600 pl-6 py-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">✓ Audit Trail</h3>
                    <p class="text-gray-700">Complete verification history and audit trail for compliance</p>
                </div>
            </div>
        </div>

        <!-- Why Verify -->
        <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl shadow-xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Why Verify Certificates?</h2>
            <p class="text-xl mb-8">
                Certificate verification protects you from fraudulent translations and ensures the authenticity of your documents for official use.
            </p>
            <div class="grid md:grid-cols-3 gap-6 text-left">
                <div>
                    <h3 class="text-xl font-bold mb-2">🛡️ Prevent Fraud</h3>
                    <p>Detect fake or altered certificates instantly</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-2">✅ Official Acceptance</h3>
                    <p>Ensure your documents are accepted by authorities</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-2">⚡ Save Time</h3>
                    <p>Instant verification without manual checks</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
