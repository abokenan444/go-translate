@extends('layouts.app')
@section('title', 'Documentation - CulturalTranslate')
@section('content')
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('docs.index') }}" class="text-indigo-600 hover:underline mb-4 inline-block">
                ← Back to Documentation
            </a>
            <h1 class="text-4xl font-bold mb-8">{{ ucwords(str_replace('-', ' ', $slug)) }}</h1>
            <div class="prose max-w-none">
                <p>Documentation content for {{ $slug }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
