<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $content->seo_title ?? $content->page_title }} - Cultural Translate</title>
    @if($content->seo_description)
        <meta name="description" content="{{ $content->seo_description }}">
    @endif
    @if($content->seo_keywords)
        <meta name="keywords" content="{{ $content->seo_keywords }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #0D0D0D 0%, #1a1a2e 100%);
            min-height: 100vh;
        }
        
        .content-wrapper {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        
        .prose {
            max-width: none;
        }
        
        .prose h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        
        .prose h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #fff;
        }
        
        .prose h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #e5e7eb;
        }
        
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
            color: #d1d5db;
        }
        
        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            color: #d1d5db;
        }
        
        .prose li {
            margin-bottom: 0.5rem;
        }
        
        .prose a {
            color: #6C63FF;
            text-decoration: underline;
        }
        
        .prose a:hover {
            color: #5A52D5;
        }
        
        .prose strong {
            font-weight: 600;
            color: #fff;
        }
        
        .prose table {
            width: 100%;
            margin: 1.5rem 0;
            border-collapse: collapse;
        }
        
        .prose table th {
            background: rgba(108, 99, 255, 0.1);
            color: #fff;
            font-weight: 600;
            padding: 0.75rem;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .prose table td {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #d1d5db;
        }
        
        .prose table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }
        
        .prose code {
            background: rgba(108, 99, 255, 0.1);
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: #a5b4fc;
        }
        
        .prose pre {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1rem 0;
        }
        
        .prose pre code {
            background: none;
            padding: 0;
            color: #e5e7eb;
        }
        
        .prose blockquote {
            border-left: 4px solid #6C63FF;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #9ca3af;
        }
        
        .prose hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 2rem 0;
        }
    </style>
</head>
<body class="text-white">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="/" class="inline-block mb-6">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                        Cultural Translate
                    </h1>
                </a>
                <h2 class="text-4xl font-bold text-white mb-2">{{ $content->page_title }}</h2>
                @if($content->seo_description)
                    <p class="text-gray-400">{{ $content->seo_description }}</p>
                @endif
            </div>

            <!-- Content -->
            <div class="content-wrapper rounded-2xl p-8 prose prose-invert">
                @if(is_array($content->sections))
                    @foreach($content->sections as $section)
                        @if(isset($section['type']))
                            @if($section['type'] === 'heading')
                                <h2>{{ $section['content'] ?? '' }}</h2>
                            @elseif($section['type'] === 'paragraph')
                                {!! \Illuminate\Support\Str::markdown($section['content'] ?? '') !!}
                            @elseif($section['type'] === 'list')
                                <ul>
                                    @if(isset($section['items']) && is_array($section['items']))
                                        @foreach($section['items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            @elseif($section['type'] === 'code')
                                <pre><code>{{ $section['content'] ?? '' }}</code></pre>
                            @else
                                {!! \Illuminate\Support\Str::markdown($section['content'] ?? '') !!}
                            @endif
                        @else
                            {!! \Illuminate\Support\Str::markdown($section['content'] ?? '') !!}
                        @endif
                    @endforeach
                @else
                    {!! \Illuminate\Support\Str::markdown($content->sections ?? '') !!}
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="/" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
