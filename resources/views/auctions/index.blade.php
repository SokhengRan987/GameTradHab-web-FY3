@extends('layouts.app')
@section('title', 'Live Auctions')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-game font-bold text-white mb-1">
            {{ request('status') === 'ended' ? 'Ended Auctions' : 'Live Auctions' }}
        </h1>

        <p class="text-gray-400">
            {{ $listings->total() }}
            {{ request('status') === 'ended' ? 'auctions finished' : 'auctions running now' }}
        </p>
    </div>

    {{-- ✅ ADD THIS BLOCK HERE --}}
    <div class="flex gap-3 mb-6">

        <a href="{{ route('auctions.index', ['status' => 'active']) }}"
        class="px-4 py-2 rounded-lg text-sm font-semibold
        {{ request('status') !== 'ended'
            ? 'bg-green-600 text-white'
            : 'bg-gray-800 text-gray-400' }}">
            ✅ Live
        </a>

        <a href="{{ route('auctions.index', ['status' => 'ended']) }}"
        class="px-4 py-2 rounded-lg text-sm font-semibold
        {{ request('status') === 'ended'
            ? 'bg-red-600 text-white'
            : 'bg-gray-800 text-gray-400' }}">
            🏁 Ended
        </a>

    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('auctions.index') }}" class="mb-8">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search auctions..."
                   class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                          text-sm text-white placeholder-gray-500 focus:outline-none
                          focus:border-indigo-500">

            <select name="game_id"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
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
                    class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                           text-sm text-white focus:outline-none focus:border-indigo-500">
                <option value="">Latest</option>
                <option value="ending_soon"  {{ request('sort') === 'ending_soon'  ? 'selected' : '' }}>Ending Soon</option>
                <option value="highest_bid"  {{ request('sort') === 'highest_bid'  ? 'selected' : '' }}>Highest Bid</option>
                <option value="lowest_bid"   {{ request('sort') === 'lowest_bid'   ? 'selected' : '' }}>Lowest Bid</option>
            </select>

            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3
                           rounded-xl text-sm font-semibold transition">
                Search
            </button>

            @if(request()->hasAny(['search','game_id','sort']))
            <a href="{{ route('auctions.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-3
                      rounded-xl text-sm transition flex items-center">
                Clear
            </a>
            @endif
        </div>
    </form>

    {{-- Auction Grid --}}
    @forelse($listings as $listing)
    @if($loop->first)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @endif

        <a href="{{ route('auctions.show', $listing) }}"
           class="group bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden
                  hover:border-yellow-500/50 hover:-translate-y-1
                  transition-all duration-300 block">

            {{-- Image --}}
            <div class="h-40 bg-gradient-to-br from-gray-800 to-gray-900 relative overflow-hidden">

                @if($listing->firstImage)
                <img src="{{ $listing->firstImage->url }}"
                     class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover:opacity-90 transition">
                @else
                <div class="absolute inset-0 flex items-center justify-center text-6xl opacity-10">
                    ♟️
                </div>
                @endif

                {{-- ✅ ENDED badge --}}
                @if($listing->status === 'inactive')
                <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                    ENDED
                </div>
                @endif

                {{-- Time remaining badge --}}
                @php
                    $hoursLeft = now()->utc()->diffInHours($listing->auction_ends_at, false);
                @endphp

                @if($listing->isLive())
                    <div class="absolute bottom-3 right-3 bg-black/80 backdrop-blur-md
                                text-xs px-3 py-1 rounded-full font-bold
                                {{ $hoursLeft < 1 ? 'text-red-400' : 'text-yellow-400' }}">
                        {{ $listing->timeRemaining() }}
                    </div>
                @else
                    <div class="absolute bottom-3 right-3 bg-gray-900/80 text-gray-400
                                text-xs px-3 py-1 rounded-full font-bold">
                        Ended
                    </div>
                @endif

                @if($listing->is_featured)
                <div class="absolute top-3 left-3 bg-yellow-500 text-black text-xs font-black px-3 py-1 rounded-full">
                    FEATURED
                </div>
                @endif
            </div>

            {{-- Body --}}
            <div class="p-5">
                <div class="text-xs font-bold text-yellow-400 uppercase tracking-widest mb-2">
                    {{ $listing->game->name }}
                </div>

                <h3 class="font-semibold text-base leading-tight mb-4 line-clamp-2 group-hover:text-indigo-300 transition">
                    {{ $listing->title }}
                </h3>

                {{-- Bid info --}}
                <div class="bg-gray-800/70 rounded-xl p-4 mb-4">
                    @if($listing->current_bid)
                    <div class="text-xs text-gray-400 mb-1">CURRENT BID</div>
                    <div class="text-2xl font-game font-bold text-yellow-400">
                        ${{ number_format($listing->current_bid, 2) }}
                    </div>
                    @else
                    <div class="text-xs text-gray-400 mb-1">STARTING PRICE</div>
                    <div class="text-2xl font-game font-bold text-emerald-400">
                        ${{ number_format($listing->starting_price, 2) }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">No bids yet</div>
                    @endif
                </div>

                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>{{ $listing->bids_count }} bids</span>
                    <span>{{ $listing->platform ?? '—' }}</span>
                </div>
            </div>
        </a>

    @if($loop->last)
    </div>
    @endif

    @empty
    <div class="text-center py-24 text-gray-400">
        <div class="mx-auto w-16 h-16 bg-gray-800 rounded-2xl flex items-center justify-center mb-6">
            <span class="text-4xl opacity-30">🏆</span>
        </div>
        <h3 class="text-xl font-semibold text-gray-300 mb-2">
            {{ request('status') === 'ended'
                ? 'No Ended Auctions Yet'
                : 'No Live Auctions Right Now' }}
        </h3>

        <p class="text-sm max-w-sm mx-auto">
            {{ request('status') === 'ended'
                ? 'Once auctions finish, they will appear here.'
                : 'Check back later or create your own auction.' }}
        </p>

        @auth
        <a href="{{ route('auctions.create') }}"
           class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3
                  rounded-xl text-sm font-semibold transition">
            Create New Auction
        </a>
        @endauth
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($listings->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $listings->withQueryString()->links() }}
    </div>
    @endif

</div>
@endsection
