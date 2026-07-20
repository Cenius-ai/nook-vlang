@extends('layouts.app')
@section('title', 'Questions')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- Main column --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1]">Questions</h1>
            @auth
            <a href="{{ route('questions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#009494] text-white text-sm font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ask Question
            </a>
            @endauth
        </div>

        @if(request()->filled('tag'))
            <div class="mb-4 p-2 text-sm text-[#5a656b] dark:text-[#8a959b]">
                Filtered by tag: <span class="font-medium text-[#009494]">{{ request('tag') }}</span>
                <a href="{{ route('questions.index') }}" class="ml-2 text-[#c9302d] hover:underline">&times; Clear</a>
            </div>
        @endif

        @forelse($questions as $question)
            <div class="mb-3 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-5 hover:border-[#009494]/30 dark:hover:border-[#009494]/30 transition-colors">
                <div class="flex gap-4">
                    {{-- Stats --}}
                    <div class="hidden sm:flex flex-col items-center gap-1 min-w-[60px] text-sm text-[#5a656b] dark:text-[#8a959b]">
                        <span class="font-medium text-[#0b1418] dark:text-[#e8eef1]">{{ $question->votes_count }}</span>
                        <span class="text-xs">votes</span>
                        <span class="font-medium {{ $question->accepted_answer_id ? 'text-[#009494] bg-[#009494]/10 px-2 py-0.5 rounded' : 'text-[#0b1418] dark:text-[#e8eef1]' }}">{{ $question->answers_count }}</span>
                        <span class="text-xs">{{ Str::plural('answer', $question->answers_count) }}</span>
                        <span class="text-xs text-[#5a656b] dark:text-[#5a656b] mt-1">{{ $question->view_count }} {{ Str::plural('view', $question->view_count) }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-heading font-semibold mb-1">
                            <a href="{{ route('questions.show', $question) }}" class="text-[#132129] dark:text-[#e8eef1] hover:text-[#009494] dark:hover:text-[#009494] transition-colors">
                                {{ $question->title }}
                            </a>
                        </h2>
                        <p class="text-sm text-[#5a656b] dark:text-[#8a959b] line-clamp-2 mb-3">
                            {{ \Illuminate\Support\Str::limit(strip_tags($question->body), 200) }}
                        </p>
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach($question->tags as $tag)
                                <a href="{{ route('tags.show', $tag) }}" class="px-2 py-0.5 text-xs rounded-md bg-[#e8eef1] dark:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] dark:hover:text-[#009494] transition-colors">{{ $tag->name }}</a>
                            @endforeach
                            <span class="text-xs text-[#5a656b] dark:text-[#5a656b] ml-auto">
                                asked {{ $question->created_at->diffForHumans() }} by
                                <a href="{{ route('users.profile', $question->user) }}" class="text-[#009494] hover:underline font-medium">{{ $question->user->name }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-[#5a656b] dark:text-[#8a959b]">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-lg font-medium">No questions yet.</p>
                @auth
                    <p class="mt-2"><a href="{{ route('questions.create') }}" class="text-[#009494] hover:underline font-medium">Be the first to ask one!</a></p>
                @endauth
            </div>
        @endforelse

        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="w-full lg:w-[260px] shrink-0">
        <div class="bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <h3 class="text-sm font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Popular Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <a href="{{ route('tags.show', $tag) }}"
                       class="px-2.5 py-1 text-xs rounded-md border {{ request('tag') === $tag->slug ? 'border-[#009494] bg-[#009494]/10 text-[#009494]' : 'border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] dark:hover:text-[#009494] hover:border-[#009494]/30' }} transition-colors">
                        {{ $tag->name }} <span class="opacity-60">({{ $tag->questions_count }})</span>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('tags.index') }}" class="block mt-3 text-xs text-[#009494] hover:underline font-medium">View all tags &rarr;</a>
        </div>
    </div>
</div>
@endsection
