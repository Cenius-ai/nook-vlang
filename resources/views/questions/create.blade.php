@extends('layouts.app')
@section('title', 'Ask a Question')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-6">Ask a Question</h1>

    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-6">
        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-[#c9302d]/10 text-[#c9302d] text-sm border border-[#c9302d]/30">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="300"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
                       placeholder="e.g. How do I optimize SQL queries with Eloquent?">
            </div>

            <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Body</label>
                <textarea id="body" name="body" rows="10" required maxlength="50000"
                          class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent text-sm"
                          placeholder="Describe your question in detail. Include any relevant code, error messages, or context…">{{ old('body') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="tags" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Tags</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags') }}" maxlength="500"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
                       placeholder="e.g. laravel, sql, eloquent (comma separated)">
                <p class="mt-1 text-xs text-[#5a656b] dark:text-[#8a959b]">Separate tags with commas. Existing tags will be reused.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 transition-colors">
                    Post Question
                </button>
                <a href="{{ route('questions.index') }}" class="px-4 py-2 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:bg-[#e8eef1] dark:hover:bg-[#1a282f]">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
