@extends('layouts.app')
@section('title', 'Sell Account')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-1">📤 Sell Your Account</h1>
    <p class="text-gray-400 text-sm mb-6">
        Fill in all details. Your listing goes live instantly.
    </p>

    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- GAME INFO --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                🎮 Game Information
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">Game </label>
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
                           placeholder="e.g. 120"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                  text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
               
            </div>

            <div class="grid grid-cols-2 gap-3">
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

        {{-- LISTING DETAILS --}}
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

                @error('price')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>  

        {{-- CONTACT INFO --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                📬 Contact Information
            </div>
            <p class="text-xs text-gray-500 mb-4">
                Add at least one contact so buyers can reach you after purchase.
            </p>

            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                  
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-white -400 mb-1">Telegram</label>
                        <div class="relative">
                                        <div style="margin-bottom: 12px;">
                        <div style="display:flex; align-items:center; background:#1f2937;
                                    border:1px solid #374151; border-radius:10px; overflow:hidden;
                                    transition: border-color 0.2s;" id="telegram-wrap">

                            {{-- Icon badge --}}
                            <div style="display:flex; align-items:center; gap:6px; padding:0 12px;
                                        border-right:1px solid #374151; height:42px; flex-shrink:0;
                                        background:#111827;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#38bdf8">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L8.48 14.617l-2.95-.924c-.642-.204-.657-.642.136-.953l11.57-4.461c.537-.194 1.006.131.326.969z"/>
                                </svg>
                                <span style="font-size:12px; color:#9ca3af; white-space:nowrap;">@</span>
                            </div>

                            {{-- Input --}}
                            <input type="text" id="telegramInput" name="contact_telegram"
                                value="{{ old('contact_telegram') }}"
                                placeholder="username"
                                autocomplete="off"
                                style="flex:1; background:transparent; border:none; outline:none;
                                        padding:0 12px; height:42px; font-size:13px; color:#fff;
                                        caret-color:#38bdf8;"
                                oninput="validateTelegram(this)">

                            {{-- Status badge --}}
                            <div id="tg-status" style="padding:0 12px; font-size:12px;
                                                        display:none; white-space:nowrap;"></div>
                        </div>

                        {{-- Helper --}}
                        <p style="font-size:11px; color:#6b7280; margin:4px 0 0 4px;">
                            e.g. myusername (min 5 characters)
                        </p>

                        @error('contact_telegram')
                            <p style="font-size:12px; color:#f87171; margin:4px 0 0 4px;">
                                ⚠ {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <script>
                    function validateTelegram(input) {
                        const wrap  = document.getElementById('telegram-wrap');
                        const badge = document.getElementById('tg-status');
                        const val   = input.value.replace(/^@/, '').trim();

                        if (!val) {
                            wrap.style.borderColor  = '#374151';
                            badge.style.display     = 'none';
                            return;
                        }

                        const ok = /^[a-zA-Z0-9][a-zA-Z0-9_]{3,30}[a-zA-Z0-9]$/.test(val);

                        wrap.style.borderColor = ok ? '#22c55e' : '#ef4444';
                        badge.style.display    = 'block';
                        badge.style.color      = ok ? '#22c55e' : '#ef4444';
                        badge.textContent      = ok ? '✓ Valid' : '✗ Invalid';
                    }
                    </script>

           
                </div>
             </div>
            </div>
            </div>
        </div>

        {{-- SELLER INFORMATION --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                👤 Seller Information
            </div>
            <p class="text-xs text-gray-500 mb-4">
                This builds trust with buyers. More info = faster sale.
            </p>

            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">📞 Phone Number</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">+</span>
                        <input type="text" name="seller_phone"
                               value="{{ old('seller_phone') }}"
                               placeholder="60123456789"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      pl-7 pr-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                    <p class="text-xs text-gray-600 mt-1">Only shown to buyer after purchase</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">🌍 Your Country</label>
                    <select name="seller_country"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                   text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select your country</option>
                        <optgroup label="Southeast Asia">
                            <option value="MY" {{ old('seller_country') === 'MY' ? 'selected' : '' }}>🇲🇾 Malaysia</option>
                            <option value="ID" {{ old('seller_country') === 'ID' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                            <option value="PH" {{ old('seller_country') === 'PH' ? 'selected' : '' }}>🇵🇭 Philippines</option>
                            <option value="TH" {{ old('seller_country') === 'TH' ? 'selected' : '' }}>🇹🇭 Thailand</option>
                            <option value="SG" {{ old('seller_country') === 'SG' ? 'selected' : '' }}>🇸🇬 Singapore</option>
                            <option value="VN" {{ old('seller_country') === 'VN' ? 'selected' : '' }}>🇻🇳 Vietnam</option>
                            <option value="MM" {{ old('seller_country') === 'MM' ? 'selected' : '' }}>🇲🇲 Myanmar</option>
                            <option value="KH" {{ old('seller_country') === 'KH' ? 'selected' : '' }}>🇰🇭 Cambodia</option>
                            <option value="BN" {{ old('seller_country') === 'BN' ? 'selected' : '' }}>🇧🇳 Brunei</option>
                        </optgroup>
                        <optgroup label="East Asia">
                            <option value="CN" {{ old('seller_country') === 'CN' ? 'selected' : '' }}>🇨🇳 China</option>
                            <option value="JP" {{ old('seller_country') === 'JP' ? 'selected' : '' }}>🇯🇵 Japan</option>
                            <option value="KR" {{ old('seller_country') === 'KR' ? 'selected' : '' }}>🇰🇷 South Korea</option>
                            <option value="TW" {{ old('seller_country') === 'TW' ? 'selected' : '' }}>🇹🇼 Taiwan</option>
                            <option value="HK" {{ old('seller_country') === 'HK' ? 'selected' : '' }}>🇭🇰 Hong Kong</option>
                        </optgroup>
                        <optgroup label="South Asia">
                            <option value="IN" {{ old('seller_country') === 'IN' ? 'selected' : '' }}>🇮🇳 India</option>
                            <option value="PK" {{ old('seller_country') === 'PK' ? 'selected' : '' }}>🇵🇰 Pakistan</option>
                            <option value="BD" {{ old('seller_country') === 'BD' ? 'selected' : '' }}>🇧🇩 Bangladesh</option>
                        </optgroup>
                        <optgroup label="Middle East">
                            <option value="SA" {{ old('seller_country') === 'SA' ? 'selected' : '' }}>🇸🇦 Saudi Arabia</option>
                            <option value="AE" {{ old('seller_country') === 'AE' ? 'selected' : '' }}>🇦🇪 UAE</option>
                            <option value="TR" {{ old('seller_country') === 'TR' ? 'selected' : '' }}>🇹🇷 Turkey</option>
                        </optgroup>
                        <optgroup label="Others">
                            <option value="US" {{ old('seller_country') === 'US' ? 'selected' : '' }}>🇺🇸 United States</option>
                            <option value="GB" {{ old('seller_country') === 'GB' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                            <option value="AU" {{ old('seller_country') === 'AU' ? 'selected' : '' }}>🇦🇺 Australia</option>
                            <option value="CA" {{ old('seller_country') === 'CA' ? 'selected' : '' }}>🇨🇦 Canada</option>
                            <option value="other" {{ old('seller_country') === 'other' ? 'selected' : '' }}>🌐 Other</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        📦 How did you get this account?
                    </label>
                    <div class="grid grid-cols-2 gap-2 mb-3"
                         x-data="{ source: '{{ old('stock_source') }}' }">
                        <label class="cursor-pointer">
                            <input type="radio" name="stock_source" value="self_farmed"
                                   x-model="source" class="sr-only"
                                   {{ old('stock_source') === 'self_farmed' ? 'checked' : '' }}>
                            <div :class="source === 'self_farmed'
                                         ? 'border-green-500 bg-green-500/10 text-green-400'
                                         : 'border-gray-700 bg-gray-800 text-gray-400'"
                                 class="border-2 rounded-xl p-3 text-center transition">
                                <div class="text-xl mb-1">🌾</div>
                                <div class="text-xs font-bold">Self Farmed</div>
                                <div class="text-xs mt-0.5 opacity-70">I earned this myself</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="stock_source" value="resell"
                                   x-model="source" class="sr-only"
                                   {{ old('stock_source') === 'resell' ? 'checked' : '' }}>
                            <div :class="source === 'resell'
                                         ? 'border-blue-500 bg-blue-500/10 text-blue-400'
                                         : 'border-gray-700 bg-gray-800 text-gray-400'"
                                 class="border-2 rounded-xl p-3 text-center transition">
                                <div class="text-xl mb-1">🔄</div>
                                <div class="text-xs font-bold">Reselling</div>
                                <div class="text-xs mt-0.5 opacity-70">Bought &amp; reselling</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="stock_source" value="gifted"
                                   x-model="source" class="sr-only"
                                   {{ old('stock_source') === 'gifted' ? 'checked' : '' }}>
                            <div :class="source === 'gifted'
                                         ? 'border-pink-500 bg-pink-500/10 text-pink-400'
                                         : 'border-gray-700 bg-gray-800 text-gray-400'"
                                 class="border-2 rounded-xl p-3 text-center transition">
                                <div class="text-xl mb-1">🎁</div>
                                <div class="text-xs font-bold">Gifted</div>
                                <div class="text-xs mt-0.5 opacity-70">Received as a gift</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="stock_source" value="other"
                                   x-model="source" class="sr-only"
                                   {{ old('stock_source') === 'other' ? 'checked' : '' }}>
                            <div :class="source === 'other'
                                         ? 'border-yellow-500 bg-yellow-500/10 text-yellow-400'
                                         : 'border-gray-700 bg-gray-800 text-gray-400'"
                                 class="border-2 rounded-xl p-3 text-center transition">
                                <div class="text-xl mb-1">💬</div>
                                <div class="text-xs font-bold">Other</div>
                                <div class="text-xs mt-0.5 opacity-70">Something else</div>
                            </div>
                        </label>
                    </div>
                    <textarea name="stock_source_note" rows="2"
                              placeholder="Optional: add more detail about the account history..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5
                                     text-sm text-white focus:outline-none focus:border-indigo-500
                                     resize-none">{{ old('stock_source_note') }}</textarea>
                </div>
            </div>
        </div>

        {{-- IMAGES --}}
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

        {{-- SUBMIT --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}"
               class="text-gray-400 hover:text-white text-sm transition">
                ← Cancel
            </a>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5
                           rounded-xl font-semibold text-sm transition">
                🚀 Post Listing
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

// Parse Telegram link or username
function parseTelegramValue(value) {
    let username = value.trim();
    if (!username) return '';

    // Handle full URLs and various formats
    const urlMatch = username.match(/(?:https?:)?\/\/(?:www\.)?(?:t\.me|telegram\.me)\/([^\s/?#]+)/i);
    if (urlMatch) {
        username = urlMatch[1];
    } else if (username.includes('t.me/') || username.includes('telegram.me/')) {
        // Handle cases without protocol
        const shortMatch = username.match(/(?:t\.me|telegram\.me)\/([^\s/?#]+)/i);
        if (shortMatch) username = shortMatch[1];
    }

    // Clean up username - remove @ and special chars
    username = username.replace(/^[@#]/, '').replace(/[^\w.-]/g, '').trim();

    return username;
}

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    const telegramInput = document.getElementById('telegramInput');

    if (!telegramInput) {
        console.warn('Telegram input not found');
        return;
    }

    // Handle paste events - process immediately
    telegramInput.addEventListener('paste', function(e) {
        // Allow the paste to complete first
        setTimeout(() => {
            const parsed = parseTelegramValue(this.value);
            if (parsed) {
                this.value = parsed;
            }
        }, 0);
    });

    // Handle blur (when user leaves field)
    telegramInput.addEventListener('blur', function() {
        const parsed = parseTelegramValue(this.value);
        if (parsed) {
            this.value = parsed;
        }
    });

    // Ensure no paste restrictions
    telegramInput.addEventListener('keydown', function(e) {
        // Allow Ctrl+V (paste)
        if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
            e.preventDefault = false;
        }
    });
});
</script>
@endpush
