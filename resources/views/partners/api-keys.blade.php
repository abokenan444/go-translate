@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">API Keys</h1>
        <p class="text-gray-600 mt-2">Manage your API keys for programmatic access</p>
    </div>

    <!-- Create New API Key -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Create New API Key</h2>
        <form action="{{ route('partner.api-keys.create') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Key Name</label>
                <input type="text" name="name" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g., Production API Key">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="translate" checked class="mr-2">
                        <span>Translate</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="read" checked class="mr-2">
                        <span>Read</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="write" class="mr-2">
                        <span>Write</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Generate API Key
            </button>
        </form>
    </div>

    <!-- API Keys List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Your API Keys</h2>
        <div class="space-y-4">
            @forelse($apiKeys as $key)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $key->name }}</h3>
                        <div class="mt-2 font-mono text-sm bg-gray-100 p-2 rounded">
                            {{ $key->masked_key }}
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="inline-block mr-4">
                                <strong>Created:</strong> {{ $key->created_at->format('M d, Y') }}
                            </span>
                            <span class="inline-block mr-4">
                                <strong>Last Used:</strong> {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Never' }}
                            </span>
                            <span class="inline-block">
                                <strong>Status:</strong> 
                                <span class="px-2 py-1 rounded text-xs {{ $key->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $key->is_active ? 'Active' : 'Revoked' }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div>
                        @if($key->is_active)
                        <form action="{{ route('partner.api-keys.revoke', $key->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"
                                onclick="return confirm('Are you sure you want to revoke this API key?')">
                                Revoke
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">No API keys yet. Create one to get started!</p>
            @endforelse
        </div>
    </div>

    <!-- API Documentation Link -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-semibold text-blue-900 mb-2">📚 API Documentation</h3>
        <p class="text-blue-800 mb-2">Learn how to use the API with our comprehensive documentation.</p>
        <a href="{{ route('api.docs') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            View API Documentation →
        </a>
    </div>
</div>

@if(session('new_key'))
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="keyModal">
    <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
        <h2 class="text-2xl font-bold mb-4 text-green-600">✅ API Key Created!</h2>
        <p class="mb-4 text-gray-700">Please copy your API key now. You won't be able to see it again!</p>
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <code class="text-sm break-all">{{ session('new_key') }}</code>
        </div>
        <div class="flex gap-4">
            <button onclick="copyKey()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Copy to Clipboard
            </button>
            <button onclick="closeModal()" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function copyKey() {
    navigator.clipboard.writeText('{{ session("new_key") }}');
    alert('API Key copied to clipboard!');
}
function closeModal() {
    document.getElementById('keyModal').remove();
}
</script>
@endif
@endsection
