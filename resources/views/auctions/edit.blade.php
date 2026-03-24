@extends('layouts.app')
@section('title', 'Edit Auction')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-1">✏️ Edit Auction</h1>
    <p class="text-gray-400 text-sm mb-6">
        Editing will re-submit your auction for admin review.
    </p>

    <form method="POST" action="{{ route('listings.update', $listing) }}">
        @csrf
        @method('PATCH')

        {{-- Game Info --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                🎮 Game Information
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Game *</label>
                <select name="game_id"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                               text-sm text-white focus:outline-none focus:border-indigo-500">
                    @foreach($games as $game)
                    <option value="{{ $game->id }}"
                        {{ $listing->game_id == $game->id ? 'selected' : '' }}>
                        {{ $game->name }} — {{ $game->category }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Rank</label>
                    <input type="text" name="rank"
                           value="{{ old('rank', $listing->rank) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Level</label>
                    <input type="number" name="level"
                           value="{{ old('level', $listing->level) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Account Age</label>
                    <input type="text" name="account_age"
                           value="{{ old('account_age', $listing->account_age) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Server</label>
                    <input type="text" name="server"
                           value="{{ old('server', $listing->server) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Platform *</label>
                    <select name="platform"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                   text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="Mobile"  {{ $listing->platform === 'Mobile'  ? 'selected' : '' }}>📱 Mobile</option>
                        <option value="PC"      {{ $listing->platform === 'PC'      ? 'selected' : '' }}>🖥️ PC</option>
                        <option value="Console" {{ $listing->platform === 'Console' ? 'selected' : '' }}>🎮 Console</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Listing Details --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                📝 Listing Details
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Title *</label>
                <input type="text" name="title"
                       value="{{ old('title', $listing->title) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                              text-sm text-white focus:outline-none focus:border-indigo-500">
                @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Description *</label>
                <textarea name="description" rows="4"
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                 text-sm text-white focus:outline-none focus:border-indigo-500 resize-none">{{ old('description', $listing->description) }}</textarea>
                @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Auction Settings --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                ⚙️ Auction Settings
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Starting Price (USD) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2
                                     text-gray-400 font-bold">$</span>
                        <input type="number" name="starting_price"
                               value="{{ old('starting_price', $listing->starting_price) }}"
                               step="0.01" min="1"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      pl-7 pr-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                    @error('starting_price')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Bid Increment (USD) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2
                                     text-gray-400 font-bold">$</span>
                        <input type="number" name="bid_increment"
                               value="{{ old('bid_increment', $listing->bid_increment) }}"
                               step="0.50" min="0.5"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      pl-7 pr-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                    @error('bid_increment')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Auction End Date & Time *
                </label>
                <input type="datetime-local" name="auction_ends_at"
                       value="{{ old('auction_ends_at', $listing->auction_ends_at?->format('Y-m-d\TH:i')) }}"
                       min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                              text-sm text-white focus:outline-none focus:border-indigo-500">
                @error('auction_ends_at')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}"
               class="text-gray-400 hover:text-white text-sm transition">
                ← Cancel
            </a>
            <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-6 py-2.5
                           rounded-xl font-bold text-sm transition">
                Save & Resubmit for Review →
            </button>
        </div>

    </form>
</div>
@endsection
