@extends('layouts.app')
@section('title', 'Sell Account')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-1">📤 Sell Your Account</h1>
    <p class="text-gray-400 text-sm mb-6">
        Fill in all details. Our team reviews every listing before it goes live.
    </p>

    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
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
                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
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
                           placeholder="e.g. 120"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Account Age</label>
                    <input type="text" name="account_age" value="{{ old('account_age') }}"
                           placeholder="e.g. 3 years"
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
                       placeholder="e.g. Mythic Account | 150 Skins | All Heroes"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                              text-sm text-white focus:outline-none focus:border-indigo-500">
                @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Description *</label>
                <textarea name="description" rows="4"
                          placeholder="Describe the account in detail — heroes, skins, rank history, etc."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                 text-sm text-white focus:outline-none focus:border-indigo-500 resize-none">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Price (USD) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                    <input type="number" name="price" value="{{ old('price') }}"
                           step="0.01" min="1" placeholder="0.00"
                           id="priceInput"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-7 pr-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div class="bg-green-500/5 border border-green-500/15 rounded-xl px-3 py-2 mt-2
                            flex justify-between text-sm">
                    <span class="text-gray-400">After 5% fee, you receive:</span>
                    <strong class="text-green-400" id="payoutDisplay">$0.00</strong>
                </div>
                @error('price')
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
                <div class="text-xs text-gray-500">JPG, PNG, WEBP · Max 3MB each · Min 1 image</div>
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
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5
                           rounded-xl font-semibold text-sm transition">
                Submit for Review →
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
// Live payout calculator
document.getElementById('priceInput').addEventListener('input', function() {
    const price  = parseFloat(this.value) || 0;
    const payout = (price * 0.95).toFixed(2);
    document.getElementById('payoutDisplay').textContent = '$' + payout;
});

// Image preview
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
