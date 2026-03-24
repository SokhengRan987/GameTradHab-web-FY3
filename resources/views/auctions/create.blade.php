@extends('layouts.app')
@section('title', 'Create Auction')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-1">🏆 Create Auction</h1>
    <p class="text-gray-400 text-sm mb-6">
        Set a starting price and end time. Highest bidder wins and pays via escrow.
    </p>

    <form method="POST" action="{{ route('auctions.store') }}" enctype="multipart/form-data">
        @csrf

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
                    <option value="">Select a game</option>
                    @foreach($games as $game)
                    <option value="{{ $game->id }}"
                        {{ old('game_id') == $game->id ? 'selected' : '' }}>
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
                    <input type="text" name="rank" value="{{ old('rank') }}"
                           placeholder="e.g. Mythic"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Level</label>
                    <input type="number" name="level" value="{{ old('level') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Account Age</label>
                    <input type="text" name="account_age" value="{{ old('account_age') }}"
                           placeholder="e.g. 2 years"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Server</label>
                    <input type="text" name="server" value="{{ old('server') }}"
                           placeholder="e.g. SEA"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Platform *</label>
                    <select name="platform"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                   text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select platform</option>
                        <option value="Mobile"  {{ old('platform') === 'Mobile'  ? 'selected' : '' }}>📱 Mobile</option>
                        <option value="PC"      {{ old('platform') === 'PC'      ? 'selected' : '' }}>🖥️ PC</option>
                        <option value="Console" {{ old('platform') === 'Console' ? 'selected' : '' }}>🎮 Console</option>
                    </select>
                    @error('platform')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="e.g. Mythic Account | 150 Skins | Rare Sets"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                              text-sm text-white focus:outline-none focus:border-indigo-500">
                @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Description *</label>
                <textarea name="description" rows="4"
                          placeholder="Describe your account in detail..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                 text-sm text-white focus:outline-none focus:border-indigo-500 resize-none">{{ old('description') }}</textarea>
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
                               value="{{ old('starting_price') }}"
                               step="0.01" min="1" placeholder="0.00"
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
                               value="{{ old('bid_increment', 1) }}"
                               step="0.50" min="0.5" placeholder="1.00"
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
                       value="{{ old('auction_ends_at') }}"
                       min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                              text-sm text-white focus:outline-none focus:border-indigo-500">
                <p class="text-xs text-gray-500 mt-1">
                    Minimum 1 hour from now. Recommended: 24–72 hours.
                </p>
                @error('auction_ends_at')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Images --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                📸 Proof Screenshots *
            </div>
            <label class="block border-2 border-dashed border-gray-700 rounded-xl p-6
                          text-center cursor-pointer hover:border-indigo-500 transition">
                <div class="text-3xl mb-2">📸</div>
                <div class="font-semibold text-sm mb-1">Click to upload screenshots</div>
                <div class="text-xs text-gray-500">JPG, PNG, WEBP · Max 3MB each</div>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="hidden" id="imageInput">
            </label>
            <div id="imagePreview" class="flex gap-2 flex-wrap mt-3"></div>
            @error('images')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
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
                Submit Auction for Review →
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-20 h-14 object-cover rounded-lg border border-gray-700';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
