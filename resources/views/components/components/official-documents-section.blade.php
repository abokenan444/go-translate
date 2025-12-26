{{-- Official Documents Section for Homepage --}}
<section class="official-documents-section py-20 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4">
        {{-- Title --}}
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mb-4">
                New Service
            </span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Certified Document Translation
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Get your official documents translated and certified with QR verification
            </p>
        </div>

        {{-- Features --}}
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            {{-- Feature 1 --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    Officially Certified
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    All translations come with official certification and digital seal
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    Secure & Protected
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Bank-level encryption and secure document storage
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    QR Verification
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Instant verification with QR code scanning
                </p>
            </div>
        </div>

        {{-- Supported Documents --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                Supported Document Types
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-passport text-blue-600 text-xl"></i>
                    <span class="text-gray-700 font-medium">Passport</span>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-id-card text-blue-600 text-xl"></i>
                    <span class="text-gray-700 font-medium">ID Card</span>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                    <span class="text-gray-700 font-medium">Diploma</span>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                    <span class="text-gray-700 font-medium">Contract</span>
                </div>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="text-center">
            @auth
                <a href="{{ route('official-documents.upload') }}" 
                   class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 mr-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Document Now
                </a>
                <a href="{{ route('official-documents.my-documents') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-lg transition-all duration-300">
                    My Documents
                </a>
            @else
                <a href="{{ route('official-documents.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 mr-4">
                    Learn More
                </a>
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-lg transition-all duration-300">
                    Get Started
                </a>
            @endauth
        </div>
    </div>
</section>
