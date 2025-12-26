@extends('layouts.app')

@section('title', 'Service Level Agreement (SLA) - Cultural Translate Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4">Service Level Agreement</h1>
                <p class="text-xl text-green-100 max-w-3xl mx-auto">
                    Government • University • Enterprise
                </p>
                <p class="text-sm text-green-200 mt-4">
                    Effective Date: {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8 md:p-12 prose prose-lg max-w-none">
                
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">1</span>
                    Scope
                </h2>
                <p class="text-gray-700 leading-relaxed mb-8">
                    This SLA applies to <strong>Government</strong>, <strong>University</strong>, and <strong>Enterprise Partner</strong> accounts using certified, governed, or API-based services provided by Cultural Translate.
                </p>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">2</span>
                    Platform Availability
                </h2>
                <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-xl mb-6">
                    <ul class="list-none text-gray-700 space-y-3">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Target uptime:</strong> 99.5% monthly</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Planned maintenance announced in advance</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Emergency maintenance permitted for security or integrity reasons</span>
                        </li>
                    </ul>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">3</span>
                    Processing Priority Levels
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Priority</th>
                                <th class="px-6 py-4 text-left font-semibold">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">Normal</td>
                                <td class="px-6 py-4 text-gray-700">Standard processing</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">High</td>
                                <td class="px-6 py-4 text-gray-700">Accelerated queue</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">Urgent</td>
                                <td class="px-6 py-4 text-gray-700">Immediate priority allocation</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">4</span>
                    Incident Response Targets
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Severity</th>
                                <th class="px-6 py-4 text-left font-semibold">Response Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        Critical
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">≤ 2 hours</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                                        High
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">≤ 6 hours</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                        Medium
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">≤ 24 hours</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        Low
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">Best effort</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">5</span>
                    Certification & Governance Guarantees
                </h2>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl mb-6">
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>All certification actions are recorded in an <strong>append-only decision ledger</strong></li>
                        <li>Freeze, revocation, and restoration follow governance workflows</li>
                        <li>Two-person approval enforced where applicable</li>
                        <li>Jurisdiction and legal basis explicitly recorded</li>
                    </ul>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">6</span>
                    Exclusions & Limitations
                </h2>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-r-xl mb-6">
                    <p class="text-gray-700 leading-relaxed mb-3 font-semibold">This SLA does not guarantee:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Acceptance by external authorities</li>
                        <li>Legal enforceability outside the declared jurisdiction</li>
                        <li>Outcomes dependent on third-party decisions</li>
                    </ul>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-8"></div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold mr-4">7</span>
                    Custom Agreements
                </h2>
                <p class="text-gray-700 leading-relaxed">
                    Custom SLAs or contracts may supersede this document for specific entities.
                </p>

            </div>
        </div>

        <!-- Download PDF Button -->
        <div class="text-center mt-8">
            <a href="#" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download SLA (PDF)
            </a>
        </div>
    </div>
</div>
@endsection
