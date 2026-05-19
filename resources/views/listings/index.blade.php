@extends('layouts.app')
@section('title', 'GameTradeHub — Buy & Sell Game Accounts')

@section('content')

{{-- ═══ HERO SECTION ═══════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="
    background: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%),
                linear-gradient(180deg, #030712 0%, #0f0f1a 100%);
">
    {{-- Grid overlay --}}
    <div class="absolute inset-0 opacity-30" style="
        background-image: linear-gradient(rgba(99,102,241,0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(99,102,241,0.05) 1px, transparent 1px);
        background-size: 50px 50px;
    "></div>

    {{-- Glowing orbs --}}
    <div class="absolute top-20 left-1/4 w-64 h-64 rounded-full opacity-10"
         style="background: radial-gradient(circle, #6366f1, transparent);
                filter: blur(60px);"></div>
    <div class="absolute bottom-10 right-1/4 w-48 h-48 rounded-full opacity-10"
         style="background: radial-gradient(circle, #8b5cf6, transparent);
                filter: blur(40px);"></div>

    <div class="max-w-7xl mx-auto px-4 py-16 relative z-10">
        <div class="max-w-3xl mx-auto text-center mb-10">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-indigo-500/10 border
                        border-indigo-500/20 rounded-full px-4 py-1.5 mb-5">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-xs text-indigo-300 tracking-wider font-semibold">
                    LIVE MARKETPLACE
                </span>
            </div>

            {{-- Headline --}}
            <h1 class="font-game text-4xl md:text-5xl font-black mb-4 leading-tight">
                <span class="text-white">BUY & SELL</span><br>
                <span style="background: linear-gradient(135deg, #6366f1, #a78bfa, #06b6d4);
                             -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    GAME ACCOUNTS
                </span>
            </h1>

            <p class="text-gray-400 text-lg mb-8 leading-relaxed font-light">
                The safest marketplace for gaming accounts.<br>
                <span class="text-indigo-300">Escrow-protected</span> transactions. Verified sellers. Instant delivery.
            </p>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('home') }}"
                  class="flex gap-2 max-w-xl mx-auto mb-6">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></span>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by game, rank, server..."
                           class="w-full bg-gray-900/80 border border-gray-700 rounded-xl
                                  pl-10 pr-4 py-3 text-sm text-white placeholder-gray-500
                                  focus:outline-none focus:border-indigo-500
                                  backdrop-blur-sm transition">
                </div>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white
                               font-game font-bold text-xs px-5 py-3 rounded-xl
                               transition tracking-wider"
                        style="box-shadow: 0 0 20px rgba(99,102,241,0.3)">
                    SEARCH
                </button>
            </form>

            {{-- Stats --}}
            <div class="flex items-center justify-center gap-6 text-sm">
                @php
                    $totalListings = \App\Models\Listing::where('status','active')->count();
                    $totalSales    = \App\Models\Transaction::where('status','completed')->count();
                    $totalSellers  = \App\Models\User::where('total_sales','>',0)->count();
                @endphp
                <div class="text-center">
                    <div class="font-game font-bold text-indigo-400 text-lg">
                        {{ number_format($totalListings) }}+
                    </div>
                    <div class="text-xs text-gray-500 tracking-wider">LISTINGS</div>
                </div>
                <div class="w-px h-8 bg-gray-800"></div>
                <div class="text-center">
                    <div class="font-game font-bold text-green-400 text-lg">
                        {{ number_format($totalSales) }}+
                    </div>
                    <div class="text-xs text-gray-500 tracking-wider">SOLD</div>
                </div>
                <div class="w-px h-8 bg-gray-800"></div>
                <div class="text-center">
                    <div class="font-game font-bold text-yellow-400 text-lg">
                        {{ number_format($totalSellers) }}+
                    </div>
                    <div class="text-xs text-gray-500 tracking-wider">SELLERS</div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ═══ GAME CATEGORIES ═══════════════════════════════════════ --}}
<div class="bg-gray-950 border-b border-gray-900">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide">

            <a href="{{ route('home') }}"
               class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl
                      text-xs font-bold transition whitespace-nowrap
                      {{ !request('game_id') ? 'bg-indigo-600 text-white' : 'bg-gray-900 text-gray-400 hover:bg-gray-800 hover:text-white border border-gray-800' }}">
                🎮 All Games
            </a>

            @php
                $gameIcons = [
                    'mobile-legends' => '⚔️',
                    'valorant'       => '🎯',
                    'pubg-mobile'    => '🔫',
                    'genshin-impact' => '✨',
                    'free-fire'      => '🔥',
                ];
            @endphp

            @foreach($games as $game)
            <a href="{{ route('home', ['game_id' => $game->id]) }}"
               class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl
                      text-xs font-bold transition whitespace-nowrap
                      {{ request('game_id') == $game->id
                         ? 'bg-indigo-600 text-white'
                         : 'bg-gray-900 text-gray-400 hover:bg-gray-800 hover:text-white border border-gray-800' }}">
                {{ $gameIcons[$game->slug] ?? '🎮' }}
                {{ $game->name }}
                @php
                    $count = \App\Models\Listing::where('game_id',$game->id)
                             ->where('status','active')->count();
                @endphp
                @if($count > 0)
                <span class="bg-gray-800 text-gray-400 px-1.5 py-0.5
                             rounded-full text-xs">{{ $count }}</span>
                @endif
            </a>
            @endforeach

            <div class="w-px h-6 bg-gray-800 flex-shrink-0"></div>

            <a href="{{ route('auctions.index') }}"
               class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl
                      text-xs font-bold bg-yellow-500/10 border border-yellow-500/20
                      text-yellow-400 hover:bg-yellow-500/20 transition whitespace-nowrap">
                🏆 Live Auctions
                @php $auctionCount = \App\Models\Listing::where('type','auction')->where('status','active')->count(); @endphp
                @if($auctionCount > 0)
                <span class="bg-yellow-500 text-black px-1.5 py-0.5 rounded-full text-xs font-black">
                    {{ $auctionCount }}
                </span>
                @endif
            </a>

        </div>
    </div>
</div>

{{-- ═══ LIVE AUCTIONS STRIP ══════════════════════════════════ --}}
@if($liveAuctions->count() > 0)
<div class="bg-gradient-to-r from-yellow-500/5 via-amber-500/5 to-yellow-500/5
            border-b border-yellow-500/10">
    <div class="max-w-7xl mx-auto px-4 py-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                <span class="font-game text-xs font-bold text-yellow-400 tracking-wider">
                    LIVE AUCTIONS
                </span>
            </div>
            <a href="{{ route('auctions.index') }}"
               class="text-xs text-yellow-400/60 hover:text-yellow-400 transition">
                View all →
            </a>
        </div>
        <div class="grid grid-cols-4 gap-3">
            @foreach($liveAuctions as $auction)
            <a href="{{ route('auctions.show', $auction) }}"
               class="bg-gray-900/60 border border-yellow-500/10 rounded-xl p-3
                      hover:border-yellow-500/30 transition block">
                <div class="text-xs text-yellow-400/70 font-bold mb-1">
                    {{ $auction->game->name }}
                </div>
                <div class="text-sm font-semibold text-white mb-2 line-clamp-1">
                    {{ $auction->title }}
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">
                            {{ $auction->current_bid ? 'Current' : 'Starting' }}
                        </div>
                        <div class="font-game font-bold text-yellow-400 text-sm">
                            ${{ number_format($auction->current_bid ?? $auction->starting_price, 2) }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Ends</div>
                        <div class="text-xs text-red-400 font-bold">
                            {{ $auction->timeRemaining() }}
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ═══ MAIN BROWSE SECTION ══════════════════════════════════ --}}
<div class="bg-gray-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Filter + Sort Bar --}}
        <form method="GET" action="{{ route('home') }}">
            @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('game_id'))
            <input type="hidden" name="game_id" value="{{ request('game_id') }}">
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-game text-lg font-bold text-white tracking-wider">
                        @if(request('search'))
                            RESULTS FOR "{{ strtoupper(request('search')) }}"
                        @elseif(request('game_id'))
                            {{ strtoupper($games->find(request('game_id'))->name ?? 'ACCOUNTS') }}
                        @else
                            ALL ACCOUNTS
                        @endif
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $listings->total() }} accounts available
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Platform filter --}}
                    <select name="platform"
                            onchange="this.form.submit()"
                            class="bg-gray-900 border border-gray-800 rounded-xl px-3 py-2
                                   text-xs text-gray-400 focus:outline-none
                                   focus:border-indigo-500 cursor-pointer">
                        <option value="">All Platforms</option>
                        <option value="Mobile"  {{ request('platform') === 'Mobile'  ? 'selected' : '' }}>📱 Mobile</option>
                        <option value="PC"      {{ request('platform') === 'PC'      ? 'selected' : '' }}>🖥️ PC</option>
                        <option value="Console" {{ request('platform') === 'Console' ? 'selected' : '' }}>🎮 Console</option>
                    </select>

                    {{-- Sort --}}
                    <select name="sort"
                            onchange="this.form.submit()"
                            class="bg-gray-900 border border-gray-800 rounded-xl px-3 py-2
                                   text-xs text-gray-400 focus:outline-none
                                   focus:border-indigo-500 cursor-pointer">
                        <option value="">⏰ Latest</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>💲 Price: Low</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>💰 Price: High</option>
                        <option value="popular"    {{ request('sort') === 'popular'    ? 'selected' : '' }}>🔥 Popular</option>
                    </select>

                    {{-- Price range --}}
                    <div class="flex items-center gap-1 bg-gray-900 border border-gray-800
                                rounded-xl px-3 py-2">
                        <span class="text-xs text-gray-600">$</span>
                        <input type="number" name="min_price"
                               value="{{ request('min_price') }}"
                               placeholder="Min"
                               class="w-14 bg-transparent text-xs text-gray-400
                                      focus:outline-none placeholder-gray-700">
                        <span class="text-xs text-gray-600">—</span>
                        <input type="number" name="max_price"
                               value="{{ request('max_price') }}"
                               placeholder="Max"
                               class="w-14 bg-transparent text-xs text-gray-400
                                      focus:outline-none placeholder-gray-700">
                        <button type="submit"
                                class="text-xs text-indigo-400 hover:text-indigo-300 ml-1">
                            ↵
                        </button>
                    </div>

                    @if(request()->hasAny(['platform','sort','min_price','max_price']))
                    <a href="{{ route('home', request()->only(['search','game_id'])) }}"
                       class="text-xs text-gray-600 hover:text-gray-400 transition px-2">
                        ✕ Clear
                    </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Listing Grid --}}
        @php
            $pendingOwnerCount = Auth::check()
                ? $listings->filter(fn($listing) => $listing->user_id === auth()->id() && $listing->status !== 'active')->count()
                : 0;
        @endphp
        @if($pendingOwnerCount > 0)
        <div class="mb-6 rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-4 text-sm text-yellow-100">
            Note: your own listing(s) under review are still shown to you here but remain hidden from other buyers until approved.
        </div>
        @endif

        @forelse($listings as $listing)
        @if($loop->first)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        @endif

            <a href="{{ route('listings.show', $listing) }}"
               class="group bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden
                      hover:border-indigo-500/50 transition-all duration-300
                      hover:-translate-y-1 hover:shadow-xl
                      hover:shadow-indigo-500/10 block">

                {{-- Image --}}
                <div class="relative h-36 overflow-hidden"
                     style="background: linear-gradient(135deg, #0f0f1a, #1a1a2e)">

                    @if($listing->firstImage)
                    <img src="{{ $listing->firstImage->url }}"
                         class="w-full h-full object-cover opacity-70
                                group-hover:opacity-90 group-hover:scale-105
                                transition-all duration-500">
                    @else
                    {{-- Placeholder with game icon --}}
                    @php
                        $gameIcons = ['mobile-legends'=>'⚔️','valorant'=>'🎯','pubg-mobile'=>'🔫','genshin-impact'=>'✨','free-fire'=>'🔥'];
                        $icon = $gameIcons[$listing->game->slug] ?? '🎮';
                    @endphp
                    <div class="w-full h-full flex items-center justify-center text-5xl opacity-20">
                        {{ $icon }}
                    </div>
                    @endif

                    {{-- Game badge --}}
                    <div class="absolute top-2 left-2">
                        <span class="bg-black/70 backdrop-blur-sm text-indigo-300
                                     text-xs px-2 py-0.5 rounded-full font-bold
                                     border border-indigo-500/20">
                            {{ $listing->game->name }}
                        </span>
                    </div>

                    {{-- Featured badge --}}
                    @if($listing->is_featured)
                    <div class="absolute top-2 right-2">
                        <span class="bg-yellow-500 text-black text-xs px-2 py-0.5
                                     rounded-full font-black">⭐ TOP</span>
                    </div>
                    @endif

                    {{-- Views --}}
                    <div class="absolute bottom-2 right-2">
                        <span class="bg-black/50 text-gray-400 text-xs px-2 py-0.5 rounded-full">
                            👁 {{ $listing->views_count }}
                        </span>
                    </div>

                </div>

                {{-- Body --}}
                <div class="p-3">

                    {{-- Title --}}
                    <h3 class="font-semibold text-sm text-white leading-tight
                               mb-2 line-clamp-2 group-hover:text-indigo-300 transition">
                        {{ $listing->title }}
                    </h3>

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-1 mb-3">
                        @if($listing->rank)
                        <span class="text-xs bg-gray-800 border border-gray-700
                                     px-2 py-0.5 rounded-full text-gray-400">
                            🏆 {{ $listing->rank }}
                        </span>
                        @endif
                        @if($listing->server)
                        <span class="text-xs bg-gray-800 border border-gray-700
                                     px-2 py-0.5 rounded-full text-gray-400">
                            🌐 {{ $listing->server }}
                        </span>
                        @endif
                        @if($listing->platform)
                        <span class="text-xs bg-gray-800 border border-gray-700
                                     px-2 py-0.5 rounded-full text-gray-400">
                            {{ $listing->platform === 'Mobile' ? '📱' : ($listing->platform === 'PC' ? '🖥️' : '🎮') }}
                            {{ $listing->platform }}
                        </span>
                        @endif

                        @if(Auth::check() && auth()->id() === $listing->user_id && $listing->status !== 'active')
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]
                                     bg-yellow-500/10 border border-yellow-500/20
                                     text-yellow-300 px-2 py-1 rounded-full">
                            {{ strtoupper($listing->status) }}
                        </span>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-2
                                border-t border-gray-800">
                        <div>
                            <div class="font-game font-bold text-green-400 text-base">
                                ${{ number_format($listing->price, 2) }}
                            </div>
                            @if($listing->seller->rating_avg > 0)
                            <div class="text-xs text-yellow-400">
                                ⭐ {{ number_format($listing->seller->rating_avg, 1) }}
                            </div>
                            @endif
                        </div>
                        <div class="bg-indigo-600/20 border border-indigo-500/30
                                    group-hover:bg-indigo-600 text-indigo-400
                                    group-hover:text-white text-xs px-3 py-1.5
                                    rounded-lg font-bold transition-all duration-300">
                            VIEW →
                        </div>
                    </div>

                </div>
            </a>

        @if($loop->last)
        </div>
        @endif

        @empty
        {{-- Empty state --}}
        <div class="text-center py-20">
            <div class="text-6xl mb-4 opacity-30">🎮</div>
            <h3 class="font-game text-xl font-bold text-gray-500 mb-2">
                NO ACCOUNTS FOUND
            </h3>
            <p class="text-gray-600 text-sm mb-5">
                Try different filters or check back later
            </p>
            @if(request()->hasAny(['search','game_id','platform','sort','min_price','max_price']))
            <a href="{{ route('home') }}"
               class="inline-flex bg-indigo-600 hover:bg-indigo-500 text-white
                      text-sm font-bold px-5 py-2.5 rounded-xl transition">
                Clear Filters
            </a>
            @endif
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($listings->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $listings->withQueryString()->links() }}
        </div>
        @endif

    </div>
</div>

{{-- ═══ TRUST SECTION ════════════════════════════════════════ --}}
@if(!request()->hasAny(['search','game_id']))
<div class="border-t border-gray-900 bg-gray-950 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-4 gap-6">
            @foreach([
                ['🔒', 'Escrow Protection', 'Funds held safely until you confirm receipt'],
                ['✅', 'Verified Sellers', 'All sellers go through our onboarding process'],
                ['⭐', 'Buyer Reviews', 'Real reviews from verified buyers only'],
                ['⚡', 'Instant Listing', 'Sell your account in minutes, not days'],
            ] as [$icon, $title, $desc])
            <div class="text-center">
                <div class="text-3xl mb-3">{{ $icon }}</div>
                <div class="font-game text-xs font-bold text-white tracking-wider mb-1">
                    {{ strtoupper($title) }}
                </div>
                <div class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
