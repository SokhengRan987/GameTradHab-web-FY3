<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GameTradeHub')</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                            + Sell Item
                            <span class="text-xs">▾</span>
                        </button>
                        <div x-show="open"
                            @click.outside="open = false"
                            class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700
                                    rounded-xl shadow-xl py-1 z-50">
                            <a href="{{ route('listings.create') }}"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                Fixed Price
                            </a>
                            <a href="{{ route('auctions.create') }}"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                Auction
                            </a>
                        </div>
                    </div>
                    {{-- Wallet Balance --}}
                    <a href="{{ route('wallet.index') }}"
                       class="bg-yellow-500/10 border border-yellow-500/25 text-yellow-400
                              px-3 py-1.5 rounded-full text-xs font-bold hover:bg-yellow-500/20 transition">
                         ${{ number_format(auth()->user()->wallet_balance, 2) }}
                    </a>

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
                            <a href="{{ route('wallet.index') }}"
                               class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                💰 Wallet
                            </a>
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
    <footer class="border-t border-gray-800 mt-20 py-8 text-center text-gray-600 text-sm">
        © {{ date('Y') }} GameTradeHub — All transactions escrow-protected
    </footer>

    @stack('scripts')
</body>
</html>
