@extends('layouts.app')

@section('title', __('pages.careers.title'))

@section('content')
<div class="container mx-auto px-4 py-10">
    <a href="{{ route('careers.index') }}" class="text-blue-600 hover:underline">← {{ __('Back') }}</a>

    <div class="mt-4 p-5 border rounded-lg bg-white">
        <h1 class="text-3xl font-semibold">{{ $job->title }}</h1>
        <p class="mt-2 text-gray-600">{{ $job->location }} • {{ $job->employment_type ?? 'Full-time' }}</p>
        <div class="prose max-w-none mt-6">{!! nl2br(e($job->description)) !!}</div>
    </div>

    @if(isset($relatedJobs) && $relatedJobs->count())
    <div class="mt-8">
        <h2 class="text-2xl font-semibold">Related roles</h2>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($relatedJobs as $r)
                <a href="{{ route('careers.show', ['job' => $r->id]) }}" class="block p-5 border rounded-lg bg-white hover:shadow">
                    <h3 class="text-lg font-medium">{{ $r->title }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $r->location }} • {{ $r->employment_type ?? 'Full-time' }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-10 p-5 border rounded-lg bg-white">
        <h2 class="text-xl font-medium">Apply</h2>
        <p class="text-gray-700 mt-2">Send your resume and a brief note to <a href="mailto:careers@culturaltranslate.com" class="text-blue-600 hover:underline">careers@culturaltranslate.com</a>.</p>
        {{-- Form can be wired later to CareersController@apply --}}
    </div>
</div>
@endsection
