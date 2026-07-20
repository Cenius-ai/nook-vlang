@extends('layouts.app')
@section('title', 'Log in')

@section('content')
<div class="max-w-md mx-auto mt-12">
    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-8">
        <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-2">Welcome back</h1>
        <p class="text-sm text-[#5a656b] dark:text-[#8a959b] mb-6">Sign in to your Nook account.</p>

        <div class="mb-4 p-3 rounded-lg bg-[#e8eef1] dark:bg-[#1a282f] text-sm text-[#5a656b] dark:text-[#8a959b]">
            <strong>Demo:</strong> cenius@cenius.ai / cenius
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-[#c9302d]/10 text-[#c9302d] text-sm border border-[#c9302d]/30">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
                       placeholder="cenius@cenius.ai">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
                       placeholder="••••••••">
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 transition-colors">
                Sign in
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-[#5a656b] dark:text-[#8a959b]">
            Don't have an account? <a href="{{ route('register') }}" class="text-[#009494] hover:underline font-medium">Sign up</a>
        </p>
    </div>
</div>
@endsection
