<!-- Google Analytics -->
@if(config('services.google_analytics.tracking_id'))
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.tracking_id') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{ config('services.google_analytics.tracking_id') }}', {
    'cookie_flags': 'SameSite=None;Secure',
    'anonymize_ip': true
  });

  // Custom event tracking
  window.trackEvent = function(category, action, label, value) {
    gtag('event', action, {
      'event_category': category,
      'event_label': label,
      'value': value
    });
  };

  // Track page views
  gtag('event', 'page_view', {
    'page_title': document.title,
    'page_location': window.location.href,
    'page_path': window.location.pathname
  });
</script>
@endif
