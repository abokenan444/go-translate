@extends('layouts.app')

@section('title', $displayMetaTitle)
@section('meta_description', $displayMetaDescription)

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 6rem 1.5rem 8rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }
    
    .page-header h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .page-header .subtitle {
        font-size: 1.25rem;
        opacity: 0.95;
        max-width: 800px;
        margin: 0 auto 1.5rem;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }
    
    .page-header .last-updated {
        font-size: 0.95rem;
        opacity: 0.85;
        position: relative;
        z-index: 1;
    }
    
    .content-wrapper {
        max-width: 1100px;
        margin: -3rem auto 0;
        padding: 0 1.5rem 5rem;
        position: relative;
        z-index: 10;
    }
    
    .content-card {
        background: white;
        border-radius: 1rem;
        padding: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .markdown-content h1 {
        font-size: 2.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 4px solid #667eea;
    }
    
    .markdown-content h2 {
        font-size: 2.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .markdown-content h3 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #334155;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .markdown-content h4 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #475569;
        margin-top: 1.75rem;
        margin-bottom: 0.875rem;
    }
    
    .markdown-content p {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #475569;
        margin-bottom: 1.5rem;
    }
    
    .markdown-content ul, .markdown-content ol {
        margin: 1.5rem 0;
        padding-left: 2rem;
    }
    
    .markdown-content li {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #475569;
        margin-bottom: 0.75rem;
    }
    
    .markdown-content a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border-bottom: 2px solid transparent;
    }
    
    .markdown-content a:hover {
        color: #764ba2;
        border-bottom-color: #764ba2;
    }
    
    .markdown-content blockquote {
        border-left: 4px solid #667eea;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #64748b;
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }
    
    .markdown-content code {
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-family: 'Courier New', monospace;
        font-size: 0.95em;
        color: #667eea;
    }
    
    .markdown-content pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1.5rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        margin: 2rem 0;
    }
    
    .markdown-content pre code {
        background: transparent;
        color: inherit;
        padding: 0;
    }
    
    .markdown-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .markdown-content th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }
    
    .markdown-content td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .markdown-content tr:last-child td {
        border-bottom: none;
    }
    
    .markdown-content tr:nth-child(even) {
        background: #f8fafc;
    }
    
    .markdown-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 2rem 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .markdown-content hr {
        border: none;
        border-top: 2px solid #e2e8f0;
        margin: 3rem 0;
    }
    
    .markdown-content strong {
        color: #1e293b;
        font-weight: 600;
    }
    
    .markdown-content em {
        color: #64748b;
    }
    
    /* Feedback Widget Styles */
    .feedback-widget {
        margin-top: 40px;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        text-align: center;
    }
    
    .feedback-widget h3 {
        color: white;
        margin-bottom: 15px;
        font-size: 20px;
    }
    
    .feedback-widget p {
        color: rgba(255,255,255,0.9);
        margin-bottom: 25px;
        font-size: 14px;
    }
    
    .feedback-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .feedback-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid white;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .feedback-btn:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    #feedback-message {
        color: white;
        font-weight: bold;
        display: none;
        margin-top: 15px;
    }
    
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2.5rem;
        }
        
        .page-header .subtitle {
            font-size: 1.125rem;
        }
        
        .content-card {
            padding: 2rem 1.5rem;
        }
        
        .markdown-content h1 {
            font-size: 2rem;
        }
        
        .markdown-content h2 {
            font-size: 1.75rem;
        }
        
        .markdown-content h3 {
            font-size: 1.5rem;
        }
        
        .markdown-content p, .markdown-content li {
            font-size: 1rem;
        }
    }
</style>

<div class="page-header">
    <h1>{{ $displayTitle }}</h1>
    @if($displayMetaDescription)
    <p class="subtitle">{{ $displayMetaDescription }}</p>
    @endif
    <p class="last-updated">Last Updated: {{ $page->updated_at->format('F j, Y') }}</p>
</div>

<div class="content-wrapper">
    <div class="content-card">
        <div class="markdown-content">
            {!! \Illuminate\Support\Str::markdown($displayContent) !!}
        </div>
        
        @if($page->slug === 'help-center')
        <div class="feedback-widget">
            <h3>Was this information helpful?</h3>
            <p>Help us improve our Help Center by providing your feedback.</p>
            
            <div class="feedback-buttons">
                <button onclick="submitFeedback('helpful')" class="feedback-btn">
                    👍 Helpful
                </button>
                <button onclick="submitFeedback('not-helpful')" class="feedback-btn">
                    👎 Not Helpful
                </button>
            </div>
            
            <div id="feedback-message"></div>
        </div>
        @endif
    </div>
</div>

@if($page->slug === 'help-center')
<script>
function submitFeedback(type) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'help_center_feedback', {
            'feedback_type': type,
            'page_url': window.location.pathname
        });
    }
    
    const messageDiv = document.getElementById('feedback-message');
    messageDiv.style.display = 'block';
    messageDiv.textContent = type === 'helpful' ? '✅ Thank you for your feedback! We are glad you found this helpful.' : '✅ Thank you for your feedback! We will work on improving this content.';
    
    document.querySelectorAll('.feedback-btn').forEach(btn => {
        btn.style.display = 'none';
    });
}
</script>
@endif

@endsection
