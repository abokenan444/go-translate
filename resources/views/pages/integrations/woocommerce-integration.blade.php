<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooCommerce Integration - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
</head>
<body class="bg-gray-50">

    @include('components.navigation')

    <!-- Hero -->
    <section class="bg-gradient-to-r from-purple-600 to-purple-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4 mb-6">
                <i class="fab fa-wordpress text-6xl"></i>
                <h1 class="text-5xl font-bold">WooCommerce Integration</h1>
            </div>
            <p class="text-xl text-purple-100 max-w-3xl">
                Translate your entire WooCommerce store including products, categories, and checkout pages
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
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4">
                        <p class="text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Note:</strong> WooCommerce integration requires the WordPress plugin to be installed first.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Prerequisites</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 ml-4">
                            <li>WordPress 5.0 or higher</li>
                            <li>WooCommerce 5.0 or higher</li>
                            <li>CulturalTranslate WordPress Plugin</li>
                            <li>Active CulturalTranslate subscription</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Installation Steps</h3>
                        <ol class="list-decimal list-inside space-y-3 text-gray-600 ml-4">
                            <li>Install and activate the <a href="/integrations/wordpress" class="text-indigo-600 hover:underline">CulturalTranslate WordPress plugin</a></li>
                            <li>Go to <strong>WooCommerce → Settings → CulturalTranslate</strong></li>
                            <li>Enable WooCommerce integration</li>
                            <li>Select which elements to translate (products, categories, attributes, etc.)</li>
                            <li>Click <strong>Save Changes</strong></li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- What Gets Translated -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">What Gets Translated</h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Product Information</h4>
                            <p class="text-gray-600 text-sm">Product names, descriptions, short descriptions, and SKUs</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Categories & Tags</h4>
                            <p class="text-gray-600 text-sm">Product categories, tags, and their descriptions</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Product Attributes</h4>
                            <p class="text-gray-600 text-sm">Color, size, and custom attributes</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Checkout Pages</h4>
                            <p class="text-gray-600 text-sm">Cart, checkout, and thank you pages</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Email Templates</h4>
                            <p class="text-gray-600 text-sm">Order confirmation and shipping notification emails</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Translation -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Bulk Translation</h2>
                <p class="text-gray-600 mb-4">Translate multiple products at once:</p>
                <ol class="list-decimal list-inside space-y-3 text-gray-600 ml-4">
                    <li>Go to <strong>Products → All Products</strong></li>
                    <li>Select the products you want to translate</li>
                    <li>From the <strong>Bulk Actions</strong> dropdown, select <strong>Translate</strong></li>
                    <li>Choose your target languages</li>
                    <li>Click <strong>Apply</strong></li>
                </ol>
                
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Pro Tip:</h4>
                    <p class="text-gray-600 text-sm">Enable auto-translation to automatically translate new products as they're added to your store.</p>
                </div>
            </div>

            <!-- API Example -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">API Integration</h2>
                <p class="text-gray-600 mb-4">Translate products programmatically using our API:</p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-php">// Translate a WooCommerce product
$api_key = get_option('culturaltranslate_api_key');
$product_id = 123;
$target_lang = 'ar';

$response = wp_remote_post('https://culturaltranslate.com/api/integrations/wordpress/translate', array(
    'headers' => array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type' => 'application/json'
    ),
    'body' => json_encode(array(
        'post_id' => $product_id,
        'post_type' => 'product',
        'target_language' => $target_lang,
        'site_url' => get_site_url(),
        'translate_meta' => true // Include product meta data
    ))
));

$result = json_decode(wp_remote_retrieve_body($response));
if ($result->success) {
    echo 'Product translated successfully!';
}</code></pre>
            </div>

            <!-- Download -->
            <div class="bg-purple-50 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Get Started with WooCommerce Translation</h2>
                <p class="text-gray-600 mb-6">
                    Start translating your WooCommerce store today
                </p>
                <div class="flex space-x-4">
                    <a href="/dashboard" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-download mr-2"></i> Download Plugin
                    </a>
                    <a href="/pricing" class="px-6 py-3 bg-white text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition">
                        View Pricing
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
