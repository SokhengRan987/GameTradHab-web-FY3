@extends('layouts.app')
@section('title', 'GameTradeHub — Buy & Sell Game Accounts')

@section('content')

{{-- ═══ HERO SECTION ═══════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="
    background: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%),
                linear-gradient(180deg, #030712 0%, #0f0f1a 100%);
">
    <div class="absolute inset-0 opacity-30" style="
        background-image: linear-gradient(rgba(99,102,241,0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(99,102,241,0.05) 1px, transparent 1px);
        background-size: 50px 50px;
    "></div>
    <div class="absolute top-20 left-1/4 w-64 h-64 rounded-full opacity-10"
         style="background: radial-gradient(circle, #6366f1, transparent); filter: blur(60px);"></div>
    <div class="absolute bottom-10 right-1/4 w-48 h-48 rounded-full opacity-10"
         style="background: radial-gradient(circle, #8b5cf6, transparent); filter: blur(40px);"></div>

    <div class="max-w-7xl mx-auto px-4 py-16 relative z-10">
        <div class="max-w-3xl mx-auto text-center mb-10">

            <div class="inline-flex items-center gap-2 bg-indigo-500/10 border
                        border-indigo-500/20 rounded-full px-4 py-1.5 mb-5">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-xs text-indigo-300 tracking-wider font-semibold">LIVE MARKETPLACE</span>
            </div>

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
            <form id="searchForm" method="GET" action="{{ route('home') }}"
                  class="flex gap-2 max-w-xl mx-auto mb-6">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by game, rank, server..."
                           class="w-full bg-gray-900/80 border border-gray-700 rounded-xl
                                  pl-10 pr-4 py-3 text-sm text-white placeholder-gray-500
                                  focus:outline-none focus:border-indigo-500 backdrop-blur-sm transition">
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
                    <div class="font-game font-bold text-indigo-400 text-lg">{{ number_format($totalListings) }}+</div>
                    <div class="text-xs text-gray-500 tracking-wider">LISTINGS</div>
                </div>
                <div class="w-px h-8 bg-gray-800"></div>
                <div class="text-center">
                    <div class="font-game font-bold text-green-400 text-lg">{{ number_format($totalSales) }}+</div>
                    <div class="text-xs text-gray-500 tracking-wider">SOLD</div>
                </div>
                <div class="w-px h-8 bg-gray-800"></div>
                <div class="text-center">
                    <div class="font-game font-bold text-yellow-400 text-lg">{{ number_format($totalSellers) }}+</div>
                    <div class="text-xs text-gray-500 tracking-wider">SELLERS</div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ═══ GAME CATEGORIES ═══════════════════════════════════════ --}}
<div class="bg-gray-950 border-b border-gray-900 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-hide">

            <a href="{{ route('home', request()->only(['search','sort','platform','min_price','max_price'])) }}"
               data-filter
               class="filter-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl
                      text-xs font-bold transition whitespace-nowrap
                      {{ !request('game_id') ? 'bg-indigo-600 text-white' : 'bg-gray-900 text-gray-400 hover:bg-gray-800 hover:text-white border border-gray-800' }}">
                All Games
            </a>

            @foreach($games as $game)
            <a href="{{ route('home', array_merge(request()->only(['search','sort','platform','min_price','max_price']), ['game_id' => $game->id])) }}"
               data-filter
               class="filter-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl
                      text-xs font-bold transition whitespace-nowrap
                      {{ request('game_id') == $game->id
                         ? 'bg-indigo-600 text-white'
                         : 'bg-gray-900 text-gray-400 hover:bg-gray-800 hover:text-white border border-gray-800' }}">
                {{ $game->name }}
                @php
                    $count = \App\Models\Listing::where('game_id',$game->id)->where('status','active')->count();
                @endphp
                @if($count > 0)
                <span class="bg-gray-800 text-gray-400 px-1.5 py-0.5 rounded-full text-xs">{{ $count }}</span>
                @endif
            </a>
            @endforeach

            <div class="w-px h-6 bg-gray-800 flex-shrink-0"></div>

            <a href="{{ route('auctions.index') }}"
               class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl
                      text-xs font-bold bg-yellow-500/10 border border-yellow-500/20
                      text-yellow-400 hover:bg-yellow-500/20 transition whitespace-nowrap">
                Live Auctions
                @php $auctionCount = \App\Models\Listing::where('type','auction')->where('status','active')->count(); @endphp
                @if($auctionCount > 0)
                <span class="bg-yellow-500 text-black px-1.5 py-0.5 rounded-full text-xs font-black">{{ $auctionCount }}</span>
                @endif
            </a>

        </div>
    </div>
</div>

{{-- ═══ LIVE AUCTIONS STRIP ══════════════════════════════════ --}}
@if($liveAuctions->count() > 0)
<div class="bg-gradient-to-r from-yellow-500/5 via-amber-500/5 to-yellow-500/5 border-b border-yellow-500/10">
    <div class="max-w-7xl mx-auto px-4 py-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                <span class="font-game text-xs font-bold text-yellow-400 tracking-wider">LIVE AUCTIONS</span>
            </div>
            <a href="{{ route('auctions.index') }}" class="text-xs text-yellow-400/60 hover:text-yellow-400 transition">View all →</a>
        </div>
        <div class="grid grid-cols-4 gap-3">
            @foreach($liveAuctions as $auction)
            <a href="{{ route('auctions.show', $auction) }}"
               class="bg-gray-900/60 border border-yellow-500/10 rounded-xl p-3 hover:border-yellow-500/30 transition block">
                <div class="text-xs text-yellow-400/70 font-bold mb-1">{{ $auction->game->name }}</div>
                <div class="text-sm font-semibold text-white mb-2 line-clamp-1">{{ $auction->title }}</div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">{{ $auction->current_bid ? 'Current' : 'Starting' }}</div>
                        <div class="font-game font-bold text-yellow-400 text-sm">
                            ${{ number_format($auction->current_bid ?? $auction->starting_price, 2) }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Ends</div>
                        <div class="text-xs text-red-400 font-bold">{{ $auction->timeRemaining() }}</div>
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
        <form id="filterForm" method="GET" action="{{ route('home') }}">
            @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('game_id'))
            <input type="hidden" name="game_id" value="{{ request('game_id') }}">
            @endif

            <div class="flex items-center justify-between mb-6">
                <div id="browseHeading">
                    <h2 class="font-game text-lg font-bold text-white tracking-wider">
                        @if(request('search'))
                            RESULTS FOR "{{ strtoupper(request('search')) }}"
                        @elseif(request('game_id'))
                            {{ strtoupper($games->find(request('game_id'))->name ?? 'ACCOUNTS') }}
                        @else
                            ALL ACCOUNTS
                        @endif
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $listings->total() }} accounts available</p>
                </div>

                <div class="flex items-center gap-2">
                    <select name="platform" onchange="this.form.submit()"
                            class="bg-gray-900 border border-gray-800 rounded-xl px-3 py-2
                                   text-xs text-gray-400 focus:outline-none focus:border-indigo-500 cursor-pointer">
                        <option value="">All Platforms</option>
                        <option value="Mobile"  {{ request('platform') === 'Mobile'  ? 'selected' : '' }}>Mobile</option>
                        <option value="PC"      {{ request('platform') === 'PC'      ? 'selected' : '' }}>PC</option>
                        <option value="Console" {{ request('platform') === 'Console' ? 'selected' : '' }}>Console</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()"
                            class="bg-gray-900 border border-gray-800 rounded-xl px-3 py-2
                                   text-xs text-gray-400 focus:outline-none focus:border-indigo-500 cursor-pointer">
                        <option value="">Latest</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular"    {{ request('sort') === 'popular'    ? 'selected' : '' }}>Popular</option>
                    </select>

                    <div class="flex items-center gap-1 bg-gray-900 border border-gray-800 rounded-xl px-3 py-2">
                        <span class="text-xs text-gray-600">$</span>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                               class="w-14 bg-transparent text-xs text-gray-400 focus:outline-none placeholder-gray-700">
                        <span class="text-xs text-gray-600">—</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                               class="w-14 bg-transparent text-xs text-gray-400 focus:outline-none placeholder-gray-700">
                        <button type="submit" class="text-xs text-indigo-400 hover:text-indigo-300 ml-1">↵</button>
                    </div>

                    @if(request()->hasAny(['platform','sort','min_price','max_price']))
                    <a href="{{ route('home', request()->only(['search','game_id'])) }}"
                       class="text-xs text-gray-600 hover:text-gray-400 transition px-2">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Listing Grid — this entire div is replaced on AJAX --}}
        <div id="listingsArea">
            @include('partials.listings-grid', ['listings' => $listings])
        </div>

    </div>
</div>

{{-- ═══ TRUST SECTION ════════════════════════════════════════ --}}
@if(!request()->hasAny(['search','game_id']))
<div class="border-t border-gray-900 bg-gray-950 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-4 gap-6">
            @foreach([
                ['lock', 'Escrow Protection', 'Funds held safely until you confirm receipt'],
                ['shield-check', 'Verified Sellers', 'All sellers go through our onboarding process'],
                ['star', 'Buyer Reviews', 'Real reviews from verified buyers only'],
                ['bolt', 'Instant Listing', 'Sell your account in minutes, not days'],
            ] as [$icon, $title, $desc])
            <div class="text-center">
                <div class="mb-3">
                    @if($icon === 'lock')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    @elseif($icon === 'shield-check')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-.343-.03-.67-.07-1z" />
                        </svg>
                    @elseif($icon === 'star')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.975 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L3.98 9.132c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    @elseif($icon === 'bolt')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    @endif
                </div>
                <div class="font-game text-xs font-bold text-white tracking-wider mb-1">{{ strtoupper($title) }}</div>
                <div class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ═══ AJAX CATEGORY SWITCHING ═══════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const listingsArea = document.getElementById('listingsArea');

    function setLoading(on) {
        listingsArea.style.opacity = on ? '0.4' : '1';
        listingsArea.style.pointerEvents = on ? 'none' : '';
        listingsArea.style.transition = 'opacity 0.15s ease';
    }

    function updateActiveBtn(url) {
        const params = new URL(url).searchParams;
        const activeGameId = params.get('game_id');

        document.querySelectorAll('.filter-btn').forEach(btn => {
            const btnParams = new URL(btn.href).searchParams;
            const btnGameId = btnParams.get('game_id');
            const isActive  = activeGameId ? btnGameId === activeGameId : !btnGameId;

            btn.className = btn.className
                .replace(/bg-indigo-600 text-white|bg-gray-900 text-gray-400 hover:bg-gray-800 hover:text-white border border-gray-800/g, '')
                .trim();

            btn.className += isActive
                ? ' bg-indigo-600 text-white'
                : ' bg-gray-900 text-gray-400 hover:bg-gray-800 hover:text-white border border-gray-800';
        });
    }

    async function loadListings(url) {
        setLoading(true);
        try {
            const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();

            // Parse only the #listingsArea content from the response
            const parser = new DOMParser();
            const doc    = parser.parseFromString(html, 'text/html');
            const fresh  = doc.getElementById('listingsArea');

            if (fresh) {
                listingsArea.innerHTML = fresh.innerHTML;
            }

            // Update heading count
            const freshHeading = doc.getElementById('browseHeading');
            const heading      = document.getElementById('browseHeading');
            if (freshHeading && heading) {
                heading.innerHTML = freshHeading.innerHTML;
            }

            history.pushState({}, '', url);
            updateActiveBtn(url);
        } catch (e) {
            // Fallback to normal navigation on error
            window.location.href = url;
        } finally {
            setLoading(false);
        }
    }

    // Intercept category filter clicks
    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            loadListings(btn.href);
        });
    });

    // Intercept pagination clicks (delegated, since pagination is re-rendered)
    document.addEventListener('click', e => {
        const pagerLink = e.target.closest('#listingsArea a[href*="page="]');
        if (pagerLink) {
            e.preventDefault();
            loadListings(pagerLink.href);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Handle browser back/forward
    window.addEventListener('popstate', () => {
        loadListings(window.location.href);
    });
});
</script>

@endsection
