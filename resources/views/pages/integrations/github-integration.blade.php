<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Integration - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
</head>
<body class="bg-gray-50">

    @include('components.navigation')

    <!-- Hero -->
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4 mb-6">
                <i class="fab fa-github text-6xl"></i>
                <h1 class="text-5xl font-bold">GitHub Integration</h1>
            </div>
            <p class="text-xl text-gray-300 max-w-3xl">
                Automate translation of documentation, README files, and issue comments in your GitHub repositories
            </p>
        </div>
    </section>

    <!-- Documentation -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Quick Start -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Quick Start</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Connect Your GitHub Account</h3>
                        <p class="text-gray-600 mb-4">First, connect your GitHub account to CulturalTranslate:</p>
                        <a href="/dashboard" class="inline-block px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                            <i class="fab fa-github mr-2"></i> Connect GitHub
                        </a>
                        <p class="text-sm text-gray-500 mt-2">Login to your dashboard to connect GitHub</p>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Install GitHub App</h3>
                        <p class="text-gray-600 mb-4">Install the CulturalTranslate GitHub App to your repositories:</p>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600 ml-4">
                            <li>Go to your GitHub repository settings</li>
                            <li>Navigate to "GitHub Apps"</li>
                            <li>Search for "CulturalTranslate"</li>
                            <li>Click "Install" and select repositories</li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Configure Translation Settings</h3>
                        <p class="text-gray-600 mb-4">Create a <code class="bg-gray-100 px-2 py-1 rounded">.culturaltranslate.yml</code> file in your repository root:</p>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-yaml">version: 1
target_languages:
  - ar
  - es
  - fr
  - de
files:
  - README.md
  - docs/**/*.md
auto_translate:
  pull_requests: true
  issues: false
  comments: false</code></pre>
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
                            <h4 class="font-semibold text-gray-900 mb-1">README Translation</h4>
                            <p class="text-gray-600 text-sm">Automatically translate your README files to multiple languages</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-book text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Documentation</h4>
                            <p class="text-gray-600 text-sm">Keep your docs in sync across all languages</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-code-branch text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Pull Request Automation</h4>
                            <p class="text-gray-600 text-sm">Auto-create PRs for translated content</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-comments text-2xl text-indigo-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Issue Translation</h4>
                            <p class="text-gray-600 text-sm">Translate issue comments for global teams</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Usage -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">API Usage</h2>
                <p class="text-gray-600 mb-4">You can also use our API directly in your GitHub Actions:</p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-yaml">@verbatim
name: Translate README
on:
  push:
    paths:
      - 'README.md'

jobs:
  translate:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Translate README
        run: |
          curl -X POST https://culturaltranslate.com/api/integrations/github/translate \
            -H "Authorization: Bearer ${{ secrets.CULTURALTRANSLATE_API_KEY }}" \
            -H "Content-Type: application/json" \
            -d '{
              "repository": "${{ github.repository }}",
              "file": "README.md",
              "target_languages": ["ar", "es", "fr"]
            }'
@endverbatim</code></pre>
            </div>

            <!-- Support -->
            <div class="bg-indigo-50 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Need Help?</h2>
                <p class="text-gray-600 mb-6">
                    Our team is here to help you get started with GitHub integration.
                </p>
                <div class="flex space-x-4">
                    <a href="/api-docs" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        View API Docs
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-yaml.min.js"></script>

</body>
</html>
