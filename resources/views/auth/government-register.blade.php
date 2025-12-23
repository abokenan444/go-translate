@extends('layouts.app')

@section('title', 'Government Account Registration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600/20 rounded-full mb-4">
                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Government Account Registration</h1>
            <p class="text-gray-400">تسجيل حساب جهة حكومية</p>
        </div>

        {{-- Important Notice --}}
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-amber-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-amber-400 mb-1">Verification Required / التحقق مطلوب</h3>
                    <p class="text-amber-200/80 text-sm">
                        Government accounts require manual verification by our team. This process typically takes 2-5 business days.
                        <br>
                        <span class="text-amber-200/60">تتطلب الحسابات الحكومية التحقق اليدوي من فريقنا. تستغرق هذه العملية عادة 2-5 أيام عمل.</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Registration Form --}}
        <form id="govRegistrationForm" class="space-y-8" enctype="multipart/form-data">
            @csrf

            {{-- Entity Information --}}
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm mr-3">1</span>
                    Entity Information / معلومات الجهة
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="entity_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Official Entity Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="entity_name" name="entity_name" autocomplete="organization" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="e.g., Ministry of Foreign Affairs">
                    </div>

                    <div>
                        <label for="entity_name_local" class="block text-sm font-medium text-gray-300 mb-2">
                            Local Name (Optional)
                        </label>
                        <input type="text" id="entity_name_local" name="entity_name_local" autocomplete="off"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="مثال: وزارة الخارجية">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Entity Type <span class="text-red-400">*</span>
                        </label>
                        <select name="entity_type" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Select Type / اختر النوع</option>
                            @foreach($entityTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Country <span class="text-red-400">*</span>
                        </label>
                        <select name="country_code" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Select Country / اختر الدولة</option>
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Department (Optional)
                        </label>
                        <input type="text" name="department"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="e.g., Translation Department">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Official Website
                        </label>
                        <input type="url" name="official_website"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="https://www.example.gov">
                    </div>
                </div>
            </div>

            {{-- Contact Person --}}
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm mr-3">2</span>
                    Contact Person / الشخص المسؤول
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="contact_name" name="contact_name" autocomplete="name" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Your full name">
                    </div>

                    <div>
                        <label for="contact_position" class="block text-sm font-medium text-gray-300 mb-2">
                            Position / Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="contact_position" name="contact_position" autocomplete="organization-title" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="e.g., Director of Translation">
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Official Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="contact_email" name="contact_email" autocomplete="email" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="name@ministry.gov.xx">
                        <p class="text-xs text-gray-500 mt-1">
                            Must be an official government email (e.g., .gov, .gov.xx)
                        </p>
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" id="contact_phone" name="contact_phone" autocomplete="tel"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="+1 234 567 8900">
                    </div>

                    <div class="md:col-span-2">
                        <label for="employee_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Employee ID (Optional)
                        </label>
                        <input type="text" id="employee_id" name="employee_id" autocomplete="off"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Your employee/badge number">
                    </div>
                </div>
            </div>

            {{-- Supporting Documents --}}
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm mr-3">3</span>
                    Supporting Documents / المستندات الداعمة
                </h2>

                <p class="text-gray-400 text-sm mb-6">
                    Upload at least one official document to verify your identity and authorization.
                    <br>
                    <span class="text-gray-500">ارفع مستندًا رسميًا واحدًا على الأقل للتحقق من هويتك وتفويضك.</span>
                </p>

                <div id="documentsContainer" class="space-y-4">
                    <div class="document-row bg-white/5 rounded-xl p-4 border border-white/10">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Document Type</label>
                                <select name="documents[0][type]" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-blue-500">
                                    @foreach($documentTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-2">File</label>
                                <input type="file" name="documents[0][file]" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addDocument()" 
                    class="mt-4 text-blue-400 hover:text-blue-300 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Another Document / إضافة مستند آخر
                </button>
            </div>

            {{-- Legal Disclaimer --}}
            <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Legal Disclaimer / إقرار قانوني
                </h2>

                <div class="bg-black/20 rounded-xl p-4 mb-4 text-sm text-gray-300 max-h-40 overflow-y-auto">
                    <p class="mb-3">
                        <strong>I hereby declare under legal responsibility that:</strong>
                    </p>
                    <ol class="list-decimal list-inside space-y-2 text-gray-400">
                        <li>I am duly authorized to represent the government entity named above.</li>
                        <li>All information provided in this application is accurate and truthful.</li>
                        <li>All documents submitted are authentic and valid.</li>
                        <li>I understand that any false representation constitutes fraud and may be prosecuted under applicable laws.</li>
                        <li>I agree to notify Cultural Translate immediately of any changes to my authorization status.</li>
                    </ol>
                    <hr class="my-4 border-white/10">
                    <p class="mb-3">
                        <strong>أقر بموجب هذا وتحت المسؤولية القانونية بما يلي:</strong>
                    </p>
                    <ol class="list-decimal list-inside space-y-2 text-gray-400" dir="rtl">
                        <li>أنا مخول رسمياً بتمثيل الجهة الحكومية المذكورة أعلاه.</li>
                        <li>جميع المعلومات المقدمة في هذا الطلب صحيحة ودقيقة.</li>
                        <li>جميع المستندات المقدمة أصلية وسارية المفعول.</li>
                        <li>أدرك أن أي تمثيل كاذب يشكل احتيالاً وقد يُلاحق قضائياً.</li>
                        <li>أوافق على إخطار Cultural Translate فوراً بأي تغييرات في حالة تفويضي.</li>
                    </ol>
                </div>

                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="legal_disclaimer_accepted" required
                        class="w-5 h-5 mt-0.5 rounded border-white/30 bg-white/10 text-blue-600 focus:ring-blue-500">
                    <span class="ml-3 text-white">
                        I have read, understood, and agree to the above legal disclaimer.
                        <br>
                        <span class="text-gray-400 text-sm">لقد قرأت وفهمت وأوافق على الإقرار القانوني أعلاه.</span>
                    </span>
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-center">
                <button type="submit" id="submitBtn"
                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submit Verification Request / إرسال طلب التحقق
                </button>
            </div>
        </form>

        {{-- Success Modal --}}
        <div id="successModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-slate-800 rounded-2xl p-8 max-w-md mx-4 text-center">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Request Submitted!</h3>
                <p class="text-gray-400 mb-4">Your verification request has been submitted successfully. Our team will review your application within 2-5 business days.</p>
                <p class="text-sm text-gray-500 mb-6">Reference ID: <span id="referenceId" class="text-blue-400 font-mono"></span></p>
                <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let documentCount = 1;

function addDocument() {
    const container = document.getElementById('documentsContainer');
    const html = `
        <div class="document-row bg-white/5 rounded-xl p-4 border border-white/10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Document Type</label>
                    <select name="documents[${documentCount}][type]" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-blue-500">
                        @foreach($documentTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-300 mb-2">File</label>
                        <input type="file" name="documents[${documentCount}][file]" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    </div>
                    <button type="button" onclick="this.closest('.document-row').remove()" class="self-end px-3 py-3 text-red-400 hover:text-red-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    documentCount++;
}

document.getElementById('govRegistrationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('submitBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Submitting...';
    btn.disabled = true;

    try {
        const formData = new FormData(this);
        const response = await fetch('{{ route("government.register.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('referenceId').textContent = data.reference_id;
            document.getElementById('successModal').classList.remove('hidden');
        } else {
            alert(data.message || 'An error occurred. Please try again.');
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
        console.error(error);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
</script>
@endpush
@endsection
