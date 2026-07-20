@extends('layouts.app')
@section('title', 'Search: ' . $q)

@section('content')
<h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-2">Search Results</h1>

<form action="{{ route('search') }}" method="GET" class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="q" value="{{ $q }}"
               class="flex-1 px-4 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#132129] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
               placeholder="Search questions…">
        <button type="submit" class="px-4 py-2 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] transition-colors">Search</button>
    </div>
</form>

@if(mb_strlen(trim($q)) === 0)
    <p class="text-[#5a656b] dark:text-[#8a959b]">Enter a search term above.</p>
@else
    <p class="text-sm text-[#5a656b] dark:text-[#8a959b] mb-4">{{ $questions->total() }} result{{ $questions->total() !== 1 ? 's' : '' }} for "<strong>{{ $q }}</strong>"</p>

    @forelse($questions as $question)
        <div class="mb-3 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-5">
            <h2 class="text-lg font-heading font-semibold mb-1">
                <a href="{{ route('questions.show', $question) }}" class="text-[#132129] dark:text-[#e8eef1] hover:text-[#009494] dark:hover:text-[#009494]">{{ $question->title }}</a>
            </h2>
            <p class="text-sm text-[#5a656b] dark:text-[#8a959b] line-clamp-2 mb-2">{{ \Illuminate\Support\Str::limit(strip_tags($question->body), 200) }}</p>
            <span class="text-xs text-[#5a656b]">
                {{ $question->votes_count }} votes &middot; {{ $question->answers_count }} answers &middot;
                asked by <a href="{{ route('users.profile', $question->user) }}" class="text-[#009494] hover:underline">{{ $question->user->name }}</a>
                {{ $question->created_at->diffForHumans() }}
            </span>
        </div>
    @empty
        <p class="text-[#5a656b] py-8 text-center">No questions found matching "{{ $q }}".</p>
    @endforelse
    <div class="mt-4">{{ $questions->links() }}</div>
@endif
@endsection
