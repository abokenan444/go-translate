<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Certified Document Translation - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .certificate-preview {
            background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
            border: 3px solid #d4af37;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .ornate-border {
            border-image: repeating-linear-gradient(45deg, #d4af37, #d4af37 10px, #f4e4c1 10px, #f4e4c1 20px) 10;
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation (Simplified) -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-indigo-600">Cultural Translate</a>
                <div class="flex gap-4">
                    <a href="/login" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Login</a>
                    <a href="/register" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full mb-8 animate-pulse-slow">
                    <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6">
                    Certified Document Translation
                </h1>
                <p class="text-xl md:text-2xl text-indigo-100 max-w-4xl mx-auto mb-8">
                    Get your official documents professionally translated and certified with dual stamps, QR verification, and global acceptance. Trusted by embassies, universities, and government institutions worldwide.
                </p>
                <div class="flex flex-wrap gap-6 justify-center text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>100+ Languages</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Dual Stamp Certification</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>QR Code Verification</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>24-48 Hour Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10 pb-20">
        <!-- Upload Form Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Upload Your Document</h2>
                <p class="text-lg text-gray-600">Get started in just a few clicks</p>
            </div>
            
            <form id="certifiedTranslationForm" enctype="multipart/form-data" class="space-y-8">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <!-- Validation Errors -->
                <div id="validationErrors" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-bold text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul id="errorsList" class="list-disc list-inside space-y-1"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Document Type -->
                <div>
                    <label for="document_type" class="block text-sm font-semibold text-gray-700 mb-3">
                        Document Type <span class="text-red-500">*</span>
                    </label>
                    <select id="document_type" name="document_type" required 
                            class="w-full px-5 py-4 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">Select document type</option>
                        <option value="passport">Passport</option>
                        <option value="id_card">ID Card / National ID</option>
                        <option value="birth_certificate">Birth Certificate</option>
                        <option value="marriage_certificate">Marriage Certificate</option>
                        <option value="divorce_certificate">Divorce Certificate</option>
                        <option value="death_certificate">Death Certificate</option>
                        <option value="diploma">Diploma / Degree</option>
                        <option value="transcript">Academic Transcript</option>
                        <option value="certificate">Certificate</option>
                        <option value="contract">Contract / Agreement</option>
                        <option value="court_document">Court Document</option>
                        <option value="police_clearance">Police Clearance</option>
                        <option value="medical_report">Medical Report</option>
                        <option value="bank_statement">Bank Statement</option>
                        <option value="business_license">Business License</option>
                        <option value="power_of_attorney">Power of Attorney</option>
                        <option value="immigration_document">Immigration Document</option>
                        <option value="other">Other Official Document</option>
                    </select>
                </div>

                <!-- Language Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="source_language" class="block text-sm font-semibold text-gray-700 mb-3">
                            From Language <span class="text-red-500">*</span>
                        </label>
                        <select id="source_language" name="source_language" required 
                                class="w-full px-5 py-4 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="">Loading languages...</option>
                        </select>
                    </div>

                    <div>
                        <label for="target_language" class="block text-sm font-semibold text-gray-700 mb-3">
                            To Language <span class="text-red-500">*</span>
                        </label>
                        <select id="target_language" name="target_language" required 
                                class="w-full px-5 py-4 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="">Loading languages...</option>
                        </select>
                    </div>
                </div>

                <!-- Delivery Method -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        Delivery Method <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="relative flex flex-col p-6 border-3 border-gray-300 rounded-2xl cursor-pointer hover:border-indigo-500 hover:shadow-lg transition-all group">
                            <input type="radio" name="delivery_method" value="digital" checked class="sr-only peer">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-xl font-bold text-gray-900">Digital PDF</span>
                                        <p class="text-sm text-gray-500">Instant download</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold text-indigo-600">$29.99</span>
                            </div>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Delivered within 24-48 hours
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Dual stamp certification
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    QR code verification
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Printable high-quality PDF
                                </li>
                            </ul>
                            <div class="absolute inset-0 border-3 border-indigo-600 rounded-2xl opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            <div class="absolute top-4 right-4 w-6 h-6 bg-indigo-600 rounded-full items-center justify-center hidden peer-checked:flex">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </label>

                        <label class="relative flex flex-col p-6 border-3 border-gray-300 rounded-2xl cursor-pointer hover:border-indigo-500 hover:shadow-lg transition-all group">
                            <input type="radio" name="delivery_method" value="physical" class="sr-only peer">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-xl font-bold text-gray-900">Physical Copy</span>
                                        <p class="text-sm text-gray-500">Shipped by mail</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold text-indigo-600">$64.99</span>
                            </div>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Everything in Digital PDF
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Physical stamps on paper
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Worldwide shipping (5-10 days)
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tracking number included
                                </li>
                            </ul>
                            <div class="absolute inset-0 border-3 border-indigo-600 rounded-2xl opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            <div class="absolute top-4 right-4 w-6 h-6 bg-indigo-600 rounded-full items-center justify-center hidden peer-checked:flex">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Shipping Address (Hidden by default) -->
                <div id="shippingAddressSection" class="hidden space-y-6 p-8 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl border-2 border-purple-200">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900">Shipping Address</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <input type="text" name="shipping_address[name]" placeholder="Full Name *" 
                               class="px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <input type="text" name="shipping_address[phone]" placeholder="Phone Number *" 
                               class="px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    
                    <input type="text" name="shipping_address[street]" placeholder="Street Address *" 
                           class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <input type="text" name="shipping_address[city]" placeholder="City *" 
                               class="px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <input type="text" name="shipping_address[state]" placeholder="State/Province" 
                               class="px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <input type="text" name="shipping_address[zip]" placeholder="ZIP Code *" 
                               class="px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    
                    <input type="text" name="shipping_address[country]" placeholder="Country *" 
                           class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Upload Document <span class="text-red-500">*</span>
                    </label>
                    <div class="relative border-3 border-dashed border-gray-300 rounded-2xl p-12 text-center hover:border-indigo-500 hover:bg-indigo-50 transition-all cursor-pointer group">
                        <input id="file-upload" name="original_pdf" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="pointer-events-none">
                            <div class="mx-auto w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-medium text-gray-700 mb-2">Click to upload or drag and drop</p>
                            <p class="text-sm text-gray-500">PDF, JPG, PNG up to 10MB</p>
                            <p id="fileName" class="mt-4 text-sm font-semibold text-indigo-600"></p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" 
                        class="w-full flex justify-center items-center py-5 px-8 border border-transparent rounded-2xl shadow-lg text-xl font-bold text-white gradient-bg hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Get Certified Translation
                </button>
            </form>

            <!-- Success Message (Hidden by default) -->
            <div id="successMessage" class="hidden mt-10 p-8 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-2xl">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-green-800 mb-3">Translation Completed Successfully!</h3>
                        <p class="text-green-700 mb-6">Your certified translation is ready for download. You can also verify its authenticity using the QR code.</p>
                        <div class="flex flex-wrap gap-4">
                            <a id="downloadLink" href="#" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-md text-base font-semibold text-white bg-green-600 hover:bg-green-700 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Certified PDF
                            </a>
                            <a id="verifyLink" href="#" target="_blank" class="inline-flex items-center px-6 py-3 border-2 border-green-600 rounded-xl shadow-md text-base font-semibold text-green-700 bg-white hover:bg-green-50 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verify Document
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message (Hidden by default) -->
            <div id="errorMessage" class="hidden mt-10 p-8 bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-400 rounded-2xl">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-red-800 mb-2">Error Occurred</h3>
                        <p id="errorText" class="text-red-700"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Preview Section -->
        <div class="mb-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Certificate Preview</h2>
                <p class="text-lg text-gray-600">See what your certified translation will look like</p>
            </div>
            <div class="certificate-preview rounded-3xl p-12 max-w-4xl mx-auto">
                <div class="ornate-border p-8 bg-white rounded-2xl">
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="w-24 h-24 border-2 border-gray-300 rounded-lg flex items-center justify-center">
                                <div class="text-xs text-gray-400 text-center">
                                    QR Code<br/>Verification
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold text-center text-gray-900 mb-8">CERTIFICATE OF TRANSLATION ACCURACY</h3>
                    <div class="space-y-4 text-gray-700">
                        <div class="flex border-b pb-2">
                            <span class="font-semibold w-48">Document Title:</span>
                            <span class="flex-1 border-b border-dotted border-gray-400"></span>
                        </div>
                        <div class="flex border-b pb-2">
                            <span class="font-semibold w-48">Original Language:</span>
                            <span class="flex-1 border-b border-dotted border-gray-400"></span>
                        </div>
                        <div class="flex border-b pb-2">
                            <span class="font-semibold w-48">Translated Language:</span>
                            <span class="flex-1 border-b border-dotted border-gray-400"></span>
                        </div>
                        <div class="flex border-b pb-2">
                            <span class="font-semibold w-48">Date of Translation:</span>
                            <span class="flex-1 border-b border-dotted border-gray-400"></span>
                        </div>
                    </div>
                    <p class="my-8 text-center text-gray-700 italic">
                        "This is to certify that this translation is accurate and complete to the best of our knowledge and ability."
                    </p>
                    <div class="flex justify-between items-end mt-12">
                        <div class="text-center">
                            <div class="w-32 h-32 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center mb-2 shadow-lg">
                                <span class="text-xs font-bold text-white">Cultural<br/>Translate<br/>Seal</span>
                            </div>
                            <div class="border-t-2 border-gray-400 pt-2 text-sm">
                                <p class="font-semibold">Platform Stamp</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-2 shadow-lg">
                                <span class="text-xs font-bold text-white">Certified<br/>Partner<br/>Seal</span>
                            </div>
                            <div class="border-t-2 border-gray-400 pt-2 text-sm">
                                <p class="font-semibold">Partner Stamp</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-center text-sm text-gray-600">
                        <p>Certification Number: <span class="font-mono">CT-XXXX-XXXX-XXXX</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Dual Stamp Certification</h3>
                <p class="text-gray-600 leading-relaxed">
                    Every certified translation includes two official stamps: one from Cultural Translate and one from our certified partner. This dual certification ensures maximum acceptance by institutions worldwide.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Secure & Protected</h3>
                <p class="text-gray-600 leading-relaxed">
                    Bank-level encryption and secure document storage ensure your sensitive information remains confidential throughout the translation process.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">QR Code Verification</h3>
                <p class="text-gray-600 leading-relaxed">
                    Each certified document includes a unique QR code that links to our verification system. Institutions can instantly verify the authenticity and details.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer (Simplified) -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">© 2025 Cultural Translate. All rights reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load languages from API
        fetch('/api/languages')
            .then(response => response.json())
            .then(data => {
                const sourceSelect = document.getElementById('source_language');
                const targetSelect = document.getElementById('target_language');
                
                sourceSelect.innerHTML = '<option value="">Select language</option>';
                targetSelect.innerHTML = '<option value="">Select language</option>';
                
                if (data.success && data.data) {
                    // Flatten all regions into a single array
                    const allLanguages = [];
                    Object.values(data.data).forEach(regionLanguages => {
                        allLanguages.push(...regionLanguages);
                    });
                    
                    // Sort alphabetically by name
                    allLanguages.sort((a, b) => a.name.localeCompare(b.name));
                    
                    allLanguages.forEach(lang => {
                        const optionText = lang.name;
                        sourceSelect.innerHTML += `<option value="${lang.locale}">${optionText}</option>`;
                        targetSelect.innerHTML += `<option value="${lang.locale}">${optionText}</option>`;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading languages:', error);
                // Fallback to basic languages
                const basicLanguages = [
                    {code: 'en', name: 'English', native_name: 'English'},
                    {code: 'ar', name: 'Arabic', native_name: 'العربية'},
                    {code: 'es', name: 'Spanish', native_name: 'Español'},
                    {code: 'fr', name: 'French', native_name: 'Français'},
                    {code: 'de', name: 'German', native_name: 'Deutsch'}
                ];
                
                const sourceSelect = document.getElementById('source_language');
                const targetSelect = document.getElementById('target_language');
                
                sourceSelect.innerHTML = '<option value="">Select language</option>';
                targetSelect.innerHTML = '<option value="">Select language</option>';
                
                basicLanguages.forEach(lang => {
                    const optionText = `${lang.name} (${lang.native_name})`;
                    sourceSelect.innerHTML += `<option value="${lang.code}">${optionText}</option>`;
                    targetSelect.innerHTML += `<option value="${lang.code}">${optionText}</option>`;
                });
            });
        
        // Show/hide shipping address
        document.querySelectorAll('input[name="delivery_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const shippingSection = document.getElementById('shippingAddressSection');
                if (this.value === 'physical') {
                    shippingSection.classList.remove('hidden');
                    shippingSection.querySelectorAll('input').forEach(input => input.required = true);
                } else {
                    shippingSection.classList.add('hidden');
                    shippingSection.querySelectorAll('input').forEach(input => input.required = false);
                }
            });
        });
        
        // File upload display
        document.getElementById('file-upload').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('fileName').textContent = `✓ Selected: ${fileName}`;
            }
        });
        
        // Form submission
        document.getElementById('certifiedTranslationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            // Hide messages
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-6 w-6 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing Translation...';
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/translation/certified/generate', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('downloadLink').href = result.download_url;
                    document.getElementById('verifyLink').href = result.verification_url;
                    successMessage.classList.remove('hidden');
                    successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    this.reset();
                    document.getElementById('fileName').textContent = '';
                } else {
                    document.getElementById('errorText').textContent = result.error || 'An error occurred while processing your request. Please try again.';
                    errorMessage.classList.remove('hidden');
                    errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('errorText').textContent = 'Network error. Please check your connection and try again.';
                errorMessage.classList.remove('hidden');
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Get Certified Translation';
            }
        });
    });
    </script>
</body>
</html>
