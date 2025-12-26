<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 text-center">
            <div class="mb-6">
                <svg class="w-20 h-20 text-yellow-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Cancelled</h1>
            
            <p class="text-gray-600 mb-8">
                Your payment was cancelled. No charges have been made to your account.
            </p>

            <p class="text-sm text-gray-500 mb-8">
                If you encountered an issue or have questions, please don't hesitate to contact our support team.
            </p>

            <div class="space-y-3">
                <a href="{{ url('/pricing-plans') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Back to Pricing
                </a>
                <a href="{{ route('dashboard') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors">
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
