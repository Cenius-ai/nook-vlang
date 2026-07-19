@extends('layouts.app')
@section('title', $user->name . ' — Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-full bg-[#009494] flex items-center justify-center text-white text-2xl font-bold shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h1 class="text-xl font-heading font-bold text-[#132129] dark:text-[#e8eef1]">{{ $user->name }}</h1>
                <p class="text-sm text-[#5a656b] dark:text-[#8a959b]">@ {{ $user->username }}</p>
                @if($user->bio)
                    <p class="mt-2 text-sm text-[#0b1418] dark:text-[#e8eef1]">{{ $user->bio }}</p>
                @endif
                <div class="flex gap-4 mt-3 text-sm text-[#5a656b] dark:text-[#8a959b]">
                    <span><strong class="text-[#0b1418] dark:text-[#e8eef1]">{{ $stats['questions_count'] }}</strong> questions</span>
                    <span><strong class="text-[#0b1418] dark:text-[#e8eef1]">{{ $stats['answers_count'] }}</strong> answers</span>
                    <span><strong class="text-[#0b1418] dark:text-[#e8eef1]">{{ $stats['total_votes'] }}</strong> votes</span>
                </div>
                <p class="text-xs text-[#5a656b] dark:text-[#5a656b] mt-2">Member since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>
        @if(auth()->id() === $user->id)
            <div class="mt-4 pt-4 border-t border-[#d9dfe2] dark:border-[#2a353a]">
                <form method="POST" action="{{ route('profile.update') }}" x-data="editProfile()" @submit.prevent="submit">
                    @csrf @method('PUT')
                    <h3 class="text-sm font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Edit Profile</h3>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-[#5a656b] mb-1">Name</label>
                        <input type="text" name="name" x-model="name" required maxlength="100"
                               class="w-full px-3 py-1.5 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494]">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-[#5a656b] mb-1">Bio</label>
                        <textarea name="bio" x-model="bio" rows="2" maxlength="500"
                                  class="w-full px-3 py-1.5 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494]"></textarea>
                    </div>
                    <button type="submit" class="px-4 py-1.5 text-sm rounded-lg bg-[#009494] text-white hover:bg-[#007a7a]">Save</button>
                </form>
            </div>
        @endif
    </div>

    {{-- User's questions --}}
    <h2 class="text-lg font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Questions</h2>
    @forelse($questions as $q)
        <div class="mb-2 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium tabular-nums min-w-[40px] {{ $q->accepted_answer_id ? 'text-[#009494]' : 'text-[#5a656b] dark:text-[#8a959b]' }}">{{ $q->votes_count }} votes</span>
                <span class="text-sm font-medium tabular-nums min-w-[40px] {{ $q->accepted_answer_id ? 'text-[#009494] bg-[#009494]/10 px-2 py-0.5 rounded' : 'text-[#5a656b] dark:text-[#8a959b]' }}">{{ $q->answers_count }} answers</span>
                <a href="{{ route('questions.show', $q) }}" class="text-sm font-medium text-[#009494] hover:underline truncate">{{ $q->title }}</a>
                <span class="text-xs text-[#5a656b] dark:text-[#8a959b] ml-auto shrink-0">{{ $q->created_at->diffForHumans() }}</span>
            </div>
        </div>
    @empty
        <p class="text-sm text-[#5a656b] py-4">No questions yet.</p>
    @endforelse

    {{-- User's answers --}}
    <h2 class="text-lg font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3 mt-8">Answers</h2>
    @forelse($answers as $a)
        <div class="mb-2 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium tabular-nums min-w-[40px] text-[#5a656b] dark:text-[#8a959b]">{{ $a->votes_count }} votes</span>
                <a href="{{ route('questions.show', $a->question) }}" class="text-sm font-medium text-[#009494] hover:underline truncate">Re: {{ $a->question->title }}</a>
                <span class="text-xs text-[#5a656b] dark:text-[#8a959b] ml-auto shrink-0">{{ $a->created_at->diffForHumans() }}</span>
            </div>
        </div>
    @empty
        <p class="text-sm text-[#5a656b] py-4">No answers yet.</p>
    @endforelse
</div>

@if(auth()->id() === $user->id)
@push('scripts')
<script>
function editProfile() {
    return {
        name: '{{ $user->name }}',
        bio: '{{ $user->bio ?? '' }}',
        async submit(e) {
            try {
                const form = e.target.closest('form');
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({name: this.name, bio: this.bio, _method: 'PUT'})
                });
                if (res.ok) window.location.reload();
            } catch (e) {}
        }
    }
}
</script>
@endpush
@endif
@endsection
