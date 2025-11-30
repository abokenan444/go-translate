<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 text-center">
            <div class="mb-6">
                <svg class="w-20 h-20 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
            
            <p class="text-gray-600 mb-8">
                Thank you for subscribing. Your payment has been processed successfully and your subscription is now active.
            </p>

            @if(session('subscription'))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Subscription Details</h3>
                    <p class="text-sm text-gray-700">
                        <strong>Plan:</strong> {{ ucfirst(session('subscription')->plan_name) }}<br>
                        <strong>Token Limit:</strong> {{ number_format(session('subscription')->tokens_limit) }}<br>
                        <strong>Status:</strong> <span class="text-green-600">Active</span>
                    </p>
                </div>
            @endif

            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Go to Dashboard
                </a>
                <a href="{{ route('pricing') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors">
                    View All Plans
                </a>
            </div>
        </div>
    </div>
</body>
</html>
