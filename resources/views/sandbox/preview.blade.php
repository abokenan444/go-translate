<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $instance->company_name }} - Sandbox Preview</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; margin:0; padding:0; color:#111; }
        .container { max-width: 960px; margin: 0 auto; padding: 24px; }
        header { padding: 24px 0; border-bottom: 1px solid #eee; }
        h1 { font-size: 28px; margin:0; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px; }
        .card { border: 1px solid #eee; border-radius: 10px; padding: 16px; background:#fff; }
        .muted { color:#666; }
        .badge { display:inline-block; padding:4px 8px; border-radius:12px; background:#eef; color:#225; font-size:12px; }
        footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #eee; color:#666; }
        pre { background:#f8f8f8; padding:12px; border-radius:8px; overflow:auto; }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>{{ $instance->company_name }} <span class="badge">Sandbox</span></h1>
        <div class="muted">Subdomain: {{ $instance->subdomain }} • Status: {{ $instance->status }}</div>
    </header>

    <div class="grid">
        <div class="card">
            <h2>Original Content</h2>
            <pre>{{ json_encode($page->original_content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        <div class="card">
            <h2>Translated Content</h2>
            @if($page->translated_content)
                <pre>{{ json_encode($page->translated_content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            @else
                <div class="muted">No translation yet. Use API Playground to translate.</div>
            @endif
        </div>
    </div>

    <footer>
        <div>Locale: {{ $page->locale }} • Market: {{ $page->market }} • Path: {{ $page->path }}</div>
    </footer>
</div>
</body>
</html>
