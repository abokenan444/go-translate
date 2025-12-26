@extends('docs.layout')

@section('content')
    <h1>Configuration Guide</h1>
    <p>Learn how to configure GoTranslate for your project's specific needs.</p>

    <h2>Configuration File</h2>
    <p>After initialization, you'll find a <code>gotranslate.config.json</code> file in your project root:</p>
    <pre><code>{
  "apiKey": "your-api-key-here",
  "sourceLanguage": "en",
  "targetLanguages": ["ar", "fr", "es", "de"],
  "translationPath": "./locales",
  "formats": ["json", "yaml"]
}</code></pre>

    <h2>Configuration Options</h2>
    <ul>
        <li><strong>apiKey:</strong> Your GoTranslate API key from the dashboard</li>
        <li><strong>sourceLanguage:</strong> The base language of your application</li>
        <li><strong>targetLanguages:</strong> Array of languages to translate into</li>
        <li><strong>translationPath:</strong> Directory where translation files are stored</li>
        <li><strong>formats:</strong> Output format (json, yaml, po, etc.)</li>
    </ul>

    <h2>Environment Variables</h2>
    <p>For security, you can also use environment variables:</p>
    <pre><code>GOTRANSLATE_API_KEY=your_api_key_here
GOTRANSLATE_PROJECT_ID=your_project_id</code></pre>

    <h2>Framework-Specific Setup</h2>
    
    <h3>Laravel</h3>
    <pre><code>// config/gotranslate.php
return [
    'api_key' => env('GOTRANSLATE_API_KEY'),
    'source_language' => 'en',
    'target_languages' => ['ar', 'fr', 'es'],
];</code></pre>

    <h3>Next.js</h3>
    <pre><code>// next.config.js
module.exports = {
  i18n: {
    locales: ['en', 'ar', 'fr', 'es'],
    defaultLocale: 'en',
  },
  gotranslate: {
    apiKey: process.env.GOTRANSLATE_API_KEY,
  },
}</code></pre>

    <h2>Next Steps</h2>
    <p>Now that your project is configured, learn about <a href="/docs/projects" class="text-blue-600 hover:underline">creating and managing projects</a>.</p>
@endsection
