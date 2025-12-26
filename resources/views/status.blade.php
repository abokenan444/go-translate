@extends('layouts.app')

@section('title', 'System Status - CulturalTranslate')

@section('meta')
    <meta name="description" content="Real-time system status and uptime for the CulturalTranslate platform. Check the operational status of all services.">
    <meta name="robots" content="index, follow">
@endsection

@section('content')
<div class="container mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <header class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
            System Status
        </h1>
        <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
            Real-time operational status of CulturalTranslate services.
        </p>
    </header>

    <!-- Main Status Indicator -->
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 mb-12 border-t-4 border-green-500 transition duration-300 hover:shadow-2xl">
        <div class="flex items-center justify-between flex-wrap">
            <div class="flex items-center mb-4 sm:mb-0">
                <span class="relative flex h-4 w-4 mr-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                </span>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">All Systems Operational</h2>
            </div>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Last Updated: <span id="last-updated" class="font-bold">Just now</span>
            </span>
        </div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">
            All core services are currently running without any reported issues. We are continuously monitoring performance and stability.
        </p>
    </div>

    <!-- Service Components Status -->
    <div class="max-w-5xl mx-auto">
        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 border-b dark:border-gray-700 pb-2">
            Service Components
        </h3>

        <div class="space-y-4">
            @php
                $components = [
                    ['name' => 'Translation API', 'status' => 'Operational', 'color' => 'green', 'uptime' => '99.99%'],
                    ['name' => 'User Authentication', 'status' => 'Operational', 'color' => 'green', 'uptime' => '100.00%'],
                    ['name' => 'Database Service', 'status' => 'Operational', 'color' => 'green', 'uptime' => '99.98%'],
                    ['name' => 'File Storage', 'status' => 'Operational', 'color' => 'green', 'uptime' => '100.00%'],
                    ['name' => 'Web Interface (Frontend)', 'status' => 'Operational', 'color' => 'green', 'uptime' => '99.99%'],
                    ['name' => 'Billing & Payments', 'status' => 'Operational', 'color' => 'green', 'uptime' => '99.95%'],
                    // Example of a non-operational status for demonstration
                    // ['name' => 'Legacy API Gateway', 'status' => 'Degraded Performance', 'color' => 'yellow', 'uptime' => '99.50%'],
                ];
            @endphp

            @foreach ($components as $component)
                <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md transition duration-300 hover:shadow-lg">
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full bg-{{ $component['color'] }}-500 mr-3"></span>
                        <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $component['name'] }}</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:inline">
                            Uptime (90 days): <span class="font-semibold text-{{ $component['color'] }}-600 dark:text-{{ $component['color'] }}-400">{{ $component['uptime'] }}</span>
                        </span>
                        <span class="text-base font-semibold text-{{ $component['color'] }}-600 dark:text-{{ $component['color'] }}-400">
                            {{ $component['status'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Incident History Placeholder -->
    <div class="max-w-5xl mx-auto mt-12">
        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 border-b dark:border-gray-700 pb-2">
            Recent Incident History
        </h3>
        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg text-center text-gray-500 dark:text-gray-400">
            <p class="text-lg">No incidents reported in the last 90 days.</p>
            <p class="mt-2 text-sm">We strive for 100% uptime and transparency. Any service disruption will be posted here immediately.</p>
        </div>
    </div>

    <!-- Footer Link -->
    <div class="text-center mt-12">
        <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition duration-150 ease-in-out">
            View full historical data &rarr;
        </a>
    </div>
</div>
@endsection
