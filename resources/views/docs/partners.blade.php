<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners Documentation - Cultural Translation Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Cultural Translation Platform</h1>
                    <p class="text-sm text-gray-600">Partners Documentation</p>
                </div>
                <a href="{{ url('/') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="{{ url('/api/v1') }}" class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="flex items-center mb-4">
                    <i class="fas fa-code text-3xl text-blue-600 mr-4"></i>
                    <h3 class="text-xl font-semibold">API Documentation</h3>
                </div>
                <p class="text-gray-600">Complete API reference and integration guides</p>
            </a>

            <a href="{{ url('/partner/dashboard') }}" class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="flex items-center mb-4">
                    <i class="fas fa-tachometer-alt text-3xl text-green-600 mr-4"></i>
                    <h3 class="text-xl font-semibold">Partner Dashboard</h3>
                </div>
                <p class="text-gray-600">Access your partner dashboard and analytics</p>
            </a>

            <a href="{{ url('/partner/apply') }}" class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="flex items-center mb-4">
                    <i class="fas fa-handshake text-3xl text-purple-600 mr-4"></i>
                    <h3 class="text-xl font-semibold">Become a Partner</h3>
                </div>
                <p class="text-gray-600">Apply for partnership program</p>
            </a>
        </div>

        <!-- Documentation Content -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Partnership Program Overview</h2>

            <!-- Partner Types -->
            <section class="mb-10">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Partner Types</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Law Firm</h4>
                        <p class="text-gray-600 mb-3">Legal document translation services</p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>20% commission rate</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Certified legal translations</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Priority support</li>
                        </ul>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Translation Agency</h4>
                        <p class="text-gray-600 mb-3">Professional translation services</p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>25% commission rate</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Bulk translation support</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Client management tools</li>
                        </ul>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">University</h4>
                        <p class="text-gray-600 mb-3">Academic translation services</p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>15% commission rate</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Student & faculty access</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Research document support</li>
                        </ul>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Corporate</h4>
                        <p class="text-gray-600 mb-3">Enterprise translation solutions</p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>30% commission rate</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Team management</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Custom integrations</li>
                        </ul>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Certified Translator</h4>
                        <p class="text-gray-600 mb-3">Individual certified translators</p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>18% commission rate</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Certification management</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Personal branding</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Subscription Tiers -->
            <section class="mb-10">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Subscription Tiers</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($plans as $plan)
                    <div class="border-2 border-gray-300 rounded-lg p-6 hover:border-blue-500 transition">
                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h4>
                        <p class="text-3xl font-bold text-blue-600 mb-4">
                            @if($plan->price > 0)
                                ${{ number_format($plan->price, 0) }}<span class="text-lg text-gray-600">/{{ $plan->billing_period }}</span>
                            @else
                                <span class="text-2xl">Contact Us</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-600 mb-4">{{ $plan->description }}</p>
                        <ul class="space-y-3 text-sm text-gray-700 mb-6">
                            @foreach($plan->features as $feature)
                            <li><i class="fas fa-check text-green-600 mr-2"></i>{{ $feature }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ url('/partners/register') }}" class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                            Choose {{ $plan->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </section>
            <!-- Getting Started -->
            <section class="mb-10">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Getting Started</h3>
                <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded">
                    <ol class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                            <div>
                                <h4 class="font-semibold mb-1">Apply for Partnership</h4>
                                <p class="text-sm">Submit your application through our partner portal</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                            <div>
                                <h4 class="font-semibold mb-1">Get Approved</h4>
                                <p class="text-sm">Our team will review and approve your application</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                            <div>
                                <h4 class="font-semibold mb-1">Choose Subscription</h4>
                                <p class="text-sm">Select the plan that fits your needs</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                            <div>
                                <h4 class="font-semibold mb-1">Get API Keys</h4>
                                <p class="text-sm">Generate your API keys and start integrating</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">5</span>
                            <div>
                                <h4 class="font-semibold mb-1">Start Earning</h4>
                                <p class="text-sm">Begin using our services and earning commissions</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>

            <!-- Resources -->
            <section>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Resources</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ url('/api/v1') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-book text-2xl text-blue-600 mr-4"></i>
                        <div>
                            <h4 class="font-semibold">API Documentation</h4>
                            <p class="text-sm text-gray-600">Complete API reference</p>
                        </div>
                    </a>

                    <a href="{{ url('/partner-api-swagger.yaml') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-file-code text-2xl text-green-600 mr-4"></i>
                        <div>
                            <h4 class="font-semibold">OpenAPI Spec</h4>
                            <p class="text-sm text-gray-600">Swagger/OpenAPI YAML file</p>
                        </div>
                    </a>

                    <a href="{{ url('/partner/dashboard') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-chart-line text-2xl text-purple-600 mr-4"></i>
                        <div>
                            <h4 class="font-semibold">Analytics Dashboard</h4>
                            <p class="text-sm text-gray-600">Track your performance</p>
                        </div>
                    </a>

                    <a href="mailto:partners@culturaltranslate.com" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-envelope text-2xl text-red-600 mr-4"></i>
                        <div>
                            <h4 class="font-semibold">Contact Support</h4>
                            <p class="text-sm text-gray-600">Get help from our team</p>
                        </div>
                    </a>
                </div>
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 Cultural Translation Platform. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
