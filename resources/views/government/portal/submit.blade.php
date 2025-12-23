@extends('layouts.app')

@section('title', 'Submit Document - ' . $portal->country_name . ' Portal')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="text-sm text-blue-200 mb-2">
                <a href="{{ route('gov.directory') }}" class="hover:text-white">Portals</a>
                <span class="mx-2">›</span>
                <a href="{{ route('gov.portal.index', ['country' => strtolower($portal->country_code)]) }}" class="hover:text-white">{{ $portal->country_name }}</a>
                <span class="mx-2">›</span>
                <span>Submit Document</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold">Submit Official Document</h1>
        </div>
    </div>

    {{-- Form --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form action="{{ route('gov.portal.submit.post', ['country' => strtolower($portal->country_code)]) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="divide-y divide-gray-200"
                  x-data="documentSubmitForm()">
                @csrf

                {{-- Document Information --}}
                <div class="p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Document Information</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Document Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                            <select name="document_type" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    x-model="documentType">
                                <option value="">Select type...</option>
                                <option value="birth_certificate">Birth Certificate</option>
                                <option value="marriage_certificate">Marriage Certificate</option>
                                <option value="death_certificate">Death Certificate</option>
                                <option value="divorce_decree">Divorce Decree</option>
                                <option value="passport">Passport</option>
                                <option value="drivers_license">Driver's License</option>
                                <option value="academic_transcript">Academic Transcript</option>
                                <option value="diploma_degree">Diploma/Degree</option>
                                <option value="court_document">Court Document</option>
                                <option value="medical_record">Medical Record</option>
                                <option value="power_of_attorney">Power of Attorney</option>
                                <option value="contract">Contract/Agreement</option>
                                <option value="corporate_document">Corporate Document</option>
                                <option value="immigration_document">Immigration Document</option>
                                <option value="other">Other</option>
                            </select>
                            @error('document_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Certification Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Certification Required *</label>
                            <select name="certification_type" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    x-model="certificationType">
                                <option value="certified">Certified Translation</option>
                                @if($portal->requires_notarization)
                                    <option value="notarized">Notarized Translation</option>
                                @endif
                                @if($portal->requires_apostille)
                                    <option value="apostille">Apostille Required</option>
                                @endif
                            </select>
                            @error('certification_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Source Language --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Source Language *</label>
                            <select name="source_language" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    x-model="sourceLanguage">
                                <option value="">Document's current language...</option>
                                @foreach($languages ?? [] as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('source_language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Target Language --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Language *</label>
                            <select name="target_language" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    x-model="targetLanguage">
                                <option value="">Translate to...</option>
                                @if($portal->supported_languages)
                                    @foreach($portal->supported_languages as $lang)
                                        <option value="{{ $lang }}" @if($lang === $portal->default_language) selected @endif>
                                            {{ strtoupper($lang) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('target_language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- File Upload --}}
                <div class="p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Upload Document</h2>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                         x-on:dragover.prevent="dragging = true"
                         x-on:dragleave.prevent="dragging = false"
                         x-on:drop.prevent="handleDrop($event)"
                         :class="{ 'border-blue-500 bg-blue-50': dragging }">
                        
                        <input type="file" 
                               name="document" 
                               id="document" 
                               required
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.tiff"
                               class="hidden"
                               x-on:change="handleFileSelect($event)">
                        
                        <template x-if="!fileName">
                            <div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mt-4 text-gray-600">
                                    <label for="document" class="cursor-pointer text-blue-600 hover:underline font-medium">
                                        Click to upload
                                    </label>
                                    or drag and drop
                                </p>
                                <p class="mt-2 text-sm text-gray-500">PDF, DOC, DOCX, JPG, PNG, TIFF (max 25MB)</p>
                            </div>
                        </template>
                        
                        <template x-if="fileName">
                            <div class="flex items-center justify-center space-x-4">
                                <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div class="text-left">
                                    <p class="font-medium text-gray-900" x-text="fileName"></p>
                                    <p class="text-sm text-gray-500" x-text="fileSize"></p>
                                </div>
                                <button type="button" x-on:click="clearFile()" class="text-red-500 hover:text-red-700">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @error('document')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Additional Information --}}
                <div class="p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Additional Information</h2>
                    
                    <div class="space-y-6">
                        {{-- Purpose --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purpose of Translation</label>
                            <select name="purpose" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select purpose (optional)...</option>
                                <option value="immigration">Immigration</option>
                                <option value="education">Education/Academic</option>
                                <option value="legal">Legal Proceedings</option>
                                <option value="employment">Employment</option>
                                <option value="business">Business/Corporate</option>
                                <option value="medical">Medical</option>
                                <option value="personal">Personal Use</option>
                            </select>
                        </div>

                        {{-- Deadline --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deadline (if any)</label>
                            <input type="date" 
                                   name="deadline"
                                   min="{{ now()->addDays(2)->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Minimum 2 business days from today</p>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                            <textarea name="notes" 
                                      rows="3"
                                      placeholder="Any special requirements or instructions..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Contact Information</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" 
                                   id="contact_name"
                                   name="contact_name" 
                                   autocomplete="name"
                                   required
                                   value="{{ auth()->user()?->name }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" 
                                   id="contact_email"
                                   name="contact_email" 
                                   autocomplete="email"
                                   required
                                   value="{{ auth()->user()?->email }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" 
                                   id="contact_phone"
                                   name="contact_phone"
                                   autocomplete="tel"
                                   value="{{ auth()->user()?->phone }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Price Estimate --}}
                <div class="p-6 md:p-8 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Estimated Price</h3>
                            <p class="text-sm text-gray-500">Final price may vary based on document complexity</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-blue-600" x-text="estimatedPrice">--</p>
                            <p class="text-sm text-gray-500">{{ $portal->currency_code }}</p>
                        </div>
                    </div>
                </div>

                {{-- Terms & Submit --}}
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="agree_terms" 
                                   required
                                   class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-600">
                                I confirm that the document is authentic and I agree to the 
                                <a href="/terms" class="text-blue-600 hover:underline">Terms of Service</a> and 
                                <a href="/privacy" class="text-blue-600 hover:underline">Privacy Policy</a>.
                            </span>
                        </label>
                        @error('agree_terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('gov.portal.index', ['country' => strtolower($portal->country_code)]) }}" 
                           class="text-gray-600 hover:text-gray-900">
                            ← Back to Portal
                        </a>
                        
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                                :disabled="submitting">
                            <span x-show="!submitting">Submit Document</span>
                            <span x-show="submitting" class="flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function documentSubmitForm() {
    return {
        documentType: '',
        certificationType: 'certified',
        sourceLanguage: '',
        targetLanguage: '{{ $portal->default_language }}',
        fileName: '',
        fileSize: '',
        dragging: false,
        submitting: false,
        estimatedPrice: '--',
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.setFile(file);
            }
        },
        
        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.setFile(file);
                document.getElementById('document').files = event.dataTransfer.files;
            }
        },
        
        setFile(file) {
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);
            this.updateEstimate();
        },
        
        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            document.getElementById('document').value = '';
            this.estimatedPrice = '--';
        },
        
        formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },
        
        async updateEstimate() {
            if (!this.documentType || !this.sourceLanguage || !this.targetLanguage || !this.fileName) {
                return;
            }
            
            try {
                const response = await fetch('{{ route('gov.portal.estimate', ['country' => strtolower($portal->country_code)]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        document_type: this.documentType,
                        certification_type: this.certificationType,
                        source_language: this.sourceLanguage,
                        target_language: this.targetLanguage
                    })
                });
                
                const data = await response.json();
                if (data.estimate) {
                    this.estimatedPrice = data.estimate.formatted;
                }
            } catch (e) {
                console.error('Estimate error:', e);
            }
        }
    }
}
</script>
@endpush
@endsection
