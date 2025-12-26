@extends('docs.layout')

@section('title', 'Translation Management')

@section('content')
    <h1>Translation Management</h1>
    <p>Learn how to manage translations efficiently in Cultural Translate.</p>

    <h2>Creating Translations</h2>
    <p>You can create translations through the web interface or API:</p>

    <h3>Web Interface</h3>
    <ol>
        <li>Navigate to your project dashboard</li>
        <li>Click "New Translation"</li>
        <li>Enter source text and select target language</li>
        <li>Choose translation engine (AI, Neural, Human)</li>
        <li>Click "Translate"</li>
    </ol>

    <h3>API</h3>
    <pre><code>POST /api/v1/translations
{
  "project_id": "proj_123",
  "source_text": "Hello, world!",
  "source_language": "en",
  "target_language": "ar",
  "engine": "ai",
  "cultural_context": true
}</code></pre>

    <h2>Translation Engines</h2>
    <ul>
        <li><strong>AI Engine:</strong> GPT-powered with cultural awareness</li>
        <li><strong>Neural Engine:</strong> Fast neural machine translation</li>
        <li><strong>Human Review:</strong> Professional human translators</li>
        <li><strong>Hybrid:</strong> AI + Human review</li>
    </ul>

    <h2>Cultural Context</h2>
    <p>Enable cultural context for more accurate, culturally-appropriate translations:</p>
    <pre><code>{
  "cultural_context": true,
  "target_culture": "ar-SA",
  "formality": "formal",
  "tone": "professional"
}</code></pre>

    <h2>Batch Translations</h2>
    <p>Translate multiple texts at once:</p>
    <pre><code>POST /api/v1/translations/batch
{
  "project_id": "proj_123",
  "items": [
    {"text": "Welcome", "key": "welcome"},
    {"text": "Goodbye", "key": "goodbye"}
  ],
  "target_language": "ar"
}</code></pre>

    <h2>Translation Memory</h2>
    <p>Cultural Translate automatically saves translations to improve consistency:</p>
    <ul>
        <li>Automatic matching of similar phrases</li>
        <li>Suggest previous translations</li>
        <li>Reduce costs by reusing translations</li>
        <li>Export/import TM databases</li>
    </ul>

    <h2>Quality Assurance</h2>
    <p>Built-in QA checks:</p>
    <ul>
        <li>Length validation</li>
        <li>Placeholder consistency</li>
        <li>Cultural appropriateness</li>
        <li>Spelling and grammar</li>
        <li>Terminology consistency</li>
    </ul>

    <h2>Review & Edit</h2>
    <p>Review and edit translations before publishing:</p>
    <ol>
        <li>Navigate to translation details</li>
        <li>Click "Edit"</li>
        <li>Make changes</li>
        <li>Save and mark as reviewed</li>
    </ol>

    <div class="alert alert-info">
        <strong>Tip:</strong> Use keyboard shortcuts (Ctrl+Enter) to quickly approve translations.
    </div>
@endsection
