<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Official Document Translation</h2>
                <p class="text-indigo-100">Upload, translate, and certify your official documents with professional stamps</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-certificate text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-lg shadow-sm p-6" x-data="officialDocumentsUpload()">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-upload text-indigo-600 mr-2"></i>
            Upload Document
        </h3>
        
        <!-- File Upload Area -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-500 transition-colors cursor-pointer" 
             @click="$refs.fileInput.click()"
             @dragover.prevent="dragOver = true"
             @dragleave.prevent="dragOver = false"
             @drop.prevent="handleDrop($event)"
             :class="{'border-indigo-500 bg-indigo-50': dragOver}">
            
            <input type="file" 
                   x-ref="fileInput" 
                   @change="handleFileSelect($event)" 
                   accept=".pdf,.doc,.docx"
                   class="hidden">
            
            <div x-show="selectedFile === null">
                <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                <p class="text-lg text-gray-700 mb-2">Drag and drop your file here or click to browse</p>
                <p class="text-sm text-gray-500">Supported formats: PDF, DOC, DOCX</p>
            </div>
            
            <div x-show="selectedFile !== null" class="flex items-center justify-center space-x-4">
                <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                <div class="text-left">
                    <p class="text-lg font-semibold text-gray-900" x-text="selectedFile ? selectedFile.name : ''"></p>
                    <p class="text-sm text-gray-500" x-text="formatFileSize(selectedFile ? selectedFile.size : 0)"></p>
                </div>
                <button @click.stop="clearFile()" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times-circle text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Language Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6" x-show="selectedFile !== null">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Source Language
                </label>
                <select x-model="sourceLanguage" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="en">English</option>
                    <option value="ar">العربية</option>
                    <option value="fr">Français</option>
                    <option value="es">Español</option>
                    <option value="de">Deutsch</option>
                    <option value="it">Italiano</option>
                    <option value="pt">Português</option>
                    <option value="ru">Русский</option>
                    <option value="zh">中文</option>
                    <option value="ja">日本語</option>
                    <option value="ko">한국어</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Target Language
                </label>
                <select x-model="targetLanguage" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="ar">العربية</option>
                    <option value="en">English</option>
                    <option value="fr">Français</option>
                    <option value="es">Español</option>
                    <option value="de">Deutsch</option>
                    <option value="it">Italiano</option>
                    <option value="pt">Português</option>
                    <option value="ru">Русский</option>
                    <option value="zh">中文</option>
                    <option value="ja">日本語</option>
                    <option value="ko">한국어</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex space-x-4" x-show="selectedFile !== null">
            <button @click="estimateCost()" 
                    :disabled="uploading || estimating"
                    class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed font-semibold transition-colors">
                <span x-show="estimating === false">
                    <i class="fas fa-calculator mr-2"></i>
                    Estimate Cost
                </span>
                <span x-show="estimating === true">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Calculating...
                </span>
            </button>
        </div>

        <!-- Cost Estimation Result -->
        <div x-show="costEstimation !== null" class="mt-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
            <h4 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-receipt text-green-600 mr-2"></i>
                Cost Breakdown
            </h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Estimated Pages</p>
                    <p class="text-2xl font-bold text-indigo-600" x-text="costEstimation ? costEstimation.estimated_pages : '-'"></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Estimated Words</p>
                    <p class="text-2xl font-bold text-indigo-600" x-text="costEstimation ? costEstimation.estimated_words : '-'"></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Price per Word</p>
                    <p class="text-2xl font-bold text-indigo-600">$0.05</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Total Cost</p>
                    <p class="text-2xl font-bold text-green-600" x-text="costEstimation ? ('$' + costEstimation.estimated_cost) : '-'"></p>
                </div>
            </div>

            <button @click="proceedToPayment()" 
                    :disabled="processing"
                    class="w-full px-6 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed font-bold text-lg transition-colors">
                <i class="fas fa-credit-card mr-2"></i>
                Proceed to Payment
            </button>
        </div>

        <!-- Processing Status -->
        <div x-show="processing" class="mt-6 p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-center">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mr-4"></i>
                <div>
                    <p class="text-lg font-semibold text-blue-900" x-text="processingMessage"></p>
                    <p class="text-sm text-blue-700">Please wait...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents History -->
    <div class="bg-white rounded-lg shadow-sm p-6" x-data="officialDocumentsHistory()" x-init="loadDocuments()">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-history text-indigo-600 mr-2"></i>
            My Documents
        </h3>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-4xl text-indigo-600 mb-4"></i>
            <p class="text-gray-600">Loading documents...</p>
        </div>

        <!-- Empty State -->
        <div x-show="loading === false && documents.length === 0" class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-lg text-gray-600">No documents yet</p>
            <p class="text-sm text-gray-500">Upload your first document to get started</p>
        </div>

        <!-- Documents List -->
        <div x-show="loading === false && documents.length > 0" class="space-y-4">
            <template x-for="doc in documents" :key="doc.id">
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900" x-text="doc.original_filename"></h4>
                                    <p class="text-sm text-gray-500">
                                        <span x-text="doc.source_language"></span>
                                        <i class="fas fa-arrow-right mx-2"></i>
                                        <span x-text="doc.target_language"></span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6 text-sm text-gray-600 mt-3">
                                <div>
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span x-text="formatDate(doc.created_at)"></span>
                                </div>
                                <div>
                                    <i class="fas fa-barcode mr-1"></i>
                                    <span x-text="doc.certificate_id"></span>
                                </div>
                                <div>
                                    <i class="fas fa-dollar-sign mr-1"></i>
                                    <span x-text="'$' + doc.amount"></span>
                                </div>
                                <div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': doc.status === 'pending',
                                              'bg-blue-100 text-blue-800': doc.status === 'processing',
                                              'bg-green-100 text-green-800': doc.status === 'completed',
                                              'bg-red-100 text-red-800': doc.status === 'failed'
                                          }"
                                          x-text="doc.status.toUpperCase()">
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button x-show="doc.status === 'completed'" 
                                    @click="downloadDocument(doc.id)"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </button>
                            <button @click="viewDetails(doc.id)"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div x-show="loading === false && totalPages > 1" class="mt-6 flex justify-center space-x-2">
            <button @click="loadDocuments(currentPage - 1)" 
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <span class="px-4 py-2 text-gray-700">
                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
            </span>
            
            <button @click="loadDocuments(currentPage + 1)" 
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
function officialDocumentsUpload() {
    return {
        selectedFile: null,
        dragOver: false,
        sourceLanguage: 'en',
        targetLanguage: 'ar',
        uploading: false,
        estimating: false,
        processing: false,
        processingMessage: '',
        costEstimation: null,
        documentId: null,

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file;
                this.costEstimation = null;
            }
        },

        handleDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer.files[0];
            if (file && (file.type === 'application/pdf' || file.type.includes('word'))) {
                this.selectedFile = file;
                this.costEstimation = null;
            }
        },

        clearFile() {
            this.selectedFile = null;
            this.costEstimation = null;
            this.$refs.fileInput.value = '';
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '';
            const mb = bytes / (1024 * 1024);
            return mb.toFixed(2) + ' MB';
        },

        async estimateCost() {
            if (this.selectedFile === null) return;

            this.estimating = true;
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('source_language', this.sourceLanguage);
            formData.append('target_language', this.targetLanguage);

            try {
                const response = await fetch('/api/official-documents/estimate', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    this.costEstimation = data;
                    this.documentId = data.document_id;
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { type: 'success', message: 'Cost estimated successfully!' }
                    }));
                } else {
                    throw new Error(data.message || 'Estimation failed');
                }
            } catch (error) {
                console.error('Estimation error:', error);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { type: 'error', message: error.message }
                }));
            } finally {
                this.estimating = false;
            }
        },

        async proceedToPayment() {
            if (this.documentId === null) return;

            this.processing = true;
            this.processingMessage = 'Redirecting to payment...';

            try {
                const response = await fetch('/api/official-documents/create-payment', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        document_id: this.documentId
                    })
                });

                const data = await response.json();
                
                if (data.success && data.checkout_url) {
                    window.location.href = data.checkout_url;
                } else {
                    throw new Error(data.message || 'Payment creation failed');
                }
            } catch (error) {
                console.error('Payment error:', error);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { type: 'error', message: error.message }
                }));
                this.processing = false;
            }
        }
    }
}

function officialDocumentsHistory() {
    return {
        documents: [],
        loading: false,
        currentPage: 1,
        totalPages: 1,

        async loadDocuments(page = 1) {
            this.loading = true;
            this.currentPage = page;

            try {
                const response = await fetch('/api/official-documents/my-documents?page=' + page, {
                    headers: {
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    this.documents = data.documents;
                    this.totalPages = data.total_pages || 1;
                }
            } catch (error) {
                console.error('Load documents error:', error);
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        },

        async downloadDocument(documentId) {
            try {
                window.location.href = '/api/official-documents/download/' + documentId;
            } catch (error) {
                console.error('Download error:', error);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'Download failed' }
                }));
            }
        },

        viewDetails(documentId) {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { type: 'info', message: 'Document details: ID ' + documentId }
            }));
        }
    }
}
</script>
