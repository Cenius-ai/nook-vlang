@extends('layouts.app')
@section('title', $question->title)

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- Main --}}
    <div class="flex-1 min-w-0">
        {{-- Question --}}
        <div class="mb-6">
            <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-4">{{ $question->title }}</h1>

            <div class="flex flex-wrap items-center gap-3 mb-4 text-sm text-[#5a656b] dark:text-[#8a959b]">
                <span class="flex items-center gap-1">
                    <span class="font-medium text-[#0b1418] dark:text-[#e8eef1]">{{ $question->votes_count }}</span> {{ Str::plural('vote', $question->votes_count) }}
                </span>
                <span>{{ $question->answers_count }} {{ Str::plural('answer', $question->answers_count) }}</span>
                <span>{{ $question->view_count }} {{ Str::plural('view', $question->view_count) }}</span>
                <span>asked {{ $question->created_at->diffForHumans() }}</span>
            </div>

            <div class="flex gap-4">
                {{-- Vote buttons --}}
                <div class="flex flex-col items-center gap-0.5 shrink-0">
                    @auth
                    <button onclick="handleVote('App\\Models\\Question', {{ $question->id }}, 1, this.closest('.flex.flex-col'))"
                            class="p-1 rounded hover:bg-[#e8eef1] dark:hover:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494]"
                            aria-label="Upvote question">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <span class="text-lg font-bold tabular-nums text-[#5a656b] dark:text-[#8a959b]" data-votes-count>{{ $question->votes_count }}</span>
                    <button onclick="handleVote('App\\Models\\Question', {{ $question->id }}, -1, this.closest('.flex.flex-col'))"
                            class="p-1 rounded hover:bg-[#e8eef1] dark:hover:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#c9302d] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c9302d]"
                            aria-label="Downvote question">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="p-1 rounded text-[#5a656b] dark:text-[#8a959b]" title="Log in to vote">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </a>
                    <span class="text-lg font-bold tabular-nums text-[#5a656b] dark:text-[#8a959b]">{{ $question->votes_count }}</span>
                    @endauth
                </div>

                <div class="flex-1 min-w-0">
                    <div class="prose dark:prose-invert prose-sm max-w-none text-[#0b1418] dark:text-[#e8eef1] mb-4">
                        {{-- Render body as plain text escaped --}}
                        <div class="whitespace-pre-wrap break-words">{{ $question->body }}</div>
                    </div>

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($question->tags as $tag)
                            <a href="{{ route('tags.show', $tag) }}" class="px-2 py-0.5 text-xs rounded-md bg-[#e8eef1] dark:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] dark:hover:text-[#009494] transition-colors">{{ $tag->name }}</a>
                        @endforeach
                    </div>

                    {{-- Author + actions row --}}
                    <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-[#d9dfe2] dark:border-[#2a353a]">
                        <div class="flex items-center gap-2 text-sm">
                            <a href="{{ route('users.profile', $question->user) }}" class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-[#009494] flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($question->user->name, 0, 1)) }}</div>
                                <span class="font-medium text-[#009494] hover:underline">{{ $question->user->name }}</span>
                            </a>
                            <span class="text-[#5a656b] dark:text-[#8a959b]">asked {{ $question->created_at->format('M j, Y') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @auth
                            @if(auth()->id() === $question->user_id || auth()->user()->isModerator())
                                {{-- Owner/moderator actions: could add edit/delete here --}}
                            @endif
                            <button onclick="flagContent('App\\Models\\Question', {{ $question->id }})"
                                    class="text-xs text-[#5a656b] dark:text-[#8a959b] hover:text-[#c9302d] dark:hover:text-[#c9302d] flex items-center gap-1 p-1 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                Flag
                            </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Answers --}}
        <div class="mt-8">
            <h2 class="text-xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-4">
                {{ $question->answers_count }} {{ Str::plural('Answer', $question->answers_count) }}
            </h2>

            @forelse($question->answers as $answer)
                <div class="mb-4 bg-[#fdffff] dark:bg-[#132129] rounded-lg border {{ $answer->is_accepted ? 'border-[#009494] ring-1 ring-[#009494]/20' : 'border-[#d9dfe2] dark:border-[#2a353a]' }} p-5">
                    @if($answer->is_accepted)
                        <div class="flex items-center gap-1 text-xs text-[#009494] font-medium mb-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Accepted Answer
                        </div>
                    @endif

                    <div class="flex gap-4">
                        {{-- Answer vote --}}
                        <div class="flex flex-col items-center gap-0.5 shrink-0">
                            @auth
                            <button onclick="handleVote('App\\Models\\Answer', {{ $answer->id }}, 1, this.closest('.flex.flex-col'))"
                                    class="p-1 rounded hover:bg-[#e8eef1] dark:hover:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494]"
                                    aria-label="Upvote answer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <span class="text-base font-bold tabular-nums text-[#5a656b] dark:text-[#8a959b]" data-votes-count>{{ $answer->votes_count }}</span>
                            <button onclick="handleVote('App\\Models\\Answer', {{ $answer->id }}, -1, this.closest('.flex.flex-col'))"
                                    class="p-1 rounded hover:bg-[#e8eef1] dark:hover:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#c9302d] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c9302d]"
                                    aria-label="Downvote answer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="p-1 rounded text-[#5a656b] dark:text-[#8a959b]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </a>
                            <span class="text-base font-bold tabular-nums text-[#5a656b] dark:text-[#8a959b]">{{ $answer->votes_count }}</span>
                            @endauth

                            {{-- Accept button for question owner --}}
                            @auth
                            @if(auth()->id() === $question->user_id && !$answer->is_accepted && !$question->accepted_answer_id)
                                <button onclick="acceptAnswer('{{ $question->slug }}', {{ $answer->id }})"
                                        class="mt-1 p-1 rounded text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] hover:bg-[#e8eef1] dark:hover:bg-[#1a282f] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494]"
                                        aria-label="Accept this answer" title="Accept this answer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            @endif
                            @endauth
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="whitespace-pre-wrap break-words text-[#0b1418] dark:text-[#e8eef1] text-sm mb-3">{{ $answer->body }}</div>
                            <div class="flex items-center justify-between pt-2 border-t border-[#d9dfe2] dark:border-[#2a353a]">
                                <div class="flex items-center gap-2 text-xs">
                                    <a href="{{ route('users.profile', $answer->user) }}" class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full bg-[#5a656b] flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($answer->user->name, 0, 1)) }}</div>
                                        <span class="font-medium text-[#009494] hover:underline">{{ $answer->user->name }}</span>
                                    </a>
                                    <span class="text-[#5a656b] dark:text-[#8a959b]">answered {{ $answer->created_at->format('M j, Y') }}</span>
                                </div>
                                @auth
                                <button onclick="flagContent('App\\Models\\Answer', {{ $answer->id }})"
                                        class="text-xs text-[#5a656b] dark:text-[#8a959b] hover:text-[#c9302d] flex items-center gap-1 p-1 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                    Flag
                                </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-[#5a656b] dark:text-[#8a959b] bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a]">
                    <p>No answers yet. Be the first to answer!</p>
                </div>
            @endforelse

            {{-- Answer form --}}
            @auth
            <div class="mt-6 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-5">
                <h3 class="text-lg font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Your Answer</h3>
                <form method="POST" action="{{ route('answers.store', $question) }}" x-data="answerForm()" @submit.prevent="submit">
                    @csrf
                    <textarea name="body" x-model="body" rows="6" required maxlength="50000"
                              class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent text-sm"
                              placeholder="Write your answer here…"></textarea>
                    <div class="flex items-center justify-between mt-3">
                        <span x-show="error" x-text="error" class="text-sm text-[#c9302d]"></span>
                        <button type="submit" :disabled="loading"
                                class="ml-auto px-4 py-2 rounded-lg bg-[#009494] text-white text-sm font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 disabled:opacity-60 transition-colors">
                            <span x-show="!loading">Post Answer</span>
                            <span x-show="loading">Posting…</span>
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="mt-6 p-4 text-center text-sm text-[#5a656b] dark:text-[#8a959b] bg-[#e8eef1] dark:bg-[#1a282f] rounded-lg">
                <a href="{{ route('login') }}" class="text-[#009494] hover:underline font-medium">Log in</a> to post an answer.
            </div>
            @endauth
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="w-full lg:w-[260px] shrink-0">
        <div class="bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <h3 class="text-sm font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($question->tags as $tag)
                    <a href="{{ route('tags.show', $tag) }}" class="px-2.5 py-1 text-xs rounded-md border border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] dark:hover:text-[#009494] hover:border-[#009494]/30 transition-colors">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Flag modal --}}
<div x-data="flagModal()" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="open=false">
    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-6 w-full max-w-md mx-4 shadow-xl">
        <h3 class="text-lg font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-4">Flag Content</h3>
        <form @submit.prevent="submitFlag">
            <textarea x-model="reason" rows="3" required maxlength="1000"
                      class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent text-sm mb-4"
                      placeholder="Explain why this content should be reviewed…"></textarea>
            <div class="flex justify-end gap-2">
                <button type="button" @click="open=false" class="px-4 py-2 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:bg-[#e8eef1] dark:hover:bg-[#1a282f]">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-[#c9302d] text-white hover:bg-[#a82826]">Submit Flag</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function answerForm() {
    return {
        body: '', error: '', loading: false,
        async submit() {
            this.error = ''; this.loading = true;
            try {
                const res = await fetch('{{ route('answers.store', $question) }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({body: this.body})
                });
                if (!res.ok) { const d = await res.json(); throw new Error(d.message || 'Failed'); }
                window.location.reload();
            } catch (e) { this.error = e.message; this.loading = false; }
        }
    }
}

let flagTarget = null;
function flagContent(type, id) {
    flagTarget = {flaggable_type: type, flaggable_id: id};
    document.querySelector('[x-data="flagModal()"]').__x.$data.open = true;
}
function flagModal() {
    return {
        open: false, reason: '',
        async submitFlag() {
            if (!flagTarget) return;
            try {
                const res = await fetch('/api/flags', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({...flagTarget, reason: this.reason})
                });
                const d = await res.json();
                alert(d.message);
                this.open = false; this.reason = '';
            } catch (e) { alert('Error flagging content.'); }
        }
    }
}

async function handleVote(type, id, value, el) {
    try {
        const res = await fetch('/api/votes', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({votable_type: type, votable_id: id, value: value})
        });
        if (res.ok) {
            const data = await res.json();
            const countEl = el.querySelector('[data-votes-count]');
            if (countEl) countEl.textContent = data.votes_count;
        }
    } catch (e) { /* silently fail */ }
}

async function acceptAnswer(slug, answerId) {
    try {
        const res = await fetch('/api/questions/' + slug + '/accept-answer', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({answer_id: answerId})
        });
        if (res.ok) window.location.reload();
    } catch (e) { /* silently fail */ }
}
</script>
@endpush
