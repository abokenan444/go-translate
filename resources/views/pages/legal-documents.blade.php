@extends('layouts.app')

@section('title', 'Legal Document Translation - CulturalTranslate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                <i class="fas fa-gavel text-indigo-600 mr-3"></i>
                Legal Document Translation
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Professional translation for legal documents with certified accuracy. 
                Pay per document - no subscription required.
            </p>
        </div>

        <!-- Pricing Info -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="grid md:grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-indigo-600 mb-2">${{ number_format(5, 2) }}</div>
                    <div class="text-gray-600">Per Page</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600 mb-2"><i class="fas fa-check-circle"></i></div>
                    <div class="text-gray-600">Certified Translation</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-purple-600 mb-2"><i class="fas fa-clock"></i></div>
                    <div class="text-gray-600">24-48 Hours</div>
                </div>
            </div>
        </div>

        <!-- Upload Section -->
        <div x-data="legalDocumentUploader()" class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Upload Your Document</h2>
            
            <form @submit.prevent="uploadDocument" class="space-y-6">
                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Document File (PDF, DOC, DOCX)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only" 
                                           @change="handleFileSelect" 
                                           accept=".pdf,.doc,.docx">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 20MB</p>
                            <p x-show="fileName" x-text="fileName" class="text-sm font-medium text-indigo-600 mt-2"></p>
                        </div>
                    </div>
                </div>

                <!-- Languages -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Source Language</label>
                        <select x-model="sourceLanguage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="en">English</option>
                            <option value="ar">Arabic</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <option value="de">German</option>
                            <option value="nl">Dutch</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Language</label>
                        <select x-model="targetLanguage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="ar">Arabic</option>
                            <option value="en">English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <option value="de">German</option>
                            <option value="nl">Dutch</option>
                        </select>
                    </div>
                </div>

                <!-- Document Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Type (Optional)</label>
                    <select x-model="documentType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select type...</option>
                        <option value="contract">Contract</option>
                        <option value="agreement">Agreement</option>
                        <option value="certificate">Certificate</option>
                        <option value="court_document">Court Document</option>
                        <option value="patent">Patent</option>
                        <option value="trademark">Trademark</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        :disabled="uploading || !file"
                        class="w-full bg-indigo-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span x-show="!uploading">
                        <i class="fas fa-upload mr-2"></i>
                        Upload & Get Quote
                    </span>
                    <span x-show="uploading">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Uploading...
                    </span>
                </button>
            </form>

            <!-- Cost Estimate -->
            <div x-show="estimate" x-cloak class="mt-8 p-6 bg-indigo-50 rounded-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Cost Estimate</h3>
                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <div class="text-sm text-gray-600">Pages</div>
                        <div class="text-2xl font-bold text-gray-900" x-text="estimate.pages"></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Words</div>
                        <div class="text-2xl font-bold text-gray-900" x-text="estimate.words"></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Total Cost</div>
                        <div class="text-2xl font-bold text-indigo-600">$<span x-text="estimate.cost.toFixed(2)"></span></div>
                    </div>
                </div>
                <button @click="proceedToPayment" 
                        class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    <i class="fas fa-credit-card mr-2"></i>
                    Proceed to Payment
                </button>
            </div>
        </div>

        <!-- Documents History -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Documents</h2>
            
            @if($documents->count() > 0)
                <div class="space-y-4">
                    @foreach($documents as $doc)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $doc->document_name }}</h3>
                                <div class="text-sm text-gray-600 mt-1">
                                    {{ strtoupper($doc->source_language) }} → {{ strtoupper($doc->target_language) }} 
                                    • {{ $doc->pages_count }} pages 
                                    • ${{ number_format($doc->payment_amount, 2) }}
                                </div>
                                <div class="mt-2">
                                    @if($doc->status === 'completed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Completed
                                        </span>
                                    @elseif($doc->status === 'processing')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Processing
                                        </span>
                                    @elseif($doc->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Pending Payment
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Failed
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($doc->status === 'completed')
                                    <a href="{{ route('legal-documents.download', $doc->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                        <i class="fas fa-download mr-2"></i>
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-file-alt text-6xl mb-4 opacity-30"></i>
                    <p>No documents yet. Upload your first legal document above.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
function legalDocumentUploader() {
    return {
        file: null,
        fileName: '',
        sourceLanguage: 'en',
        targetLanguage: 'ar',
        documentType: '',
        uploading: false,
        estimate: null,
        documentId: null,
        stripe: Stripe('{{ env("STRIPE_KEY") }}'),
        
        handleFileSelect(event) {
            this.file = event.target.files[0];
            this.fileName = this.file ? this.file.name : '';
        },
        
        async uploadDocument() {
            if (!this.file) return;
            
            this.uploading = true;
            const formData = new FormData();
            formData.append('document', this.file);
            formData.append('source_language', this.sourceLanguage);
            formData.append('target_language', this.targetLanguage);
            formData.append('document_type', this.documentType);
            
            try {
                const response = await fetch('/legal-documents/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.estimate = data;
                    this.documentId = data.document_id;
                } else {
                    alert('Upload failed: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('Upload failed: ' + error.message);
            } finally {
                this.uploading = false;
            }
        },
        
        async proceedToPayment() {
            if (!this.documentId) return;
            
            try {
                // Create payment intent
                const response = await fetch(`/legal-documents/${this.documentId}/payment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to Stripe Checkout
                    const {error} = await this.stripe.confirmCardPayment(data.client_secret);
                    
                    if (error) {
                        alert('Payment failed: ' + error.message);
                    } else {
                        // Confirm payment on server
                        await fetch(`/legal-documents/${this.documentId}/confirm`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                        });
                        
                        alert('Payment successful! Your document is being translated.');
                        window.location.reload();
                    }
                } else {
                    alert('Payment setup failed: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('Payment failed: ' + error.message);
            }
        }
    };
}
</script>
@endsection
