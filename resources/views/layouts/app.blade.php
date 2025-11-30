<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="{{ config('app.name') }} — Cultural Translation Platform">
  <meta property="og:title" content="@yield('title', config('app.name'))">
  <meta property="og:description" content="{{ config('app.name') }} — Cultural Translation Platform">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('images/og-default.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900">
  @includeIf('components.navigation')

  <main>
    @yield('content')
  </main>

  @includeIf('components.footer')
</body>
</html>