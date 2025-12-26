@extends("layouts.app")

@section("title", "Legal Documents - Cultural Translate")

@section("content")
<div class="min-h-screen bg-gradient-to-br from-gray-100 via-white to-gray-200">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Legal Documents</h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Important legal information about our services, privacy policy, and terms of use.
                </p>
            </div>
        </div>
    </div>

    <!-- Legal Documents List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Terms of Service -->
            <a href="/terms-of-service" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow block">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Terms of Service</h3>
                <p class="text-gray-600 leading-relaxed">
                    Read the terms and conditions that govern your use of our services.
                </p>
            </a>

            <!-- Privacy Policy -->
            <a href="/privacy-policy" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow block">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Privacy Policy</h3>
                <p class="text-gray-600 leading-relaxed">
                    Learn how we collect, use, and protect your personal data.
                </p>
            </a>

            <!-- Cookie Policy -->
            <a href="/cookie-policy" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow block">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Cookie Policy</h3>
                <p class="text-gray-600 leading-relaxed">
                    Understand how we use cookies to improve your experience on our website.
                </p>
            </a>

            <!-- Affiliate Agreement -->
            <a href="#" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow block">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Affiliate Agreement</h3>
                <p class="text-gray-600 leading-relaxed">
                    Terms and conditions for our affiliate program partners.
                </p>
            </a>

            <!-- Partner Agreement -->
            <a href="#" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow block">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Partner Agreement</h3>
                <p class="text-gray-600 leading-relaxed">
                    Terms and conditions for our certified translation partners.
                </p>
            </a>

            <!-- Service Level Agreement (SLA) -->
            <a href="#" class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow block">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Service Level Agreement (SLA)</h3>
                <p class="text-gray-600 leading-relaxed">
                    Our commitment to service quality and uptime for enterprise customers.
                </p>
            </a>
        </div>
    </div>

</div>
@endsection
