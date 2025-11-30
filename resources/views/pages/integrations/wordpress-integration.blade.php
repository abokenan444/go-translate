<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress Integration - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
</head>
<body class="bg-gray-50">

    @include('components.navigation')

    <!-- Hero -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4 mb-6">
                <i class="fab fa-wordpress text-6xl"></i>
                <h1 class="text-5xl font-bold">WordPress Integration</h1>
            </div>
            <p class="text-xl text-blue-100 max-w-3xl">
                Translate your WordPress posts, pages, and custom post types with cultural context preservation
            </p>
        </div>
    </section>

    <!-- Documentation -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Installation -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Installation</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Method 1: WordPress Plugin (Recommended)</h3>
                        <ol class="list-decimal list-inside space-y-3 text-gray-600 ml-4">
                            <li>Download the CulturalTranslate plugin from <a href="#" class="text-indigo-600 hover:underline">WordPress.org</a></li>
                            <li>Go to your WordPress admin dashboard</li>
                            <li>Navigate to <strong>Plugins → Add New → Upload Plugin</strong></li>
                            <li>Upload the downloaded ZIP file</li>
                            <li>Click <strong>Install Now</strong> and then <strong>Activate</strong></li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Method 2: REST API Integration</h3>
                        <p class="text-gray-600 mb-4">Add this code to your theme's <code class="bg-gray-100 px-2 py-1 rounded">functions.php</code>:</p>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-php">// Add CulturalTranslate API integration
add_action('rest_api_init', function () {
    register_rest_route('culturaltranslate/v1', '/translate', array(
        'methods' => 'POST',
        'callback' => 'culturaltranslate_translate_post',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));
});

function culturaltranslate_translate_post($request) {
    $post_id = $request['post_id'];
    $target_lang = $request['target_lang'];
    
    $api_key = get_option('culturaltranslate_api_key');
    $api_url = 'https://culturaltranslate.com/api/integrations/wordpress/translate';
    
    $response = wp_remote_post($api_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode(array(
            'post_id' => $post_id,
            'target_language' => $target_lang,
            'site_url' => get_site_url()
        ))
    ));
    
    return json_decode(wp_remote_retrieve_body($response));
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Configuration</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Get Your API Key</h3>
                        <p class="text-gray-600 mb-4">Get your API key from your <a href="/dashboard" class="text-indigo-600 hover:underline">CulturalTranslate dashboard</a></p>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Connect WordPress Site</h3>
                        <p class="text-gray-600 mb-4">In your WordPress admin:</p>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600 ml-4">
                            <li>Go to <strong>Settings → CulturalTranslate</strong></li>
                            <li>Enter your API key</li>
                            <li>Select target languages</li>
                            <li>Choose post types to translate</li>
                            <li>Click <strong>Save Changes</strong></li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Start Translating</h3>
                        <p class="text-gray-600 mb-4">When editing a post or page, you'll see a "Translate" button in the editor. Click it to translate your content!</p>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Features</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-file-alt text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Posts & Pages</h4>
                            <p class="text-gray-600 text-sm">Translate all your posts and pages automatically</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-th-large text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Custom Post Types</h4>
                            <p class="text-gray-600 text-sm">Support for WooCommerce products and custom types</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-sync text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Auto-Sync</h4>
                            <p class="text-gray-600 text-sm">Keep translations in sync when you update content</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-globe text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">SEO Friendly</h4>
                            <p class="text-gray-600 text-sm">Optimized for multilingual SEO</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Plugin -->
            <div class="bg-indigo-50 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Download WordPress Plugin</h2>
                <p class="text-gray-600 mb-6">
                    Get the official CulturalTranslate plugin for WordPress
                </p>
                <div class="flex space-x-4">
                    <a href="/dashboard" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-download mr-2"></i> Download Plugin
                    </a>
                    <a href="/contact" class="px-6 py-3 bg-white text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                        Contact Support
                    </a>
                </div>
            </div>

        </div>
    </section>

    @include('components.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>

</body>
</html>
