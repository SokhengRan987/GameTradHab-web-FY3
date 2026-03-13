<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'GameTradeHub')</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-52 bg-gray-900 border-r border-gray-800 flex flex-col fixed top-0 bottom-0">
        {{-- Logo --}}
        <div class="px-4 py-4 border-b border-gray-800">
            <div class="font-bold text-sm flex items-center gap-2">
                <div class="w-7 h-7 bg-sky-600 rounded-lg flex items-center justify-center text-xs">⚡</div>
                <span class="text-sky-400">Admin</span> Panel
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 overflow-y-auto">

            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mb-2">
                Overview
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1
                      {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600/20 text-sky-400 font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                📈 Dashboard
            </a>

            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mt-4 mb-2">
                Manage
            </div>
            <a href="{{ route('admin.listings.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1
                      {{ request()->routeIs('admin.listings.*') ? 'bg-sky-600/20 text-sky-400 font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                🛒 Listings
                @php $pending = \App\Models\Listing::where('status','pending')->count() @endphp
                @if($pending > 0)
                <span class="ml-auto bg-yellow-500 text-black text-xs px-1.5 py-0.5 rounded-full font-bold">
                    {{ $pending }}
                </span>
                @endif
            </a>
            <a href="{{ route('admin.transactions.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1
                      {{ request()->routeIs('admin.transactions.*') ? 'bg-sky-600/20 text-sky-400 font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                💳 Transactions
                @php $disputes = \App\Models\Transaction::where('status','disputed')->count() @endphp
                @if($disputes > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">
                    {{ $disputes }}
                </span>
                @endif
            </a>

        </nav>

        {{-- Bottom --}}
        <div class="px-3 py-3 border-t border-gray-800">
            <a href="{{ route('home') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-800">
                ← Back to Site
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-gray-800">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="ml-52 flex-1 p-6">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/25 text-green-400
                    px-4 py-3 rounded-xl text-sm mb-4 flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/25 text-red-400
                    px-4 py-3 rounded-xl text-sm mb-4 flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
