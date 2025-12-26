@extends('docs.layout')

@section('title', 'Glossary & Terminology')

@section('content')
    <h1>Glossary & Terminology Management</h1>
    <p>Maintain consistent terminology across all your translations with glossaries.</p>

    <h2>Creating a Glossary</h2>
    <p>Create project-specific or global glossaries:</p>

    <h3>Via Web Interface</h3>
    <ol>
        <li>Go to Project Settings</li>
        <li>Click "Glossary" tab</li>
        <li>Click "Add Term"</li>
        <li>Enter source term and translations</li>
        <li>Save</li>
    </ol>

    <h3>Via API</h3>
    <pre><code>POST /api/v1/glossaries
{
  "project_id": "proj_123",
  "term": "Dashboard",
  "translations": {
    "ar": "لوحة التحكم",
    "es": "Panel de control",
    "fr": "Tableau de bord"
  },
  "context": "User interface element",
  "do_not_translate": false
}</code></pre>

    <h2>Glossary Types</h2>

    <h3>Project Glossary</h3>
    <p>Specific to one project. Highest priority.</p>

    <h3>Organization Glossary</h3>
    <p>Shared across all projects in your organization.</p>

    <h3>Public Glossary</h3>
    <p>Community-maintained glossaries you can subscribe to.</p>

    <h2>Term Properties</h2>
    <ul>
        <li><strong>Source Term:</strong> Original word/phrase</li>
        <li><strong>Translations:</strong> Target language equivalents</li>
        <li><strong>Context:</strong> Usage notes and examples</li>
        <li><strong>Case Sensitive:</strong> Exact match required</li>
        <li><strong>Do Not Translate:</strong> Keep original (brand names)</li>
        <li><strong>Part of Speech:</strong> Noun, verb, adjective, etc.</li>
    </ul>

    <h2>Import/Export</h2>

    <h3>Supported Formats</h3>
    <ul>
        <li>CSV</li>
        <li>Excel (.xlsx)</li>
        <li>TBX (TermBase eXchange)</li>
        <li>JSON</li>
    </ul>

    <h3>CSV Format</h3>
    <pre><code>Term (Source),Arabic,Spanish,French,Context,Do Not Translate
Dashboard,لوحة التحكم,Panel de control,Tableau de bord,UI element,false
Login,تسجيل الدخول,Iniciar sesión,Connexion,Authentication,false
iPhone,iPhone,iPhone,iPhone,Product name,true</code></pre>

    <h3>Import via API</h3>
    <pre><code>POST /api/v1/glossaries/import
Content-Type: multipart/form-data

file: glossary.csv
project_id: proj_123
merge: true</code></pre>

    <h2>Auto-Apply</h2>
    <p>Glossary terms are automatically applied during translation:</p>
    <ul>
        <li>Exact matches highlighted in translation editor</li>
        <li>Suggestions shown for partial matches</li>
        <li>Warnings for inconsistent usage</li>
    </ul>

    <h2>Quality Checks</h2>
    <p>Glossary-based QA:</p>
    <ul>
        <li>Detect missing glossary terms</li>
        <li>Flag incorrect term usage</li>
        <li>Validate do-not-translate terms</li>
        <li>Check term consistency across files</li>
    </ul>

    <h2>Best Practices</h2>
    <div class="alert alert-success">
        <ul>
            <li>Add context and examples to every term</li>
            <li>Mark brand names as "do not translate"</li>
            <li>Review and update regularly</li>
            <li>Use consistent capitalization</li>
            <li>Include plural forms when relevant</li>
        </ul>
    </div>
@endsection
