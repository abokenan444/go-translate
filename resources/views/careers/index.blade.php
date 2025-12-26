@extends('layouts.app')

@section('title', __('pages.careers.title'))

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl">
        <h1 class="text-3xl font-semibold">{{ __('pages.careers.heading') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('pages.careers.intro') }}</p>
    </div>

    <div class="mt-6 flex flex-wrap gap-4">
        @if(isset($departments) && count($departments))
            <div>
                <span class="font-medium">Departments:</span>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($departments as $dept)
                        <span class="px-3 py-1 text-sm bg-gray-100 rounded">{{ $dept }}</span>
                    @endforeach
                </div>
            </div>
        @endif
        @if(isset($locations) && count($locations))
            <div>
                <span class="font-medium">Locations:</span>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($locations as $loc)
                        <span class="px-3 py-1 text-sm bg-gray-100 rounded">{{ $loc }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($jobs as $job)
            <a href="{{ route('careers.show', ['job' => $job->id]) }}" class="block p-5 border rounded-lg bg-white hover:shadow">
                <h3 class="text-lg font-medium">{{ $job->title }}</h3>
                <p class="text-gray-700 mt-2">{{ \Illuminate\Support\Str::limit($job->summary ?? $job->description, 140) }}</p>
                <p class="mt-3 text-sm text-gray-600">{{ $job->location }} • {{ $job->employment_type ?? 'Full-time' }}</p>
            </a>
        @empty
            <div class="p-5 border rounded-lg bg-white">
                <p class="text-gray-700">No open roles at the moment. Please check back soon.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
