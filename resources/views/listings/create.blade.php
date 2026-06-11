@extends('layouts.app')
@section('title', 'Sell Account — GameTradeHub')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white mb-1">Post a Listing</h1>
        <p class="text-gray-500 text-sm">Fill in the details below to list your account for sale.</p>
    </div>

    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- ── GAME INFORMATION ──────────────────────────────────── --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">

            <div class="flex items-center gap-2 mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                </svg>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Game Information</span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Game <span class="text-red-400">*</span>
                </label>
                <select name="game_id" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                               text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                    <option value="" disabled selected hidden>Select a game</option>
                    @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }}
                    </option>
                    @endforeach
                </select>
                @error('game_id')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Rank</label>
                    <input type="text" name="rank" value="{{ old('rank') }}" placeholder="e.g. Mythic"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                  text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Level</label>
                    <input type="number" name="level" value="{{ old('level') }}" placeholder="e.g. 120"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                  text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Server</label>
                    <input type="text" name="server" value="{{ old('server') }}" placeholder="e.g. SEA"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                  text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Platform <span class="text-red-400">*</span>
                    </label>
                    <select name="platform" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                   text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="Mobile"  {{ old('platform') === 'Mobile'  ? 'selected' : '' }}>Mobile</option>
                        <option value="PC"      {{ old('platform') === 'PC'      ? 'selected' : '' }}>PC</option>
                        <option value="Console" {{ old('platform') === 'Console' ? 'selected' : '' }}>Console</option>
                    </select>
                    @error('platform')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── LISTING DETAILS ───────────────────────────────────── --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">

            <div class="flex items-center gap-2 mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Listing Details</span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Title <span class="text-red-400">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="e.g. Mythic Account | 150 Skins | All Heroes"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                              text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                @error('title')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Description <span class="text-red-400">*</span>
                </label>
                <textarea name="description" rows="5" required
                          placeholder="Describe the account — skins, heroes, rank history, any notable items..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500
                                 transition resize-none">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Price (USD) <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-sm">$</span>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="1" required
                           placeholder="0.00"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-8 pr-4 py-3
                                  text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                </div>
                @error('price')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ── CONTACT INFORMATION ───────────────────────────────── --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">

            <div class="flex items-center gap-2 mb-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contact Information</span>
            </div>
            <p class="text-xs text-gray-600 mb-5 ml-6">Buyers will reach out to you via these channels.</p>

            <div class="space-y-4">

                {{-- Telegram --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Telegram <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">@</span>
                        <input type="text" name="contact_telegram" id="telegramInput" required
                               value="{{ old('contact_telegram') }}" placeholder="yourusername"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-8 pr-4 py-3
                                      text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                    </div>
                    @error('contact_telegram')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- WhatsApp --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                            WhatsApp
                            <span class="text-gray-600 font-normal ml-1">optional</span>
                        </label>
                        <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp') }}"
                               placeholder="+60123456789"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                      text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                    </div>
                    {{-- Discord --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                            Discord
                            <span class="text-gray-600 font-normal ml-1">optional</span>
                        </label>
                        <input type="text" name="contact_discord" value="{{ old('contact_discord') }}"
                               placeholder="username#0000"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3
                                      text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500 transition">
                    </div>
                </div>

            </div>
        </div>

        {{-- ── PROOF SCREENSHOTS ─────────────────────────────────── --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">

            <div class="flex items-center gap-2 mb-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Proof Screenshots</span>
            </div>
            <p class="text-xs text-gray-600 mb-5 ml-6">Upload screenshots showing rank, inventory, or other account details.</p>

            <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="imageInput">

            {{-- Drop Zone --}}
            <div id="uploadArea"
                 class="border-2 border-dashed border-gray-700 hover:border-indigo-500/60
                        rounded-xl transition cursor-pointer">

                {{-- Prompt --}}
                <div id="uploadPrompt" class="flex flex-col items-center justify-center py-12 px-6 text-center">
                    <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-white mb-1">Click to upload screenshots</p>
                    <p class="text-xs text-gray-500">JPG, PNG, WEBP · Max 3MB each</p>
                </div>

                {{-- Preview Grid --}}
                <div id="imagePreview" class="hidden p-4 grid grid-cols-2 gap-3"></div>
            </div>

            {{-- Add More --}}
            <div id="addMoreBtn" class="hidden mt-3">
                <button type="button" onclick="document.getElementById('imageInput').click()"
                        class="flex items-center gap-1.5 text-xs text-indigo-400 hover:text-indigo-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add more images
                </button>
            </div>

            @error('images')
            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── SUBMIT ────────────────────────────────────────────── --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Cancel
            </a>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold
                           px-8 py-3 rounded-xl transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Post Listing
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
const uploadArea   = document.getElementById('uploadArea');
const imageInput   = document.getElementById('imageInput');
const uploadPrompt = document.getElementById('uploadPrompt');
const imagePreview = document.getElementById('imagePreview');
const addMoreBtn   = document.getElementById('addMoreBtn');

// Click anywhere on drop zone → open file picker
uploadArea.addEventListener('click', e => {
    if (!e.target.closest('button')) imageInput.click();
});

// Drag & drop support
uploadArea.addEventListener('dragover', e => {
    e.preventDefault();
    uploadArea.classList.add('border-indigo-500');
});
uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('border-indigo-500');
});
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('border-indigo-500');
    handleFiles(e.dataTransfer.files);
});

imageInput.addEventListener('change', function () {
    if (this.files.length === 0) return;
    handleFiles(this.files);
});

function handleFiles(files) {
    uploadPrompt.classList.add('hidden');
    imagePreview.classList.remove('hidden');
    addMoreBtn.classList.remove('hidden');

    imagePreview.innerHTML = '';

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group rounded-xl overflow-hidden border border-gray-700';
            wrapper.innerHTML = `
                <img src="${e.target.result}"
                     class="w-full aspect-video object-cover">
                <button type="button"
                        onclick="removeImage(this)"
                        class="absolute top-2 right-2 bg-black/70 hover:bg-red-600 text-white
                               rounded-lg px-2.5 py-1 text-xs opacity-0 group-hover:opacity-100 transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Remove
                </button>
            `;
            imagePreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(btn) {
    btn.closest('.relative').remove();
    if (imagePreview.children.length === 0) {
        uploadPrompt.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        addMoreBtn.classList.add('hidden');
    }
}

// Telegram — strip URL or @ prefix on blur/paste
function parseTelegram(value) {
    const match = value.trim().match(/(?:https?:\/\/)?(?:t\.me|telegram\.me)\/([a-zA-Z0-9_]+)/i);
    return match ? match[1] : value.trim().replace(/^@/, '');
}

const telegramInput = document.getElementById('telegramInput');
telegramInput.addEventListener('blur',  function () { this.value = parseTelegram(this.value); });
telegramInput.addEventListener('paste', function () { setTimeout(() => this.value = parseTelegram(this.value), 10); });
</script>
@endpush
