@extends('layouts.app')
@section('title', 'Certified Translator Dashboard')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Certified Translator Dashboard</h1>
        <p class="text-gray-600">Manage document certifications and seals</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Documents Certified</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['monthly_translations'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">This month</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Pending Review</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_count'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Awaiting stamp</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total Earnings</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($stats['total_earnings'] ?? 0, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">${{ number_format($stats['pending_earnings'] ?? 0, 2) }} pending</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Print Queue</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['print_queue'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Ready to print</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('partner.documents') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-center">
            <svg class="w-10 h-10 text-amber-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900 text-sm">Documents</h3>
        </a>
        
        <a href="{{ route('partner.print-queue') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-center">
            <svg class="w-10 h-10 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900 text-sm">Print Queue</h3>
        </a>
        
        <a href="{{ route('partner.assignments.index') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-center">
            <svg class="w-10 h-10 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="font-semibold text-gray-900 text-sm">Assignments</h3>
        </a>
        
        <a href="{{ route('partner.earnings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-center">
            <svg class="w-10 h-10 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900 text-sm">Earnings</h3>
        </a>
    </div>
    
    <!-- Stamp Management -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Seal & Stamp Management</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('partner.upload-stamp') }}" method="POST" enctype="multipart/form-data" class="max-w-xl">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Your Official Seal</label>
                    <input type="file" name="stamp" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                </div>
                <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700">Upload Seal</button>
            </form>
        </div>
    </div>
</div>
@endsection
