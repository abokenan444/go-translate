@extends('layouts.app')

@section('title', 'Government Account Registration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Government Account Registration</h1>
            <p class="text-lg text-gray-600">Verification Required - Not Instant Access</p>
        </div>

        <!-- Warning Alert -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Government accounts require manual verification (2-5 business days)</li>
                            <li>You must use an official government email address</li>
                            <li>At least one supporting document is required</li>
                            <li>No access is granted until verification is complete</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <form id="governmentRegistrationForm" method="POST" action="{{ route('government.register.submit') }}" enctype="multipart/form-data">
                @csrf

                <!-- Entity Information -->
                <div class="px-8 py-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">🏛️ Government Entity Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Official Entity Name *</label>
                            <input type="text" name="entity_name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., Ministry of Foreign Affairs">
                            <p class="mt-1 text-xs text-gray-500">The official name of your government entity</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Entity Type *</label>
                            <select name="entity_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Type</option>
                                <option value="ministry">Ministry / وزارة</option>
                                <option value="embassy">Embassy / سفارة</option>
                                <option value="consulate">Consulate / قنصلية</option>
                                <option value="municipality">Municipality / بلدية</option>
                                <option value="agency">Agency / هيئة</option>
                                <option value="department">Department / إدارة</option>
                                <option value="court">Court / محكمة</option>
                                <option value="parliament">Parliament / برلمان</option>
                                <option value="other">Other / أخرى</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                            <select name="country_code" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Country</option>
                                <option value="SA">Saudi Arabia / المملكة العربية السعودية</option>
                                <option value="AE">United Arab Emirates / الإمارات العربية المتحدة</option>
                                <option value="EG">Egypt / مصر</option>
                                <option value="JO">Jordan / الأردن</option>
                                <option value="US">United States</option>
                                <option value="GB">United Kingdom</option>
                                <option value="FR">France</option>
                                <option value="DE">Germany</option>
                                <!-- Add more countries -->
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department (Optional)</label>
                            <input type="text" name="department"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="e.g., Consular Affairs Department">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Official Website (Optional)</label>
                            <input type="url" name="official_website"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="https://ministry.gov.xx">
                        </div>
                    </div>
                </div>

                <!-- Contact Person -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">👤 Contact Person Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="contact_name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Position/Title *</label>
                            <input type="text" name="contact_position" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="e.g., Director of Translation Services">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Official Email Address *</label>
                            <input type="email" name="contact_email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="name@ministry.gov.xx">
                            <p class="mt-1 text-xs text-red-600">⚠️ Must be an official government domain (.gov, .ministry., etc.)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number (Optional)</label>
                            <input type="tel" name="contact_phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="+1 (555) 123-4567">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID (Optional)</label>
                            <input type="text" name="employee_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Your employee or staff ID number">
                        </div>
                    </div>
                </div>

                <!-- Supporting Documents -->
                <div class="px-8 py-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">📎 Supporting Documents *</h2>
                    <p class="text-sm text-gray-600 mb-6">Please provide at least ONE of the following documents:</p>
                    
                    <div class="space-y-4" id="documentsContainer">
                        <div class="document-item p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                                    <select name="documents[0][type]" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                        <option value="official_id">Official Government ID</option>
                                        <option value="authorization_letter">Authorization Letter</option>
                                        <option value="business_card">Business Card</option>
                                        <option value="appointment_letter">Appointment Letter</option>
                                        <option value="official_website_proof">Screenshot of Official Website</option>
                                        <option value="mou_agreement">MoU or Agreement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (Max 10MB)</label>
                                    <input type="file" name="documents[0][file]" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addDocumentField()" 
                        class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        + Add Another Document
                    </button>
                </div>

                <!-- Legal Disclaimer -->
                <div class="px-8 py-6 bg-red-50 border-b border-red-200">
                    <h2 class="text-2xl font-semibold text-red-900 mb-4">⚖️ Legal Declaration</h2>
                    
                    <div class="bg-white p-6 rounded-lg border-2 border-red-300 mb-4">
                        <p class="text-sm text-gray-800 leading-relaxed mb-4">
                            <strong class="text-red-600">I declare under legal responsibility that:</strong>
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-sm text-gray-700">
                            <li>I am an authorized representative of the government entity mentioned above</li>
                            <li>All information provided is accurate and truthful</li>
                            <li>I have the legal authority to register this entity on this platform</li>
                            <li>Any false representation constitutes fraud and may be prosecuted</li>
                            <li>I understand that verification may take 2-5 business days</li>
                            <li>No access is granted until verification is complete</li>
                        </ul>
                    </div>

                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" name="legal_disclaimer_accepted" required value="1"
                            class="mt-1 w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="text-sm text-gray-900 font-medium">
                            I have read and accept the legal declaration above. I understand the consequences of providing false information. *
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="px-8 py-6 bg-gray-50">
                    <button type="submit"
                        class="w-full px-6 py-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition">
                        Submit for Verification
                    </button>
                    <p class="mt-4 text-center text-sm text-gray-600">
                        By submitting this form, your application will be reviewed by our team within 2-5 business days.
                        You will receive an email notification once your account is verified.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let documentCount = 1;

function addDocumentField() {
    const container = document.getElementById('documentsContainer');
    const newDoc = document.createElement('div');
    newDoc.className = 'document-item p-4 bg-gray-50 rounded-lg border border-gray-200';
    newDoc.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                <select name="documents[${documentCount}][type]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="official_id">Official Government ID</option>
                    <option value="authorization_letter">Authorization Letter</option>
                    <option value="business_card">Business Card</option>
                    <option value="appointment_letter">Appointment Letter</option>
                    <option value="official_website_proof">Screenshot of Official Website</option>
                    <option value="mou_agreement">MoU or Agreement</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (Max 10MB)</label>
                <input type="file" name="documents[${documentCount}][file]" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" 
            class="mt-2 text-sm text-red-600 hover:text-red-800">Remove</button>
    `;
    container.appendChild(newDoc);
    documentCount++;
}
</script>
@endsection
