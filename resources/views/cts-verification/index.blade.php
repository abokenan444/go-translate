<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify CTS Certificate - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Verify CTS Certificate</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter the certificate ID to verify its authenticity
                </p>
            </div>

            <!-- Search Form -->
            <div class="bg-white shadow-lg rounded-lg p-8">
                <form action="{{ route('cts-verify.show', ['certificateId' => 'PLACEHOLDER']) }}" method="GET" onsubmit="return handleSubmit(event)">
                    <div class="space-y-6">
                        <div>
                            <label for="certificate_id" class="block text-sm font-medium text-gray-700">
                                Certificate ID
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="certificate_id" 
                                       id="certificate_id" 
                                       required
                                       placeholder="CT-CTS-2025-12-00000001"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Format: CT-CTS-YYYY-MM-XXXXXXXX
                            </p>
                        </div>

                        <div>
                            <button type="submit" 
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Verify Certificate
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Info Cards -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            What is CTS?
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>
                                CTS (CulturalTranslate Standard) is our certification system that ensures translations meet cultural safety and quality standards.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTS Levels -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">CTS Certification Levels</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            CTS-A
                        </span>
                        <span class="ml-3 text-sm text-gray-600">Government-Safe (85-100)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            CTS-B
                        </span>
                        <span class="ml-3 text-sm text-gray-600">Commercial-Safe (65-84)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            CTS-C
                        </span>
                        <span class="ml-3 text-sm text-gray-600">Requires Review (40-64)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            CTS-R
                        </span>
                        <span class="ml-3 text-sm text-gray-600">High Risk (0-39)</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <a href="{{ url('/gov') }}" class="text-blue-600 hover:text-blue-800">
                    Learn more about our Government Portal
                </a>
            </div>
        </div>
    </div>

    <script>
        function handleSubmit(event) {
            event.preventDefault();
            const certificateId = document.getElementById('certificate_id').value.trim();
            if (certificateId) {
                window.location.href = `/cts-verify/${encodeURIComponent(certificateId)}`;
            }
            return false;
        }
    </script>
</body>
</html>
