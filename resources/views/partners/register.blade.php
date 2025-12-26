@extends('layouts.app')
@section('title', 'Partner Registration')
@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold mb-6">Become a Partner</h1>
            <form action="{{ route('partners.register.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Company Name *</label>
                        <input type="text" name="company_name" required class="w-full px-4 py-2 border rounded-lg" value="{{ old('company_name') }}">
                        @error('company_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Contact Name *</label>
                        <input type="text" name="contact_name" required class="w-full px-4 py-2 border rounded-lg" value="{{ old('contact_name') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Email *</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Phone</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border rounded-lg" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Country *</label>
                        <input type="text" name="country" required class="w-full px-4 py-2 border rounded-lg" value="{{ old('country') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Website</label>
                        <input type="url" name="website" class="w-full px-4 py-2 border rounded-lg" value="{{ old('website') }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">Submit Application</button>
            </form>
        </div>
    </div>
</div>
@endsection
