<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Certificate - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-certificate text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">CulturalTranslate</span>
                </a>
                <a href="/" class="text-gray-600 hover:text-indigo-600 transition">
                    <i class="fas fa-home mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-16">
        <div class="max-w-2xl mx-auto">
            
            <!-- Page Title -->
            <div class="text-center mb-12">
                <div class="inline-block p-4 bg-indigo-100 rounded-full mb-4">
                    <i class="fas fa-shield-check text-4xl text-indigo-600"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Verify Translation Certificate</h1>
                <p class="text-gray-600 text-lg">Enter your certificate ID to verify authenticity</p>
            </div>

            <!-- Verification Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <form action="{{ route('verify.search') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="certificate_id" class="block text-gray-700 font-semibold mb-3">
                            Certificate ID
                        </label>
                        <input 
                            type="text" 
                            name="certificate_id" 
                            id="certificate_id"
                            placeholder="e.g., CT-2025-ABC12345"
                            class="w-full px-4 py-4 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition text-lg font-mono"
                            required
                        >
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Enter the certificate ID found on your translation document
                        </p>
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-indigo-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:bg-indigo-700 transition shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Verify Certificate
                    </button>
                </form>
            </div>

            <!-- How It Works -->
            <div class="bg-white rounded-xl p-6 mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-question-circle text-indigo-600 mr-2"></i>
                    How Verification Works
                </h3>
                <div class="space-y-3 text-gray-600">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="text-indigo-600 font-bold text-sm">1</span>
                        </div>
                        <p>Enter the certificate ID from your translated document</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="text-indigo-600 font-bold text-sm">2</span>
                        </div>
                        <p>Our system checks the certificate against our secure database</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="text-indigo-600 font-bold text-sm">3</span>
                        </div>
                        <p>View certificate details, status, and verification history</p>
                    </div>
                </div>
            </div>

            <!-- QR Code Scanner -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border-2 border-indigo-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-qrcode text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 mb-1">Have a QR Code?</h4>
                        <p class="text-gray-600 text-sm">Simply scan the QR code on your certificate for instant verification</p>
                    </div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-12 grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <i class="fas fa-lock text-3xl text-indigo-600 mb-2"></i>
                    <div class="text-xs font-semibold text-gray-600">Secure</div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <i class="fas fa-database text-3xl text-indigo-600 mb-2"></i>
                    <div class="text-xs font-semibold text-gray-600">Verified Database</div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <i class="fas fa-clock text-3xl text-indigo-600 mb-2"></i>
                    <div class="text-xs font-semibold text-gray-600">Real-time</div>
                </div>
            </div>

            <!-- Help -->
            <div class="mt-8 text-center text-gray-600">
                <p class="mb-2">Need assistance?</p>
                <a href="mailto:support@culturaltranslate.com" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                    <i class="fas fa-envelope mr-1"></i>
                    support@culturaltranslate.com
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t py-8">
        <div class="container mx-auto px-6 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} CulturalTranslate. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
