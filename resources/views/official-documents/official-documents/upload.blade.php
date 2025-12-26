@extends('layouts.app')

@section('title', 'Upload Official Document')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center text-blue-600">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-blue-600 text-white font-bold">1</div>
                        <div class="ml-2 text-sm font-medium">Upload</div>
                    </div>
                    <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                    <div class="flex items-center text-gray-400">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-gray-300 text-gray-600 font-bold">2</div>
                        <div class="ml-2 text-sm font-medium">Payment</div>
                    </div>
                    <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                    <div class="flex items-center text-gray-400">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-gray-300 text-gray-600 font-bold">3</div>
                        <div class="ml-2 text-sm font-medium">Processing</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-2 text-gray-900">Upload Official Document</h1>
            <p class="text-gray-600 mb-8">Please provide your document details and upload the PDF file</p>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <p class="font-medium mb-2">Please correct the following errors:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('official.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Document Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Document Type <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type" required 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('document_type') border-red-500 @enderror">
                        <option value="">Select document type...</option>
                        @foreach($documentTypes as $key => $name)
                            <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('document_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Source Language -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Source Language (Original) <span class="text-red-500">*</span>
                    </label>
                    <select name="source_language" required 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('source_language') border-red-500 @enderror">
                        <option value="">Select source language...</option>
                        @foreach($languages as $code => $name)
                            <option value="{{ $code }}" {{ old('source_language') == $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('source_language')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Language -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Target Language (Translation) <span class="text-red-500">*</span>
                    </label>
                    <select name="target_language" required 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('target_language') border-red-500 @enderror">
                        <option value="">Select target language...</option>
                        @foreach($languages as $code => $name)
                            <option value="{{ $code }}" {{ old('target_language') == $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('target_language')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload PDF Document <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition @error('document') border-red-500 @enderror">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="document" type="file" accept=".pdf" required class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF up to 20MB</p>
                        </div>
                    </div>
                    @error('document')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Your document will be processed securely</li>
                                    <li>Price: €10.00 per document</li>
                                    <li>Processing time: 24-48 hours</li>
                                    <li>You'll receive a certified PDF with official seal and QR code</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('official.documents.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        ← Back
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                        Continue to Payment →
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>🔒 Your documents are encrypted and stored securely. We never share your information.</p>
        </div>
    </div>
</div>

<script>
    // File upload preview
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const label = document.querySelector('label[for="file-upload"] span');
            label.textContent = fileName;
        }
    });
</script>
@endsection
