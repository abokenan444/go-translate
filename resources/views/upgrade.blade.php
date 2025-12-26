<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Account - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-rocket text-blue-600"></i> Upgrade Your Account
            </h1>
            <p class="text-gray-600">Choose the account type that fits your needs</p>
            <p class="text-sm text-gray-500 mt-2">Current: <span class="font-bold text-blue-600">{{ ucfirst(auth()->user()->account_type) }}</span></p>
        </div>

        <!-- Account Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            
            <!-- Affiliate -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all border-2 border-transparent hover:border-purple-500">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Affiliate</h3>
                    <p class="text-sm text-gray-500 mt-2">Earn commissions</p>
                </div>
                
                <ul class="space-y-2 mb-6">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Referral links</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Commission tracking</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Marketing materials</span>
                    </li>
                </ul>
                
                <form action="/account/upgrade-account" method="POST">
                    @csrf
                    <input type="hidden" name="account_type" value="affiliate">
                    <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                        Upgrade to Affiliate
                    </button>
                </form>
            </div>

            <!-- Partner -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all border-2 border-transparent hover:border-blue-500">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Partner</h3>
                    <p class="text-sm text-gray-500 mt-2">API integration</p>
                </div>
                
                <ul class="space-y-2 mb-6">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">API access</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Revenue sharing</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Partner levels</span>
                    </li>
                </ul>
                
                <form action="/account/upgrade-account" method="POST">
                    @csrf
                    <input type="hidden" name="account_type" value="partner">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Upgrade to Partner
                    </button>
                </form>
            </div>

            <!-- Government -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all border-2 border-transparent hover:border-green-500">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-landmark text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Government</h3>
                    <p class="text-sm text-gray-500 mt-2">Official documents</p>
                </div>
                
                <ul class="space-y-2 mb-6">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Certified translations</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Official seals</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Priority support</span>
                    </li>
                </ul>
                
                <form action="/account/upgrade-account" method="POST">
                    @csrf
                    <input type="hidden" name="account_type" value="government">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        Upgrade to Government
                    </button>
                </form>
            </div>

            <!-- Translator -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all border-2 border-transparent hover:border-orange-500">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-language text-3xl text-orange-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Translator</h3>
                    <p class="text-sm text-gray-500 mt-2">Work as translator</p>
                </div>
                
                <ul class="space-y-2 mb-6">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Browse jobs</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Earn money</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm">Rating system</span>
                    </li>
                </ul>
                
                <form action="/account/upgrade-account" method="POST">
                    @csrf
                    <input type="hidden" name="account_type" value="translator">
                    <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                        Upgrade to Translator
                    </button>
                </form>
            </div>

        </div>

        <!-- Back to Dashboard -->
        <div class="text-center mt-12">
            <a href="/dashboard" class="inline-block bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
