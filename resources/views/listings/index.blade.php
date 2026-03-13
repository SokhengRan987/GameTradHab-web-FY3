@extends('layouts.app')
@section('title', 'Browse Listings')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">🛒 Browse Game Accounts</h1>
        <p class="text-gray-400 text-sm">{{ $listings->total() }} accounts available</p>
    </div>

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('listings.index') }}" class="mb-6">
        <div class="flex gap-3 mb-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by title, game, rank..."
                   class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5
                          text-sm text-white placeholder-gray-500 focus:outline-none
                          focus:border-indigo-500">
            <select name="game_id"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                           text-sm text-white focus:outline-none focus:border-indigo-500">
                <option value="">All Games</option>
                @foreach($games as $game)
                <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                    {{ $game->name }}
                </option>
                @endforeach
            </select>
            <select name="platform"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                           text-sm text-white focus:outline-none focus:border-indigo-500">
                <option value="">All Platforms</option>
                <option value="Mobile"  {{ request('platform') === 'Mobile'  ? 'selected' : '' }}>📱 Mobile</option>
                <option value="PC"      {{ request('platform') === 'PC'      ? 'selected' : '' }}>🖥️ PC</option>
                <option value="Console" {{ request('platform') === 'Console' ? 'selected' : '' }}>🎮 Console</option>
            </select>
            <select name="sort"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                           text-sm text-white focus:outline-none focus:border-indigo-500">
                <option value="">Latest</option>
                <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Price ↑</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price ↓</option>
                <option value="popular"    {{ request('sort') === 'popular'    ? 'selected' : '' }}>Popular</option>
            </select>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5
                           rounded-xl text-sm font-semibold transition">
                Search
            </button>
            @if(request()->hasAny(['search','game_id','platform','sort']))
            <a href="{{ route('listings.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2.5
                      rounded-xl text-sm transition">
                Clear
            </a>
            @endif
        </div>
    </form>

    {{-- Listing Grid --}}
    @forelse($listings as $listing)
    @if($loop->first)
    <div class="grid grid-cols-4 gap-4 mb-8">
    @endif

        <a href="{{ route('listings.show', $listing) }}"
           class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden
                  hover:border-indigo-500/50 hover:-translate-y-1 transition-all duration-200 block">

            {{-- Image --}}
            <div class="h-32 bg-gradient-to-br from-gray-800 to-gray-900
                        flex items-center justify-center text-4xl relative">
                🎮
                @if($listing->is_featured)
                <span class="absolute top-2 left-2 bg-yellow-500 text-black
                             text-xs font-bold px-2 py-0.5 rounded">
                    ⭐ FEATURED
                </span>
                @endif
                @if($listing->firstImage)
                <img src="{{ $listing->firstImage->url }}"
                     class="absolute inset-0 w-full h-full object-cover opacity-60">
                @endif
            </div>

            {{-- Body --}}
            <div class="p-3">
                <div class="text-xs font-bold text-indigo-400 uppercase tracking-wide mb-1">
                    {{ $listing->game->name }}
                </div>
                <div class="font-bold text-sm leading-tight mb-2 line-clamp-2">
                    {{ $listing->title }}
                </div>
                <div class="flex gap-1 flex-wrap mb-3">
                    @if($listing->rank)
                    <span class="text-xs bg-gray-800 border border-gray-700 px-2 py-0.5 rounded text-gray-400">
                        {{ $listing->rank }}
                    </span>
                    @endif
                    @if($listing->server)
                    <span class="text-xs bg-gray-800 border border-gray-700 px-2 py-0.5 rounded text-gray-400">
                        {{ $listing->server }}
                    </span>
                    @endif
                    <span class="text-xs bg-gray-800 border border-gray-700 px-2 py-0.5 rounded text-gray-400">
                        {{ $listing->platform }}
                    </span>
                </div>
                <div class="flex items-center justify-between border-t border-gray-800 pt-2">
                    <div class="text-lg font-bold text-green-400 font-mono">
                        ${{ number_format($listing->price, 2) }}
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-500">
                        👁️ {{ $listing->views_count }}
                    </div>
                </div>
            </div>
        </a>

    @if($loop->last)
    </div>
    @endif

    @empty
    <div class="text-center py-20 text-gray-500">
        <div class="text-5xl mb-4">🔍</div>
        <div class="text-lg font-semibold mb-2">No listings found</div>
        <p class="text-sm">Try adjusting your search filters</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $listings->withQueryString()->links() }}
    </div>

</div>
@endsection
