@extends('layouts.app')

@section('title', __('pages.status.title'))

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold">{{ __('pages.status.heading') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('pages.status.description') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Service Status -->
        <div class="p-5 border rounded-lg bg-white">
            <h2 class="text-xl font-medium mb-3">{{ __('pages.status.services') }}</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-gray-800">{{ __('pages.status.api') }}</span>
                    </div>
                    <span class="text-green-600 font-medium">{{ __('pages.status.operational') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-gray-800">{{ __('pages.status.realtime') }}</span>
                    </div>
                    <span class="text-green-600 font-medium">{{ __('pages.status.operational') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-gray-800">{{ __('pages.status.payments') }}</span>
                    </div>
                    <span class="text-green-600 font-medium">{{ __('pages.status.operational') }}</span>
                </div>
            </div>
        </div>

        <!-- Incident History -->
        <div class="p-5 border rounded-lg bg-white lg:col-span-2">
            <h2 class="text-xl font-medium mb-3">{{ __('pages.status.incidents') }}</h2>
            <div class="space-y-4">
                <div class="p-4 border rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">No incidents reported</p>
                            <p class="text-sm text-gray-600">We continuously monitor uptime, latency, and error rates across regions.</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Healthy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SLOs & Uptime -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-5 border rounded-lg bg-white">
            <p class="text-sm text-gray-500">API Uptime (30 days)</p>
            <p class="text-2xl font-semibold text-gray-900">99.98%</p>
            <p class="text-xs text-gray-500 mt-1">Targets: Global ≥ 99.95%</p>
        </div>
        <div class="p-5 border rounded-lg bg-white">
            <p class="text-sm text-gray-500">Median Latency</p>
            <p class="text-2xl font-semibold text-gray-900">180 ms</p>
            <p class="text-xs text-gray-500 mt-1">Measured server-side</p>
        </div>
        <div class="p-5 border rounded-lg bg-white">
            <p class="text-sm text-gray-500">Error Rate</p>
            <p class="text-2xl font-semibold text-gray-900">0.03%</p>
            <p class="text-xs text-gray-500 mt-1">5xx + 429</p>
        </div>
    </div>

    <!-- Regions & Providers -->
    <div class="mt-10 p-5 border rounded-lg bg-white">
        <h2 class="text-xl font-medium mb-3">Regions</h2>
        <div class="flex flex-wrap gap-2 text-sm text-gray-700">
            <span class="px-2 py-1 rounded bg-gray-100">US-East</span>
            <span class="px-2 py-1 rounded bg-gray-100">US-West</span>
            <span class="px-2 py-1 rounded bg-gray-100">EU-Central</span>
            <span class="px-2 py-1 rounded bg-gray-100">EU-West</span>
            <span class="px-2 py-1 rounded bg-gray-100">AP-South</span>
        </div>
    </div>

    <!-- Support & Links -->
    <div class="mt-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="text-gray-700">
            Need help? Contact support at
            <a href="mailto:support@culturaltranslate.com" class="text-blue-600 hover:underline">support@culturaltranslate.com</a>.
        </div>
        <div class="flex gap-4">
            <a href="/sitemap.xml" class="text-blue-600 hover:underline">{{ __('pages.status.view_sitemap') }}</a>
            <a href="/api-docs" class="text-blue-600 hover:underline">API Docs</a>
            <a href="/demo" class="text-blue-600 hover:underline">Live Demo</a>
        </div>
    </div>
</div>
@endsection
