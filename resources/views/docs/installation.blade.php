@extends('docs.layout')

@section('content')
    <h1>Installation Guide</h1>
    <p>This guide will walk you through the process of setting up GoTranslate for your organization.</p>

    <h2>Prerequisites</h2>
    <p>Before you begin, ensure you have the following:</p>
    <ul>
        <li>A GoTranslate account (Sign up <a href="/register" class="text-blue-600 hover:underline">here</a>)</li>
        <li>API Key (Available in your dashboard)</li>
    </ul>

    <h2>Installing the CLI</h2>
    <p>The easiest way to interact with GoTranslate is via our CLI tool.</p>
    <pre><code>npm install -g @gotranslate/cli</code></pre>

    <h2>Initializing a Project</h2>
    <p>Navigate to your project root and run:</p>
    <pre><code>gotranslate init</code></pre>
    <p>Follow the interactive prompts to configure your source language and target languages.</p>

    <h2>Syncing Translations</h2>
    <p>To push your source keys to GoTranslate:</p>
    <pre><code>gotranslate push</code></pre>
    <p>To pull the latest translations back to your project:</p>
    <pre><code>gotranslate pull</code></pre>

    <h2>Next Steps</h2>
    <p>Learn how to configure your project in depth in the <a href="/docs/configuration" class="text-blue-600 hover:underline">Configuration</a> section.</p>
@endsection
