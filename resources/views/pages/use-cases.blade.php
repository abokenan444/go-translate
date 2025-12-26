@extends("layouts.app")
@section("title", "Translation Use Cases - E-commerce, Healthcare, Education & More | CulturalTranslate")

@section("content")
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Real-World Use Cases</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                Discover how businesses across industries use CulturalTranslate to break language barriers and expand globally
            </p>
        </div>
    </section>

    <!-- Use Cases Grid -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- E-commerce -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-block px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold mb-4">
                            E-commerce
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Global Online Stores</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Translate product descriptions, reviews, and checkout pages to sell worldwide. Maintain your brand voice across all languages while adapting to local cultural preferences.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Product Catalog Translation</div>
                                    <div class="text-gray-600">Translate thousands of products in minutes</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Customer Reviews</div>
                                    <div class="text-gray-600">Show reviews in customer's language</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">SEO Optimization</div>
                                    <div class="text-gray-600">Culturally-adapted content for better rankings</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="/pricing" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition inline-block">
                                Start Free Trial
                            </a>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Fashion Retailer</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Expanding to 12 new markets with 50,000 products</p>
                            <p><strong class="text-gray-900">Solution:</strong> Automated translation with cultural adaptation</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>300% increase in international sales</li>
                                <li>95% reduction in translation time</li>
                                <li>4.8/5 customer satisfaction rating</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketing & Advertising -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1 bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Global Marketing Agency</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Launch campaigns in 20 countries simultaneously</p>
                            <p><strong class="text-gray-900">Solution:</strong> Real-time collaboration with cultural experts</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>70% faster campaign launches</li>
                                <li>2x higher engagement rates</li>
                                <li>$500K saved on translation costs</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="inline-block px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold mb-4">
                            Marketing & Advertising
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Global Campaigns</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Launch marketing campaigns across multiple markets with culturally-adapted messaging that resonates with local audiences while maintaining brand consistency.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Ad Copy Translation</div>
                                    <div class="text-gray-600">Culturally-adapted ad content</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Social Media Content</div>
                                    <div class="text-gray-600">Localized posts for each market</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Video Subtitles</div>
                                    <div class="text-gray-600">Automatic subtitle generation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SaaS & Technology -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold mb-4">
                            SaaS & Technology
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Software Localization</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Localize your software interface, documentation, and support content to provide a native experience for users worldwide. Our API allows for continuous localization.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">UI/UX Localization</div>
                                    <div class="text-gray-600">Adapt your interface for different cultures</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Knowledge Base Translation</div>
                                    <div class="text-gray-600">Translate help articles and FAQs</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Continuous Integration</div>
                                    <div class="text-gray-600">Automate translation with our API</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">SaaS Platform</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Enter 8 new international markets in one quarter</p>
                            <p><strong class="text-gray-900">Solution:</strong> API integration for continuous localization</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>99% translation accuracy</li>
                                <li>Reduced time-to-market by 80%</li>
                                <li>40% increase in user adoption</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legal -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1 bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">International Law Firm</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Translate sensitive legal documents with 100% accuracy</p>
                            <p><strong class="text-gray-900">Solution:</strong> Secure, on-premise deployment with human review</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>Maintained full data privacy</li>
                                <li>99.9% accuracy after human review</li>
                                <li>Reduced legal risks in contracts</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="inline-block px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold mb-4">
                            Legal
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Legal Document Translation</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Translate contracts, patents, and other legal documents with high accuracy and cultural nuance. Ensure your legal documents are understood and enforceable in any jurisdiction.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Contract Translation</div>
                                    <div class="text-gray-600">Accurate translation of legal agreements</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Patent Filings</div>
                                    <div class="text-gray-600">Translate patents for international filings</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Compliance Documents</div>
                                    <div class="text-gray-600">Translate regulatory and compliance docs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
