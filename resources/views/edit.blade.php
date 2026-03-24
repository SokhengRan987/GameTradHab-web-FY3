@extends('layouts.app')
@section('title', 'Edit Listing')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-1">✏️ Edit Listing</h1>
    <p class="text-gray-400 text-sm mb-6">
        Editing will re-submit your listing for admin review.
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
                @error('game_id')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
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

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Description *</label>
                <textarea name="description" rows="4"
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                 text-sm text-white focus:outline-none focus:border-indigo-500 resize-none">{{ old('description', $listing->description) }}</textarea>
                @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Price (USD) *
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2
                                 text-gray-400 font-bold">$</span>
                    <input type="number" name="price"
                           value="{{ old('price', $listing->price) }}"
                           step="0.01" min="1"
                           id="priceInput"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  pl-7 pr-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                </div>
                <div class="bg-green-500/5 border border-green-500/15 rounded-xl
                            px-3 py-2 mt-2 flex justify-between text-sm">
                    <span class="text-gray-400">After 5% fee, you receive:</span>
                    <strong class="text-green-400" id="payoutDisplay">
                        ${{ number_format($listing->price * 0.95, 2) }}
                    </strong>
                </div>
                @error('price')
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
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5
                           rounded-xl font-semibold text-sm transition">
                Save & Resubmit for Review →
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('priceInput').addEventListener('input', function() {
    const payout = (parseFloat(this.value) * 0.95 || 0).toFixed(2);
    document.getElementById('payoutDisplay').textContent = '$' + payout;
});
</script>
@endpush
