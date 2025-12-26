@extends('docs.layout')

@section('content')
    <h1>CLI Tool</h1>
    <p>The GoTranslate CLI is a powerful command-line interface for managing translations directly from your terminal.</p>

    <h2>Installation</h2>
    <pre><code>npm install -g @gotranslate/cli</code></pre>

    <h2>Authentication</h2>
    <p>Login with your API key:</p>
    <pre><code>gotranslate login</code></pre>

    <h2>Common Commands</h2>

    <h3>Initialize a Project</h3>
    <pre><code>gotranslate init</code></pre>

    <h3>Push Source Keys</h3>
    <p>Upload your translation keys to GoTranslate:</p>
    <pre><code>gotranslate push</code></pre>

    <h3>Pull Translations</h3>
    <p>Download the latest translations:</p>
    <pre><code>gotranslate pull</code></pre>

    <h3>Sync (Push + Pull)</h3>
    <pre><code>gotranslate sync</code></pre>

    <h3>Translation Status</h3>
    <p>Check completion status for all languages:</p>
    <pre><code>gotranslate status</code></pre>

    <h2>Project Management</h2>
    
    <h3>List Projects</h3>
    <pre><code>gotranslate project:list</code></pre>

    <h3>Create Project</h3>
    <pre><code>gotranslate project:create "Project Name" --source=en --targets=ar,fr</code></pre>

    <h3>Switch Project</h3>
    <pre><code>gotranslate project:switch [project-id]</code></pre>

    <h2>Translation Commands</h2>

    <h3>Translate Text</h3>
    <pre><code>gotranslate translate "Hello World" --to=ar</code></pre>

    <h3>Add Translation Key</h3>
    <pre><code>gotranslate key:add welcome.message "Welcome!" --context="Homepage greeting"</code></pre>

    <h2>Configuration</h2>
    
    <h3>View Config</h3>
    <pre><code>gotranslate config:show</code></pre>

    <h3>Set Config Value</h3>
    <pre><code>gotranslate config:set translationPath ./i18n</code></pre>

    <h2>CI/CD Integration</h2>
    <p>Use the CLI in your CI/CD pipeline:</p>
    <pre><code># .github/workflows/translations.yml
name: Sync Translations
on:
  push:
    branches: [main]

jobs:
  sync:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - run: npm install -g @gotranslate/cli
      - run: gotranslate sync
        env:
          GOTRANSLATE_API_KEY: @{{ secrets.GOTRANSLATE_API_KEY }}</code></pre>

    <h2>Flags & Options</h2>
    <p>Most commands support these flags:</p>
    <ul>
        <li><code>--verbose</code> - Show detailed output</li>
        <li><code>--json</code> - Output in JSON format</li>
        <li><code>--quiet</code> - Suppress non-error output</li>
        <li><code>--help</code> - Show command help</li>
    </ul>

    <h2>Getting Help</h2>
    <pre><code>gotranslate --help
gotranslate [command] --help</code></pre>
@endsection
