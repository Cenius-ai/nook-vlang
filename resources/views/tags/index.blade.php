@extends('layouts.app')
@section('title', 'Tags')

@section('content')
<h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-6">Tags</h1>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
    @foreach($tags as $tag)
        <a href="{{ route('tags.show', $tag) }}"
           class="bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4 hover:border-[#009494]/30 dark:hover:border-[#009494]/30 transition-colors group">
            <span class="inline-block px-2.5 py-1 text-xs rounded-md bg-[#e8eef1] dark:bg-[#1a282f] text-[#009494] group-hover:bg-[#009494] group-hover:text-white transition-colors font-medium">{{ $tag->name }}</span>
            <p class="mt-2 text-xs text-[#5a656b] dark:text-[#8a959b]">{{ $tag->questions_count }} {{ Str::plural('question', $tag->questions_count) }}</p>
        </a>
    @endforeach
</div>
@endsection
