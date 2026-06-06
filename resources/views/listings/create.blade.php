@extends('layouts.app')
@section('title', 'Sell Account')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-1">📤 Sell Your Account</h1>
    <p class="text-gray-400 text-sm mb-8">
        Fill the details below. Your listing goes live instantly.
    </p>

    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- GAME INFO --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">
                🎮 Game Information
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Game <span class="text-red-400">*</span></label>
                <select name="game_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white">
                    <option value="">Select a game</option>
                    @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }} — {{ $game->category }}
                    </option>
                    @endforeach
                </select>
                @error('game_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Rank</label>
                    <input type="text" name="rank" value="{{ old('rank') }}" placeholder="Mythic"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Level</label>
                    <input type="number" name="level" value="{{ old('level') }}" placeholder="120"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Server</label>
                    <input type="text" name="server" value="{{ old('server') }}" placeholder="SEA"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Platform <span class="text-red-400">*</span></label>
                    <select name="platform" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                        <option value="">Select</option>
                        <option value="Mobile" {{ old('platform') === 'Mobile' ? 'selected' : '' }}>📱 Mobile</option>
                        <option value="PC" {{ old('platform') === 'PC' ? 'selected' : '' }}>🖥️ PC</option>
                        <option value="Console" {{ old('platform') === 'Console' ? 'selected' : '' }}>🎮 Console</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- LISTING DETAILS --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">
                📝 Listing Details
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Mythic Account | 150 Skins | All Heroes"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Description <span class="text-red-400">*</span></label>
                <textarea name="description" rows="4" required placeholder="Describe the account in detail..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Price (USD) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                    <input type="number" name="price" id="priceInput" value="{{ old('price') }}" step="0.01" min="1" required
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-8 pr-4 py-3 text-sm">
                </div>
               {{--  <div class="mt-3 bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-3 flex justify-between">
                    <span class="text-gray-400">You will receive:</span>
                    <strong class="text-green-400" id="payoutDisplay">$0.00</strong>
                </div>
            </div>--}} 
        </div>

        {{-- CONTACT --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                📬 Contact Information
            </div>
            <p class="text-xs text-gray-500 mb-5">Buyers will message you here</p>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Telegram <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">@</span>
                        <input type="text" name="contact_telegram" id="telegramInput" required
                               value="{{ old('contact_telegram') }}" placeholder="yourusername"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-8 pr-4 py-3 text-sm">
                    </div>
                    @error('contact_telegram') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">WhatsApp (optional)</label>
                        <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp') }}" placeholder="+60123456789"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Discord (optional)</label>
                        <input type="text" name="contact_discord" value="{{ old('contact_discord') }}" placeholder="username#0000"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm">
                    </div>
                </div>
            </div>
        </div>

        {{-- IMAGES - LARGE FULL PREVIEW --}}
<div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-8">
    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
        📸 Proof Screenshots <span class="text-red-400">*</span>
    </div>

    <div id="uploadArea"
         class="border-2 border-dashed border-gray-700 hover:border-indigo-500 rounded-2xl p-8 text-center cursor-pointer transition min-h-[420px] flex flex-col">

        <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="imageInput">

        <!-- Upload Prompt -->
        <div id="uploadPrompt" class="flex-1 flex flex-col items-center justify-center">
            <div class="text-6xl mb-5">📸</div>
            <div class="font-semibold text-white text-xl">Click to upload screenshots</div>
            <div class="text-sm text-gray-400 mt-2 text-center">
                JPG, PNG, WEBP • Max 3MB each<br>
                At least 1 image recommended
            </div>
        </div>

        <!-- Large Preview Area -->
        <div id="imagePreview" class="hidden w-full grid grid-cols-1 gap-4 mt-4"></div>
    </div>

    <div id="addMoreBtn" class="hidden mt-4 text-center">
        <button type="button" onclick="document.getElementById('imageInput').click()"
                class="text-indigo-400 hover:text-indigo-300 text-sm flex items-center gap-2 mx-auto">
            + Add more images
        </button>
    </div>

    @error('images')
        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>

        {{-- SUBMIT --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition">← Cancel</a>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 px-8 py-3.5 rounded-2xl font-semibold transition">
                🚀 Post Listing Now
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Price calculator
document.getElementById('priceInput').addEventListener('input', function() {
    const payout = (parseFloat(this.value) * 0.95 || 0).toFixed(2);
    document.getElementById('payoutDisplay').textContent = '$' + payout;
});

const uploadArea = document.getElementById('uploadArea');
const imageInput = document.getElementById('imageInput');
const uploadPrompt = document.getElementById('uploadPrompt');
const imagePreview = document.getElementById('imagePreview');
const addMoreBtn = document.getElementById('addMoreBtn');

imageInput.addEventListener('change', function() {
    if (this.files.length === 0) return;

    uploadPrompt.classList.add('hidden');
    imagePreview.classList.remove('hidden');
    addMoreBtn.classList.remove('hidden');

    // Clear previous previews
    imagePreview.innerHTML = '';

    Array.from(this.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}"
                     class="w-full rounded-2xl border border-gray-700 object-cover aspect-video">
                <button type="button"
                        onclick="removeImage(this)"
                        class="absolute top-3 right-3 bg-black/80 hover:bg-red-600 text-white text-xs px-3 py-1 rounded-xl opacity-0 group-hover:opacity-100 transition">
                    Remove
                </button>
            `;
            imagePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

// Allow clicking anywhere on the area to upload
uploadArea.addEventListener('click', function(e) {
    if (!e.target.closest('button')) {
        imageInput.click();
    }
});

// Function to remove image
function removeImage(btn) {
    btn.parentElement.remove();

    // If no images left, show upload prompt again
    if (imagePreview.children.length === 0) {
        uploadPrompt.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        addMoreBtn.classList.add('hidden');
    }
}

// Telegram parser
function parseTelegram(value) {
    let u = value.trim();
    const match = u.match(/(?:https?:\/\/)?(?:www\.)?(?:t\.me|telegram\.me)\/([a-zA-Z0-9_]+)/i);
    if (match) u = match[1];
    return u.replace(/^@/, '').trim();
}

const telegramInput = document.getElementById('telegramInput');
telegramInput.addEventListener('blur', function() {
    this.value = parseTelegram(this.value);
});
telegramInput.addEventListener('paste', function() {
    setTimeout(() => this.value = parseTelegram(this.value), 10);
});
</script>
@endpush
