<!-- Google Site Verification -->
@if(config('services.google_verification.code'))
<meta name="google-site-verification" content="{{ config('services.google_verification.code') }}" />
@endif

<!-- Bing Webmaster Verification -->
<meta name="msvalidate.01" content="YOUR_BING_VERIFICATION_CODE" />

<!-- Yandex Verification -->
<meta name="yandex-verification" content="YOUR_YANDEX_VERIFICATION_CODE" />

<!-- SEO Meta Tags -->
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="googlebot" content="index, follow">
<meta name="bingbot" content="index, follow">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'Professional translation services powered by AI and human expertise' }}">
<meta property="og:image" content="{{ $image ?? asset('images/og-image.png') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $title ?? config('app.name') }}">
<meta property="twitter:description" content="{{ $description ?? 'Professional translation services powered by AI and human expertise' }}">
<meta property="twitter:image" content="{{ $image ?? asset('images/og-image.png') }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}" />

<!-- Alternate Languages -->
<link rel="alternate" hreflang="en" href="{{ url()->current() }}" />
<link rel="alternate" hreflang="ar" href="{{ url()->current() }}" />
<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}" />

<!-- Structured Data - Organization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "{{ config('app.name') }}",
  "url": "{{ config('app.url') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "description": "Professional translation services powered by AI and human expertise",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+1-XXX-XXX-XXXX",
    "contactType": "customer service",
    "areaServed": "Worldwide",
    "availableLanguage": ["English", "Arabic"]
  },
  "sameAs": [
    "https://www.facebook.com/culturaltranslate",
    "https://twitter.com/culturaltranslate",
    "https://www.linkedin.com/company/culturaltranslate"
  ]
}
</script>

<!-- Structured Data - WebSite -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ config('app.name') }}",
  "url": "{{ config('app.url') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ config('app.url') }}/search?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
