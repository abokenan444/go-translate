@extends('layouts.app')

@section('title', 'ترجمة المستندات | Document Translation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50" x-data="fileTranslationApp()">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-2">ترجمة المستندات | Document Translation</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    ارفع مستنداتك واحصل على ترجمة احترافية مع الحفاظ على التنسيق
                    <br>Upload your documents and get professional translation with layout preservation
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الملفات<br>Total Files</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_files'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">مكتملة<br>Completed</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">قيد المعالجة<br>Processing</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['processing'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="animate-spin w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">فاشلة<br>Failed</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            
            <!-- Upload Section -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span>رفع مستند جديد | Upload New Document</span>
                </h2>

                <!-- File Drop Zone -->
                <div @drop.prevent="handleDrop($event)" 
                     @dragover.prevent 
                     @dragenter="dragover = true" 
                     @dragleave="dragover = false"
                     :class="{'border-blue-500 bg-blue-50': dragover}"
                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-colors cursor-pointer"
                     @click="$refs.fileInput.click()">
                    <input type="file" 
                           x-ref="fileInput" 
                           @change="handleFileSelect($event)" 
                           accept=".pdf,.jpg,.jpeg,.png,.docx"
                           class="hidden">
                    
                    <svg x-show="!selectedFile" class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    
                    <div x-show="!selectedFile">
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-semibold text-blue-600">انقر لرفع</span> أو اسحب وأفلت الملف هنا
                            <br><span class="font-semibold text-blue-600">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            PDF, DOCX, JPG, PNG (Max 10MB)
                        </p>
                    </div>

                    <div x-show="selectedFile" class="mt-4">
                        <div class="flex items-center justify-center space-x-3 rtl:space-x-reverse">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <div class="text-left rtl:text-right">
                                <p class="text-sm font-medium text-gray-900" x-text="selectedFile?.name"></p>
                                <p class="text-xs text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                            </div>
                        </div>
                        <button @click.stop="selectedFile = null" class="mt-2 text-sm text-red-600 hover:text-red-700">
                            ✕ إزالة | Remove
                        </button>
                    </div>
                </div>

                <!-- Language Selection -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            اللغة المصدر | Source Language
                        </label>
                        <select x-model="sourceLang" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="en">English | إنجليزي</option>
                            <option value="ar">Arabic | عربي</option>
                            <option value="es">Spanish | إسباني</option>
                            <option value="fr">French | فرنسي</option>
                            <option value="de">German | ألماني</option>
                            <option value="ja">Japanese | ياباني</option>
                            <option value="zh">Chinese | صيني</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            اللغة الهدف | Target Language
                        </label>
                        <select x-model="targetLang" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="ar">Arabic | عربي</option>
                            <option value="en">English | إنجليزي</option>
                            <option value="es">Spanish | إسباني</option>
                            <option value="fr">French | فرنسي</option>
                            <option value="de">German | ألماني</option>
                            <option value="ja">Japanese | ياباني</option>
                            <option value="zh">Chinese | صيني</option>
                        </select>
                    </div>
                </div>

                <!-- Options -->
                <div class="mt-6 space-y-3">
                    <label class="flex items-center space-x-3 rtl:space-x-reverse cursor-pointer">
                        <input type="checkbox" x-model="preserveLayout" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700">
                            الحفاظ على التنسيق الأصلي | Preserve original layout
                        </span>
                    </label>
                    <label class="flex items-center space-x-3 rtl:space-x-reverse cursor-pointer">
                        <input type="checkbox" x-model="culturalAdaptation" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700">
                            تكييف ثقافي تلقائي | Automatic cultural adaptation
                        </span>
                    </label>
                </div>

                <!-- Upload Button -->
                <button @click="uploadFile()" 
                        :disabled="!selectedFile || uploading"
                        class="mt-6 w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!uploading">
                        📤 رفع وترجمة | Upload & Translate
                    </span>
                    <span x-show="uploading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        جاري الرفع... | Uploading...
                    </span>
                </button>

                <!-- Upload Result -->
                <div x-show="uploadResult" class="mt-4 p-4 bg-green-50 border-2 border-green-300 rounded-lg">
                    <h4 class="font-semibold text-green-900 mb-2">✅ تم الرفع بنجاح! | Uploaded Successfully!</h4>
                    <p class="text-sm text-green-800" x-text="uploadResult"></p>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" class="mt-4 p-4 bg-red-50 border-2 border-red-300 rounded-lg">
                    <h4 class="font-semibold text-red-900 mb-2">❌ خطأ | Error:</h4>
                    <p class="text-sm text-red-800" x-text="errorMessage"></p>
                </div>
            </div>

            <!-- Recent Translations -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>الترجمات الأخيرة | Recent Translations</span>
                </h2>

                <div class="space-y-4 max-h-[600px] overflow-y-auto">
                    @forelse($recentTranslations as $translation)
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse mb-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $translation->original_filename }}</h3>
                                </div>
                                <div class="flex items-center space-x-4 rtl:space-x-reverse text-xs text-gray-600">
                                    <span>{{ strtoupper($translation->source_language) }} → {{ strtoupper($translation->target_language) }}</span>
                                    <span>{{ $translation->file_size_human }}</span>
                                    <span>{{ $translation->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 rtl:space-x-reverse ml-4">
                                @if($translation->status === 'completed')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        ✓ مكتمل | Completed
                                    </span>
                                    <a href="{{ route('dashboard.file-translation.download', $translation->id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700">
                                        ⬇ تحميل | Download
                                    </a>
                                @elseif($translation->status === 'processing')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        ⏳ معالجة | Processing
                                    </span>
                                @elseif($translation->status === 'failed')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        ✕ فشل | Failed
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        ⌛ معلق | Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-600">
                            لا توجد ترجمات بعد<br>
                            No translations yet
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fileTranslationApp() {
    return {
        selectedFile: null,
        sourceLang: 'en',
        targetLang: 'ar',
        preserveLayout: true,
        culturalAdaptation: true,
        uploading: false,
        dragover: false,
        uploadResult: '',
        errorMessage: '',

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.validateAndSetFile(file);
            }
        },

        handleDrop(event) {
            this.dragover = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.validateAndSetFile(file);
            }
        },

        validateAndSetFile(file) {
            // Check file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                this.errorMessage = 'حجم الملف كبير جداً (الحد الأقصى 10MB) | File size too large (max 10MB)';
                return;
            }

            // Check file type
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                this.errorMessage = 'نوع الملف غير مدعوم | Unsupported file type';
                return;
            }

            this.selectedFile = file;
            this.errorMessage = '';
        },

        formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unitIndex = 0;
            while (size >= 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex++;
            }
            return size.toFixed(2) + ' ' + units[unitIndex];
        },

        async uploadFile() {
            if (!this.selectedFile) {
                this.errorMessage = 'الرجاء اختيار ملف | Please select a file';
                return;
            }

            this.uploading = true;
            this.uploadResult = '';
            this.errorMessage = '';

            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('source_lang', this.sourceLang);
            formData.append('target_lang', this.targetLang);
            formData.append('preserve_layout', this.preserveLayout ? '1' : '0');
            formData.append('cultural_adaptation', this.culturalAdaptation ? '1' : '0');

            try {
                const response = await fetch('{{ route("dashboard.file-translation.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.uploadResult = `تم رفع "${data.translation.original_filename}" بنجاح! جاري المعالجة... | "${data.translation.original_filename}" uploaded successfully! Processing...`;
                    this.selectedFile = null;
                    
                    // Reload page after 2 seconds to show new file
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    this.errorMessage = data.message || 'حدث خطأ أثناء الرفع | Error during upload';
                }
            } catch (error) {
                this.errorMessage = 'خطأ في الاتصال | Connection error: ' + error.message;
            } finally {
                this.uploading = false;
            }
        }
    }
}
</script>
@endsection
