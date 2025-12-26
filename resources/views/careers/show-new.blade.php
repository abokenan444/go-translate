@extends('layouts.app')

@section('title', $job->title . ' - Careers')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Back Button -->
        <a href="{{ route('careers.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Careers
        </a>

        <!-- Job Header -->
        <div class="bg-white rounded-xl p-8 shadow-md mb-6">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $job->title }}</h1>
            
            <div class="flex flex-wrap gap-3 mb-4">
                @if($job->department)
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full">{{ $job->department }}</span>
                @endif
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full">{{ $job->location }}</span>
                <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full">{{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</span>
                @if($job->salary_range)
                    <span class="px-4 py-2 bg-green-600 text-white rounded-full">{{ $job->salary_range }}</span>
                @endif
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <section class="bg-white rounded-xl p-8 shadow-md">
                    <h2 class="text-2xl font-bold mb-4">About This Role</h2>
                    <div class="text-gray-700">
                        {{ $job->description }}
                    </div>
                </section>

                <!-- Responsibilities -->
                @if($job->responsibilities)
                <section class="bg-white rounded-xl p-8 shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Key Responsibilities</h2>
                    @php
                        $responsibilities = is_string($job->responsibilities) ? json_decode($job->responsibilities, true) : $job->responsibilities;
                    @endphp
                    @if(is_array($responsibilities))
                        <ul class="list-disc pl-5 space-y-2 text-gray-700">
                            @foreach($responsibilities as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-700">{{ $job->responsibilities }}</div>
                    @endif
                </section>
                @endif

                <!-- Requirements -->
                @if($job->requirements)
                <section class="bg-white rounded-xl p-8 shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Requirements</h2>
                    @php
                        $requirements = is_string($job->requirements) ? json_decode($job->requirements, true) : $job->requirements;
                    @endphp
                    @if(is_array($requirements))
                        <ul class="list-disc pl-5 space-y-2 text-gray-700">
                            @foreach($requirements as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-700">{{ $job->requirements }}</div>
                    @endif
                </section>
                @endif

                <!-- Benefits -->
                @if($job->benefits)
                <section class="bg-white rounded-xl p-8 shadow-md">
                    <h2 class="text-2xl font-bold mb-4">What We Offer</h2>
                    @php
                        $benefits = is_string($job->benefits) ? json_decode($job->benefits, true) : $job->benefits;
                    @endphp
                    @if(is_array($benefits))
                        <ul class="list-disc pl-5 space-y-2 text-gray-700">
                            @foreach($benefits as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-700">{{ $job->benefits }}</div>
                    @endif
                </section>
                @endif

                <!-- Application Form -->
                <section id="apply-form" class="bg-white rounded-xl p-8 shadow-md">
                    <h2 class="text-2xl font-bold mb-6">Apply for This Position</h2>
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('careers.apply', $job->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                <input type="tel" name="phone" required value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile</label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter</label>
                            <textarea name="cover_letter" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('cover_letter') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resume / CV *</label>
                            <input type="file" name="resume" required accept=".pdf,.doc,.docx" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                            Submit Application
                        </button>
                    </form>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-blue-600 text-white rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-bold mb-3">Ready to Apply?</h3>
                        <a href="#apply-form" class="block w-full px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold text-center hover:bg-blue-50">
                            Apply Now
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Job Details</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600">Positions</dt>
                                <dd class="font-medium">{{ $job->positions_available }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
