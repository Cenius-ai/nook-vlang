@extends('layouts.app')
@section('title', 'Sign up')

@section('content')
<div class="max-w-md mx-auto mt-12">
    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-8">
        <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-2">Create your account</h1>
        <p class="text-sm text-[#5a656b] dark:text-[#8a959b] mb-6">Join the Nook developer community.</p>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-[#c9302d]/10 text-[#c9302d] text-sm border border-[#c9302d]/30">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required maxlength="50" pattern="[A-Za-z0-9_-]+"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Password</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 transition-colors">
                Create account
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-[#5a656b] dark:text-[#8a959b]">
            Already have an account? <a href="{{ route('login') }}" class="text-[#009494] hover:underline font-medium">Sign in</a>
        </p>
    </div>
</div>
@endsection
