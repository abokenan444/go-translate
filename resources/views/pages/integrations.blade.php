<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <title>Integrations - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .toast-enter {
            animation: slideIn 0.3s ease-out;
        }
        .toast-exit {
            animation: slideOut 0.3s ease-in;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    @include('components.navigation')

    <!-- Hero -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Integrations</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                Connect CulturalTranslate with your favorite tools and streamline your translation workflow
            </p>
        </div>
    </section>

    <!-- Integration Categories -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- E-commerce Platforms -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">E-commerce Platforms</h2>
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Shopify -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-shopify text-5xl" style="color: #96BF48"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Shopify</h3>
                        <p class="text-gray-600 mb-4">Translate your entire store including products, collections, and checkout pages.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Product translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Multi-store support</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Checkout localization</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('shopify')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Shopify
                        </button>
                    </div>

                    <!-- WooCommerce -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-wordpress text-5xl" style="color: #21759B"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">WooCommerce</h3>
                        <p class="text-gray-600 mb-4">WordPress plugin for automatic translation of WooCommerce stores.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Product translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Category sync</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Checkout pages</span>
                            </div>
                        </div>
                        <a href="/integrations/woocommerce" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition block text-center">
                            View Documentation
                        </a>
                    </div>

                    <!-- Magento -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-magento text-5xl" style="color: #EE672F"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Magento</h3>
                        <p class="text-gray-600 mb-4">Enterprise-grade translation for Magento stores with multi-store support.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Multi-store</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Product catalog</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>CMS pages</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('magento')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Magento
                        </button>
                    </div>

                    <!-- Amazon Marketplace -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-amazon text-5xl" style="color: #FF9900"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Amazon</h3>
                        <p class="text-gray-600 mb-4">Translate product listings for Amazon global marketplaces.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Product listings</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Global markets</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Bulk translation</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('amazon')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Amazon
                        </button>
                    </div>

                    <!-- eBay -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-shopping-cart text-5xl" style="color: #E53238"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">eBay</h3>
                        <p class="text-gray-600 mb-4">Translate eBay listings for international buyers.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Listing translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>International sites</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Auto-sync</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('ebay')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect eBay
                        </button>
                    </div>
                </div>
            </div>

            <!-- Social Media & Marketing -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Social Media & Marketing</h2>
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Facebook/Instagram -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-facebook text-5xl" style="color: #1877F2"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Facebook/Instagram</h3>
                        <p class="text-gray-600 mb-4">Translate posts, ads, and stories for global audiences.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Post translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Ad campaigns</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Stories & Reels</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('facebook')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Facebook
                        </button>
                    </div>

                    <!-- Twitter/X -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-twitter text-5xl" style="color: #1DA1F2"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Twitter / X</h3>
                        <p class="text-gray-600 mb-4">Auto-translate tweets and threads for global reach.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Tweet translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Thread support</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Auto-post</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('twitter')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Twitter
                        </button>
                    </div>

                    <!-- LinkedIn -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-linkedin text-5xl" style="color: #0A66C2"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">LinkedIn</h3>
                        <p class="text-gray-600 mb-4">Translate professional content for international networks.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Post translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Article localization</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Company pages</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('linkedin')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect LinkedIn
                        </button>
                    </div>

                    <!-- TikTok -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-tiktok text-5xl text-gray-900"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">TikTok</h3>
                        <p class="text-gray-600 mb-4">Translate captions and descriptions for viral content.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Caption translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Hashtag localization</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Multi-region</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('tiktok')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect TikTok
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messaging & Communication -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Messaging & Communication</h2>
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Slack -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-slack text-5xl" style="color: #4A154B"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Slack</h3>
                        <p class="text-gray-600 mb-4">Translate messages in real-time directly in your Slack channels.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Real-time translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Slash commands</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Bot integration</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('slack')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Slack
                        </button>
                    </div>

                    <!-- Microsoft Teams -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-microsoft text-5xl" style="color: #5059C9"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Microsoft Teams</h3>
                        <p class="text-gray-600 mb-4">Integrate translation capabilities into Teams chats and channels.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Chat translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Channel integration</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Bot commands</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('teams')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Teams
                        </button>
                    </div>

                    <!-- WhatsApp Business -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-whatsapp text-5xl" style="color: #25D366"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">WhatsApp Business</h3>
                        <p class="text-gray-600 mb-4">Auto-translate customer messages for global support.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Message translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Template messages</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Auto-reply</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('whatsapp')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect WhatsApp
                        </button>
                    </div>

                    <!-- Telegram -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-telegram text-5xl" style="color: #0088CC"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Telegram</h3>
                        <p class="text-gray-600 mb-4">Bot integration for instant message translation.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Bot commands</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Group translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Channel support</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('telegram')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Telegram
                        </button>
                    </div>

                    <!-- Discord -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-discord text-5xl" style="color: #5865F2"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Discord</h3>
                        <p class="text-gray-600 mb-4">Bot for translating messages in Discord servers.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Server bot</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Slash commands</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Channel translation</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('discord')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Discord
                        </button>
                    </div>

                    <!-- Zoom -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-video text-5xl text-blue-600"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Zoom</h3>
                        <p class="text-gray-600 mb-4">Real-time transcription and translation for Zoom meetings.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Live transcription</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Real-time translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Meeting summaries</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('zoom')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Zoom
                        </button>
                    </div>
                </div>
            </div>

            <!-- CMS & Content -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Content Management Systems</h2>
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- WordPress -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-wordpress text-5xl" style="color: #21759B"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">WordPress</h3>
                        <p class="text-gray-600 mb-4">Plugin for translating WordPress posts, pages, and custom post types.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Post translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Custom post types</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>REST API integration</span>
                            </div>
                        </div>
                        <a href="/integrations/wordpress" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition block text-center">
                            View Documentation
                        </a>
                    </div>

                    <!-- Contentful -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-cube text-5xl text-blue-600"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Contentful</h3>
                        <p class="text-gray-600 mb-4">Headless CMS integration for automatic content translation.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Content models</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>API integration</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Webhook support</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('contentful')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Contentful
                        </button>
                    </div>

                    <!-- Strapi -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-file-alt text-5xl text-purple-600"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Strapi</h3>
                        <p class="text-gray-600 mb-4">Open-source headless CMS with API-first translation.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Content types</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Plugin integration</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>i18n support</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('strapi')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Strapi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Development Tools -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Development & API</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- GitHub -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-github text-5xl text-gray-900"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">GitHub</h3>
                        <p class="text-gray-600 mb-4">Automate translation of documentation, README files, and issue comments.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>GitHub Actions</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>PR translations</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Docs automation</span>
                            </div>
                        </div>
                        <a href="/integrations/github" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition block text-center">
                            View Documentation
                        </a>
                    </div>

                    <!-- GitLab -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-gitlab text-5xl" style="color: #FC6D26"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">GitLab</h3>
                        <p class="text-gray-600 mb-4">Integrate translation into your GitLab CI/CD pipeline.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>CI/CD integration</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Pipeline automation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Merge request hooks</span>
                            </div>
                        </div>
                        <button onclick="connectIntegration('gitlab')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect GitLab
                        </button>
                    </div>

                    <!-- REST API -->
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-code text-5xl text-indigo-600"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">REST API</h3>
                        <p class="text-gray-600 mb-4">Full-featured REST API for custom integrations.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>RESTful endpoints</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Webhooks</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>API documentation</span>
                            </div>
                        </div>
                        <a href="/api/documentation" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition block text-center">
                            View API Docs
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @include('components.footer')

    <script>
        function connectIntegration(platform) {
            // Show loading toast
            showToast(`Connecting to ${platform}...`, 'info');
            
            // Make API call to connect integration
            fetch(`/api/integrations/connect/${platform}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect_url) {
                        // Redirect to OAuth page
                        window.location.href = data.redirect_url;
                    } else {
                        showToast(`Successfully connected to ${platform}!`, 'success');
                    }
                } else {
                    showToast(data.message || 'Failed to connect', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };
            
            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg toast-enter`;
            toast.textContent = message;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>
