@extends('layouts.dashboard')

@section('title', 'My Affiliate Links')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ __('My Affiliate Links') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Manage and track all your referral links') }}
            </p>
        </div>
        <button onclick="openGenerateLinkModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>{{ __('New Link') }}
        </button>
    </div>

    <!-- Links Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($referrals as $referral)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                        {{ $referral->campaign_name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Created') }}: {{ $referral->created_at->format('M d, Y') }}
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $referral->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                    {{ ucfirst($referral->status) }}
                </span>
            </div>

            <!-- Description -->
            @if($referral->description)
            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                {{ $referral->description }}
            </p>
            @endif

            <!-- Link -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between">
                    <code class="text-sm text-gray-800 dark:text-gray-200 break-all">
                        {{ route('affiliate.track', $referral->referral_code) }}
                    </code>
                    <button onclick="copyLink('{{ route('affiliate.track', $referral->referral_code) }}')" 
                            class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($referral->clicks_count) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Clicks') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($referral->conversions_count) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Conversions') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $referral->clicks_count > 0 ? number_format(($referral->conversions_count / $referral->clicks_count) * 100, 1) : 0 }}%
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Rate') }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-2">
                <button onclick="viewQRCode('{{ $referral->referral_code }}')" 
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-qrcode mr-1"></i>{{ __('QR Code') }}
                </button>
                <button onclick="shareLink('{{ route('affiliate.track', $referral->referral_code) }}')" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-share-alt mr-1"></i>{{ __('Share') }}
                </button>
                @if($referral->status === 'active')
                <form action="{{ route('dashboard.affiliate.toggle-link', $referral->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition text-sm">
                        <i class="fas fa-pause mr-1"></i>{{ __('Pause') }}
                    </button>
                </form>
                @else
                <form action="{{ route('dashboard.affiliate.toggle-link', $referral->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition text-sm">
                        <i class="fas fa-play mr-1"></i>{{ __('Activate') }}
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <i class="fas fa-link text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('No affiliate links yet') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                {{ __('Create your first affiliate link to start earning commissions') }}
            </p>
            <button onclick="openGenerateLinkModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>{{ __('Create First Link') }}
            </button>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($referrals->hasPages())
    <div class="mt-8">
        {{ $referrals->links() }}
    </div>
    @endif
</div>

<!-- Generate Link Modal -->
<div id="generateLinkModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            {{ __('Generate New Link') }}
        </h3>
        <form action="{{ route('dashboard.affiliate.generate-link') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">{{ __('Campaign Name') }} *</label>
                <input type="text" name="campaign_name" required
                       class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="e.g., Facebook Campaign">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">{{ __('Description') }}</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Optional description..."></textarea>
            </div>
            <div class="flex space-x-4">
                <button type="button" onclick="closeGenerateLinkModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white px-4 py-2 rounded-lg transition">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    {{ __('Generate') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrCodeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 max-w-md w-full mx-4 text-center">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            {{ __('QR Code') }}
        </h3>
        <div id="qrCodeContainer" class="bg-white p-4 rounded-lg inline-block mb-4"></div>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            {{ __('Scan this QR code to share your affiliate link') }}
        </p>
        <button onclick="closeQRCodeModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
            {{ __('Close') }}
        </button>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
function copyLink(link) {
    navigator.clipboard.writeText(link).then(() => {
        alert('{{ __("Link copied to clipboard!") }}');
    });
}

function openGenerateLinkModal() {
    document.getElementById('generateLinkModal').classList.remove('hidden');
}

function closeGenerateLinkModal() {
    document.getElementById('generateLinkModal').classList.add('hidden');
}

function viewQRCode(code) {
    const link = '{{ url("/ref") }}/' + code;
    const container = document.getElementById('qrCodeContainer');
    container.innerHTML = '';
    new QRCode(container, {
        text: link,
        width: 256,
        height: 256
    });
    document.getElementById('qrCodeModal').classList.remove('hidden');
}

function closeQRCodeModal() {
    document.getElementById('qrCodeModal').classList.add('hidden');
}

function shareLink(link) {
    if (navigator.share) {
        navigator.share({
            title: '{{ config("app.name") }} - Affiliate Link',
            text: '{{ __("Check out this amazing translation service!") }}',
            url: link
        });
    } else {
        copyLink(link);
    }
}

// Close modals on outside click
document.getElementById('generateLinkModal').addEventListener('click', function(e) {
    if (e.target === this) closeGenerateLinkModal();
});
document.getElementById('qrCodeModal').addEventListener('click', function(e) {
    if (e.target === this) closeQRCodeModal();
});
</script>
@endpush
@endsection
