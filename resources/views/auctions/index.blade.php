@extends('layouts.app')
@section('title', 'Live Auctions')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">🏆 Live Auctions</h1>
        <p class="text-gray-400 text-sm">{{ $listings->total() }} auctions running now</p>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('auctions.index') }}" class="mb-6">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search auctions..."
                   class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5
                          text-sm text-white placeholder-gray-500 focus:outline-none
                          focus:border-indigo-500">
            <select name="game_id"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                           text-sm text-white focus:outline-none focus:border-indigo-500">
                <option value="">All Games</option>
                @foreach($games as $game)
                <option value="{{ $game->id }}"
                    {{ request('game_id') == $game->id ? 'selected' : '' }}>
                    {{ $game->name }}
                </option>
                @endforeach
            </select>
            <select name="sort"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                           text-sm text-white focus:outline-none focus:border-indigo-500">
                <option value="">Latest</option>
                <option value="ending_soon"  {{ request('sort') === 'ending_soon'  ? 'selected' : '' }}>⏰ Ending Soon</option>
                <option value="highest_bid"  {{ request('sort') === 'highest_bid'  ? 'selected' : '' }}>💰 Highest Bid</option>
                <option value="lowest_bid"   {{ request('sort') === 'lowest_bid'   ? 'selected' : '' }}>📉 Lowest Bid</option>
            </select>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5
                           rounded-xl text-sm font-semibold transition">
                Search
            </button>
            @if(request()->hasAny(['search','game_id','sort']))
            <a href="{{ route('auctions.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2.5
                      rounded-xl text-sm transition">
                Clear
            </a>
            @endif
        </div>
    </form>

    {{-- Auction Grid --}}
    @forelse($listings as $listing)
    @if($loop->first)
    <div class="grid grid-cols-4 gap-4 mb-8">
    @endif

        <a href="{{ route('auctions.show', $listing) }}"
           class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden
                  hover:border-yellow-500/50 hover:-translate-y-1
                  transition-all duration-200 block">

            {{-- Image --}}
            <div class="h-32 bg-gradient-to-br from-gray-800 to-gray-900
                        flex items-center justify-center text-4xl relative">
                🏆
                @if($listing->firstImage)
                <img src="{{ $listing->firstImage->url }}"
                     class="absolute inset-0 w-full h-full object-cover opacity-60">
                @endif

                {{-- Time remaining badge --}}
                <div class="absolute bottom-2 right-2 bg-black/70 backdrop-blur
                            text-xs px-2 py-1 rounded-full font-bold
                            {{ $listing->auction_ends_at->diffInHours() < 1
                               ? 'text-red-400' : 'text-yellow-400' }}">
                    ⏰ {{ $listing->timeRemaining() }}
                </div>
            </div>

            {{-- Body --}}
            <div class="p-3">
                <div class="text-xs font-bold text-yellow-400 uppercase tracking-wide mb-1">
                    {{ $listing->game->name }}
                </div>
                <div class="font-bold text-sm leading-tight mb-2 line-clamp-2">
                    {{ $listing->title }}
                </div>

                {{-- Bid info --}}
                <div class="bg-gray-800 rounded-xl p-2 mb-2">
                    @if($listing->current_bid)
                    <div class="text-xs text-gray-400 mb-0.5">Current Bid</div>
                    <div class="text-lg font-bold text-yellow-400 font-mono">
                        ${{ number_format($listing->current_bid, 2) }}
                    </div>
                    {{-- <div class="text-xs text-gray-500">
                        by {{ $listing->highestBidder->name ?? '—' }}
                    </div> --}}
                    <td class="px-4 py-3 text-sm font-bold text-yellow-400">
                        {{ $listing->current_bid
                        ? '$'.number_format($listing->current_bid, 2)
                        : '—' }}
                    </td>
                    @else
                    <div class="text-xs text-gray-400 mb-0.5">Starting Bid</div>
                    <div class="text-lg font-bold text-green-400 font-mono">
                        ${{ number_format($listing->starting_price, 2) }}
                    </div>
                    <div class="text-xs text-gray-500">No bids yet</div>
                    @endif
                </div>

                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>{{ $listing->bids->count() }} bids</span>
                    <span>{{ $listing->platform }}</span>
                </div>
            </div>
        </a>

    @if($loop->last)
    </div>
    @endif

    @empty
    <div class="text-center py-20 text-gray-500">
        <div class="text-5xl mb-4">🏆</div>
        <div class="text-lg font-semibold mb-2">No live auctions</div>
        <p class="text-sm mb-4">Check back soon or create your own auction</p>
        @auth
        <a href="{{ route('auctions.create') }}"
           class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5
                  rounded-xl text-sm font-semibold transition">
            + Create Auction
        </a>
        @endauth
    </div>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $listings->withQueryString()->links() }}
    </div>

</div>
@endsection
