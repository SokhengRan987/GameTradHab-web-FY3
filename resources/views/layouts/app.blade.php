<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GameTradeHub')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rajdhani', sans-serif; }
        .font-game { font-family: 'Orbitron', monospace; }
    </style>

    {{-- Open Graph Meta Tags for social sharing --}}
    <meta property="og:site_name"   content="GameTradeHub">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="@yield('og_title', 'GameTradeHub — Buy & Sell Game Accounts')">
    <meta property="og:description" content="@yield('og_description', 'Safe escrow-protected marketplace for game accounts. Buy and sell Mobile Legends, Valorant, PUBG and more.')">
    <meta property="og:url"         content="@yield('og_url', url()->current())">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.png'))">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', 'GameTradeHub')">
    <meta name="twitter:description" content="@yield('og_description', 'Safe escrow-protected marketplace for game accounts.')">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/og-default.png'))">

    {{-- General --}}
    <meta name="description" content="@yield('og_description', 'Safe escrow-protected marketplace for game accounts.')">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="font-bold text-xl text-white flex items-center gap-2">
                <span class="text-indigo-400">Game</span>TradeHub
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('listings.index') }}"
                   class="px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    Browse
                </a>
                <a href="{{ route('auctions.index') }}"
                    class="px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    Auctions
                </a>
                @auth
                <a href="{{ route('transactions.index') }}"
                class="px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    My Orders
                </a>
                @endauth
            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Sell Item Button --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-500
                                    text-white text-sm font-semibold rounded-lg transition">
                            New
                            <span class="text-xs">+</span>
                        </button>
                        <div x-show="open"
                            @click.outside="open = false"
                            class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700
                                    rounded-xl shadow-xl py-1 z-50">
                            <a href="{{ route('listings.create') }}"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                Sell Account
                            </a>
                            <a href="{{ route('auctions.create') }}"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                Create Auction
                            </a>
                        </div>
                    </div>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 bg-gray-800 px-3 py-1.5
                                       rounded-lg text-sm text-gray-300 hover:text-white transition">
                            <div class="w-6 h-6 rounded-full bg-indigo-600 flex items-center
                                        justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            {{ auth()->user()->name }}
                            <span class="text-xs">▾</span>
                        </button>
                        <div x-show="open"
                             @click.outside="open = false"
                             class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700
                                    rounded-xl shadow-xl py-1 z-50">
                            <a href="{{ route('dashboard') }}"
                               class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                📊 Dashboard
                            </a>
                            {{-- <a href="{{ route('wallet.index') }}"
                               class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                💰 Wallet
                            </a> --}}
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                ⚙️ Profile
                            </a>
                            @if(auth()->user()->isAdmin())
                            <div class="border-t border-gray-700 my-1"></div>
                            <a href="{{ route('admin.dashboard') }}"
                               class="block px-4 py-2 text-sm text-sky-400 hover:bg-gray-700">
                                👨‍💼 Admin Panel
                            </a>
                            @endif
                            <div class="border-t border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm text-gray-400 hover:text-white transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white
                              text-sm font-semibold rounded-lg transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 pt-4">
        <div class="bg-green-500/10 border border-green-500/25 text-green-400
                    px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 pt-4">
        <div class="bg-red-500/10 border border-red-500/25 text-red-400
                    px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- PAGE CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-800/50 mt-16"
            style="background: linear-gradient(180deg, #030712 0%, #020208 100%)">

        {{-- Top footer --}}
        <div class="max-w-7xl mx-auto px-4 py-10">
            <div class="grid grid-cols-5 gap-8">

                {{-- Brand --}}
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center
                                    justify-center text-white font-game font-bold text-sm
                                    shadow-lg shadow-indigo-500/30">G</div>
                        <span class="font-game text-lg font-bold tracking-wider">
                            <span class="text-indigo-400">GAME</span>
                            <span class="text-white">TRADE</span>
                            <span class="text-indigo-400">HUB</span>
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4 max-w-xs">
                        The safest marketplace for buying and selling game accounts.
                        All transactions protected by escrow.
                    </p>
                    {{-- Trust badges --}}
                    <div class="flex gap-2">
                        <span class="bg-green-500/10 border border-green-500/20
                                    text-green-400 text-xs px-2 py-1 rounded-lg">
                            🔒 Escrow Safe
                        </span>
                        <span class="bg-indigo-500/10 border border-indigo-500/20
                                    text-indigo-400 text-xs px-2 py-1 rounded-lg">
                            ✅ Verified
                        </span>
                    </div>
                </div>

                {{-- Marketplace --}}
                <div>
                    <div class="font-game text-xs font-bold text-gray-400
                                tracking-widest uppercase mb-3">
                        Marketplace
                    </div>
                    <div class="flex flex-col gap-2">
                        @foreach([
                            [route('home'), '🛒 Browse Accounts'],
                            [route('auctions.index'), '🏆 Live Auctions'],
                            [route('listings.create'), '📤 Sell Account'],
                            [route('auctions.create'), '⚡ Create Auction'],
                        ] as [$url, $label])
                        <a href="{{ $url }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Games --}}
                <div>
                    <div class="font-game text-xs font-bold text-gray-400
                                tracking-widest uppercase mb-3">
                        Top Games
                    </div>
                    <div class="flex flex-col gap-2">
                        @php
                            $footerGames = \App\Models\Game::where('is_active',true)->take(5)->get();
                            $gameIcons = ['mobile-legends'=>'⚔️','valorant'=>'🎯','pubg-mobile'=>'🔫','genshin-impact'=>'✨','free-fire'=>'🔥'];
                        @endphp
                        @foreach($footerGames as $game)
                        <a href="{{ route('home', ['game_id' => $game->id]) }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            {{ $gameIcons[$game->slug] ?? '🎮' }} {{ $game->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Account --}}
                <div>
                    <div class="font-game text-xs font-bold text-gray-400
                                tracking-widest uppercase mb-3">
                        Account
                    </div>
                    <div class="flex flex-col gap-2">
                        @auth
                        <a href="{{ route('dashboard') }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('transactions.index') }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            📦 My Orders
                        </a>
                        {{-- <a href="{{ route('wallet.index') }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            💰 Wallet
                        </a> --}}
                        <a href="{{ route('profile.edit') }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            ⚙️ Profile
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            🔑 Sign In
                        </a>
                        <a href="{{ route('register') }}"
                        class="text-xs text-gray-500 hover:text-indigo-400 transition">
                            🎮 Create Account
                        </a>
                        @endauth
                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="text-xs text-gray-700">
                    © {{ date('Y') }}
                    <span class="font-game text-gray-600">GAMETRADEHUB</span>
                    — All rights reserved
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-700">
                    <span>🔒 SSL Secured</span>
                    <span>⚡ Powered by Laravel</span>
                    <span class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        All systems operational
                    </span>
                </div>
            </div>
        </div>

    </footer>

    @stack('scripts')
</body>
</html>
