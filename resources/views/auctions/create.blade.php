@extends('layouts.app')
@section('title', 'Create Auction')

@if ($errors->any())
    <div class="text-red-500 bg-red-900/20 p-4 rounded-xl">
        <ul>
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight mb-2 flex items-center gap-3">
            Create New Auction
        </h1>
        <p class="text-gray-400">
            List your account securely. Highest bidder wins — payment held in escrow until delivery.
        </p>
    </div>

    <form method="POST" action="{{ route('auctions.store') }}" enctype="multipart/form-data"
          class="space-y-8">
        @csrf

        {{-- Game Information --}}
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 bg-indigo-500/10 text-indigo-400 rounded-2xl flex items-center justify-center text-xl">🎮</div>
                <div>
                    <h2 class="text-lg font-semibold">Game Information</h2>
                    <p class="text-sm text-gray-500">Tell buyers what they're getting</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Game <span class="text-red-400">*</span></label>
                    <select name="game_id" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-5 py-3.5 text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="" disabled selected>Select a game...</option>
                        @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                            {{ $game->name }} — {{ $game->category }}
                        </option>
                        @endforeach
                    </select>
                    @error('game_id')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Rank / Tier</label>
                    <input type="text" name="rank" value="{{ old('rank') }}"
                           placeholder="e.g. Mythic, Immortal, Grandmaster"
                           class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-5 py-3.5 text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Level</label>
                    <input type="number" name="level" value="{{ old('level') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-5 py-3.5 text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Platform <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="platform" value="Mobile" required class="peer hidden" {{ old('platform') === 'Mobile' ? 'checked' : '' }}>
                            <div class="peer-checked:bg-indigo-600 peer-checked:text-white border border-gray-700 hover:border-gray-600 rounded-2xl py-4 text-center transition">
                                📱 Mobile
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="platform" value="PC" required class="peer hidden" {{ old('platform') === 'PC' ? 'checked' : '' }}>
                            <div class="peer-checked:bg-indigo-600 peer-checked:text-white border border-gray-700 hover:border-gray-600 rounded-2xl py-4 text-center transition">
                                🖥️ PC
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="platform" value="Console" required class="peer hidden" {{ old('platform') === 'Console' ? 'checked' : '' }}>
                            <div class="peer-checked:bg-indigo-600 peer-checked:text-white border border-gray-700 hover:border-gray-600 rounded-2xl py-4 text-center transition">
                                🎮 Console
                            </div>
                        </label>
                    </div>
                    @error('platform')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Listing Details --}}
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center text-xl">📝</div>
                <h2 class="text-lg font-semibold">Listing Details</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Auction Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="Mythic Account • 150+ Skins • Rare Collections"
                       class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-5 py-3.5 text-white focus:outline-none focus:border-indigo-500 placeholder:text-gray-500">
                @error('title')
                    <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Auction Settings --}}
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 bg-amber-500/10 text-amber-400 rounded-2xl flex items-center justify-center text-xl">⚙️</div>
                <h2 class="text-lg font-semibold">Auction Settings</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Starting Price (USD) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl text-gray-400 font-light">$</span>
                        <input type="number" name="starting_price" step="0.01" min="1" value="{{ old('starting_price') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-2xl pl-11 pr-5 py-3.5 text-lg font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    @error('starting_price')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Minimum Bid Increment (USD)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl text-gray-400 font-light">$</span>
                        <input type="number" name="bid_increment" value="{{ old('bid_increment', 1) }}" step="0.5" min="0.5"
                               class="w-full bg-gray-800 border border-gray-700 rounded-2xl pl-11 pr-5 py-3.5 text-lg font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Auction Ends <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="auction_ends_at"
                        id="auctionEndsAt"
                        value="{{ old('auction_ends_at') }}"
                        class="w-full bg-gray-800 border border-gray-700 rounded-2xl
                                px-5 py-3.5 text-white focus:outline-none focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-2">
                        Minimum 1 hour from now. Recommended: 24–72 hours.
                    </p>
                    {{-- <p class="text-xs text-gray-500 mt-2">Minimum 1 hour from now. Longer auctions usually attract more bids.</p> --}}
                    @error('auction_ends_at')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Proof Images --}}
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 bg-purple-500/10 text-purple-400 rounded-2xl flex items-center justify-center text-xl">📸</div>
                <div>
                    <h2 class="text-lg font-semibold">Proof Screenshots <span class="text-red-400">*</span></h2>
                    <p class="text-sm text-gray-500">Upload clear images of your account (inventory, rank, etc.)</p>
                </div>
            </div>

            <div id="dropZone"
                 class="border-2 border-dashed border-gray-700 hover:border-indigo-500 rounded-3xl p-10 text-center transition cursor-pointer">
                <div class="mx-auto w-16 h-16 bg-gray-800 rounded-2xl flex items-center justify-center text-4xl mb-4">📸</div>
                <p class="font-medium mb-1">Drop images here or click to upload</p>
                <p class="text-sm text-gray-500">JPG, PNG, WEBP • Max 5MB each • Max 8 images</p>
                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="hidden">
            </div>

            <div id="imagePreview" class="grid grid-cols-3 sm:grid-cols-4 gap-4 mt-6"></div>

            @error('images')
                <p class="text-red-400 text-sm mt-3">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-6">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 text-gray-400 hover:text-white transition">
                ← Cancel
            </a>

            <button type="submit"
                    class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-300 hover:to-amber-400 text-black font-bold px-10 py-4 rounded-2xl flex items-center gap-3 transition-all active:scale-95">
                Submit Auction for Review
                <span class="text-xl">→</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('imageInput');
const previewContainer = document.getElementById('imagePreview');

// click upload
dropZone.addEventListener('click', () => fileInput.click());

// drag UI only
dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('border-indigo-500');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-indigo-500');
});

// ❗ IMPORTANT: do NOT inject dropped files
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-indigo-500');

    alert("Please click and select images. Drag preview only (browser security).");
});

fileInput.addEventListener('change', function () {
    const maxSize = 3 * 1024 * 1024; // 3MB

    for (let file of this.files) {
        if (file.size > maxSize) {
            alert("Image must be less than 3MB");
            this.value = ""; // clear input
            previewContainer.innerHTML = '';
            return;
        }
    }

    renderPreviews(this.files);
});
``

// preview
function renderPreviews(files) {
    previewContainer.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;

        const reader = new FileReader();

        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative';

            div.innerHTML = `
                <img src="${e.target.result}" class="w-full aspect-square object-cover">
                <div class="absolute bottom-2 left-2 bg-black text-white text-xs px-2 py-1">
                    ${index + 1}
                </div>
            `;

            previewContainer.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}
</script>
@endpush
