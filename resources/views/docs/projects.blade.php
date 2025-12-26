@extends('docs.layout')

@section('content')
    <h1>Projects</h1>
    <p>Projects are the core organizational unit in GoTranslate. Each project represents an application or website that needs translation.</p>

    <h2>Creating a Project</h2>
    <p>You can create projects through the dashboard or the CLI:</p>
    
    <h3>Via Dashboard</h3>
    <ol class="list-decimal pl-6 mb-4">
        <li>Log in to your <a href="https://admin.culturaltranslate.com" class="text-blue-600 hover:underline">admin dashboard</a></li>
        <li>Click "New Project"</li>
        <li>Enter project name and select languages</li>
        <li>Click "Create"</li>
    </ol>

    <h3>Via CLI</h3>
    <pre><code>gotranslate project:create "My Website" --source=en --targets=ar,fr,es</code></pre>

    <h2>Project Structure</h2>
    <p>Each project contains:</p>
    <ul>
        <li><strong>Keys:</strong> The text strings to be translated</li>
        <li><strong>Translations:</strong> The translated versions in each target language</li>
        <li><strong>Glossary:</strong> Project-specific terminology</li>
        <li><strong>Translation Memory:</strong> Previously translated segments</li>
    </ul>

    <h2>Managing Projects</h2>
    
    <h3>Listing Projects</h3>
    <pre><code>gotranslate project:list</code></pre>

    <h3>Switching Projects</h3>
    <pre><code>gotranslate project:switch [project-id]</code></pre>

    <h3>Project Settings</h3>
    <p>Access project settings in the dashboard to configure:</p>
    <ul>
        <li>AI model preferences</li>
        <li>Quality assurance rules</li>
        <li>Collaboration permissions</li>
        <li>Webhook notifications</li>
    </ul>

    <h2>Best Practices</h2>
    <ul>
        <li>Use one project per application or website</li>
        <li>Name projects clearly (e.g., "Mobile App - iOS", "Website - Marketing")</li>
        <li>Set up glossaries early to ensure consistency</li>
        <li>Enable translation memory to save costs</li>
    </ul>
@endsection
