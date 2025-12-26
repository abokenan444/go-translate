@extends('layouts.app')
@section('title', 'Verify Certificate')
@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold mb-6">Verify Certificate</h1>
            <p class="text-gray-600 mb-6">Enter the 16-character verification code found on your certificate</p>
            
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('certificates.verify.check') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Verification Code</label>
                    <input type="text" name="code" maxlength="16" required 
                           class="w-full px-4 py-3 border rounded-lg text-center text-2xl font-mono tracking-widest uppercase"
                           placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
                    Verify Certificate
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
