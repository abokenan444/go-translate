@extends('layouts.app')

@section('title', __('pages.careers.title'))

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl">
        <h1 class="text-3xl font-semibold">{{ __('pages.careers.heading') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('pages.careers.intro') }}</p>
    </div>

    <!-- Mission & Values -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-5 border rounded-lg bg-white">
            <h2 class="text-xl font-medium">Mission</h2>
            <p class="text-gray-700 mt-2">Enable culturally intelligent communication for every business, in every language.</p>
        </div>
        <div class="p-5 border rounded-lg bg-white">
            <h2 class="text-xl font-medium">Values</h2>
            <ul class="mt-2 space-y-1 text-gray-700 list-disc pl-5">
                <li>Empathy and inclusion</li>
                <li>Craft and accountability</li>
                <li>Bias-aware AI</li>
            </ul>
        </div>
        <div class="p-5 border rounded-lg bg-white">
            <h2 class="text-xl font-medium">Benefits</h2>
            <ul class="mt-2 space-y-1 text-gray-700 list-disc pl-5">
                <li>Remote-first</li>
                <li>Learning stipend</li>
                <li>Flexible hours</li>
            </ul>
        </div>
    </div>

    <!-- Open Roles -->
    <div class="mt-10">
        <h2 class="text-2xl font-semibold">{{ __('pages.careers.open_roles') }}</h2>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-5 border rounded-lg bg-white">
                <h3 class="text-lg font-medium">Senior Laravel Engineer</h3>
                <p class="text-gray-700 mt-2">Own backend architecture, performance, and reliability across our API and admin stack.</p>
                <ul class="mt-3 list-disc pl-5 text-gray-700 space-y-1">
                    <li>Laravel 10+, queues, caching</li>
                    <li>Stripe, webhook security</li>
                    <li>Filament admin, SQLite/Postgres</li>
                </ul>
                <p class="mt-3 text-sm text-gray-600">Location: Remote • Type: Full-time</p>
            </div>

            <div class="p-5 border rounded-lg bg-white">
                <h3 class="text-lg font-medium">AI Prompt Engineer</h3>
                <p class="text-gray-700 mt-2">Design culturally aware prompts, evaluate quality, and tune model behavior.</p>
                <ul class="mt-3 list-disc pl-5 text-gray-700 space-y-1">
                    <li>NLG evaluation, bias mitigation</li>
                    <li>Prompt chains & guardrails</li>
                    <li>Localization workflows</li>
                </ul>
                <p class="mt-3 text-sm text-gray-600">Location: Remote • Type: Full-time</p>
            </div>

            <div class="p-5 border rounded-lg bg-white">
                <h3 class="text-lg font-medium">Front-end Engineer (Tailwind/Alpine)</h3>
                <p class="text-gray-700 mt-2">Build smooth, accessible UIs for demo tools and docs.</p>
                <ul class="mt-3 list-disc pl-5 text-gray-700 space-y-1">
                    <li>Tailwind CSS, Alpine.js</li>
                    <li>Accessibility & performance</li>
                    <li>Documentation tooling</li>
                </ul>
                <p class="mt-3 text-sm text-gray-600">Location: Remote • Type: Contract</p>
            </div>
        </div>
    </div>

    <!-- How to Apply -->
    <div class="mt-10 p-5 border rounded-lg bg-white">
        <h2 class="text-xl font-medium">{{ __('pages.careers.how_to_apply') }}</h2>
        <p class="text-gray-700 mt-2">{{ __('pages.careers.apply_instructions') }}</p>
        <p class="mt-2"><a href="mailto:careers@culturaltranslate.com" class="text-blue-600 hover:underline">careers@culturaltranslate.com</a></p>
    </div>
</div>
@endsection
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    
    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Careers</h1>
        <div class="bg-white rounded-lg shadow-sm p-8">
            <p class="text-gray-700">This page is under construction.</p>
        </div>
    </div>
    
    @include('components.footer')
</body>
</html>
