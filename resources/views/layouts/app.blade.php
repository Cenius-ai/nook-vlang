<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      x-init="$watch('dark', v => { localStorage.setItem('theme', v ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', v); })"
      :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nook') — Developer Q&amp;A</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
</head>
<body class="font-body bg-background text-foreground dark:bg-[#0b1418] dark:text-[#e8eef1] min-h-screen flex flex-col antialiased">

    {{-- Top Navigation --}}
    <nav class="sticky top-0 z-50 h-16 border-b border-[#d9dfe2] dark:border-[#2a353a] glass-nav">
        <div class="max-w-[1200px] mx-auto px-4 h-full flex items-center justify-between">
            {{-- Brand --}}
            <a href="{{ route('questions.index') }}" class="flex items-center gap-2 shrink-0">
                <span class="text-2xl font-heading font-bold text-primary dark:text-[#e8eef1] tracking-tight">Nook</span>
                <span class="hidden sm:inline text-sm text-muted-fg font-medium">Dev Q&amp;A</span>
            </a>

            {{-- Center nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('questions.index') }}" class="nav-link {{ request()->routeIs('questions.index') && !request()->has('tag') ? 'nav-link-active' : '' }}">Questions</a>
                <a href="{{ route('tags.index') }}" class="nav-link {{ request()->routeIs('tags.*') ? 'nav-link-active' : '' }}">Tags</a>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <form action="{{ route('search') }}" method="GET" class="hidden sm:block">
                    <input type="text" name="q" placeholder="Search…" value="{{ request('q') }}"
                           class="search-input">
                </form>

                {{-- Dark mode toggle --}}
                <button @click="dark = !dark" class="icon-btn" aria-label="Toggle dark mode">
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                @auth
                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}" class="icon-btn relative" aria-label="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                            <span class="badge badge-destructive absolute -top-0.5 -right-0.5">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </a>

                    @if(auth()->user()->isModerator())
                        <a href="{{ route('admin.flags.index') }}" class="icon-btn" aria-label="Moderation">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </a>
                    @endif

                    {{-- Profile --}}
                    <a href="{{ route('profile') }}" class="icon-btn ml-1" aria-label="Profile">
                        <div class="avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="icon-btn" aria-label="Sign out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Gradient keyline under nav --}}
    <div class="h-[2px] bg-gradient-to-r from-accent via-accent/40 to-transparent"></div>

    {{-- Main content --}}
    <main class="flex-1">
        <div class="max-w-[1200px] mx-auto px-4 py-6">
            @if(session('success'))
                <div class="alert-success mb-4" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Gradient keyline above footer --}}
    <div class="h-[2px] bg-gradient-to-r from-transparent via-accent/40 to-accent"></div>

    <footer class="py-4 text-center text-xs text-muted-fg">
        Nook &mdash; Developer Q&amp;A Community
    </footer>

    @stack('scripts')
</body>
</html>
