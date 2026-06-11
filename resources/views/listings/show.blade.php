@extends('layouts.app')
@section('title', $listing->title . ' — GameTradeHub')
@section('og_type', 'product')
@section('og_title', $listing->title . ' — GameTradeHub')
@section('og_description', 'Buy: ' . $listing->title . ' | ' . $listing->game->name . ' | $' . number_format($listing->price, 2) . ' | Escrow Protected')
@section('og_image', $listing->firstImage ? $listing->firstImage->url : asset('images/og-default.jpg'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('listings.index') }}" class="hover:text-white transition">Browse</a>
        <span>›</span>
        <span>{{ $listing->game->name }}</span>
        <span>›</span>
        <span class="text-gray-300 truncate">{{ $listing->title }}</span>
    </div>

    @if(Auth::check() && Auth::id() === $listing->user_id && $listing->status !== 'active')
    <div class="bg-yellow-500/10 border border-yellow-500/25 rounded-xl p-4 mb-5 text-sm text-yellow-200">
        Your listing has been updated and is pending admin review. It is hidden from public search until approved, but you can still edit it from your dashboard.
    </div>
    @endif

    <div class="grid grid-cols-3 gap-6">

        {{-- Left — Images + Info --}}
        <div class="col-span-2">

            {{-- Main Image --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl h-64
                        flex items-center justify-center text-6xl mb-3 overflow-hidden relative"
                 id="mainImage">
                @if($listing->images->count() > 0)
                <img src="{{ $listing->images->first()->url }}"
                     class="w-full h-full object-cover" id="mainImg">
                @else
                <span>🎮</span>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($listing->images->count() > 1)
            <div class="flex gap-2 mb-5">
                @foreach($listing->images as $image)
                <img src="{{ $image->url }}"
                     onclick="document.getElementById('mainImg').src='{{ $image->url }}'"
                     class="w-16 h-12 object-cover rounded-lg border-2 border-gray-700
                            hover:border-indigo-500 cursor-pointer transition">
                @endforeach
            </div>
            @endif

            {{-- Specs --}}
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Game</div>
                    <div class="font-bold text-sm">{{ $listing->game->name }}</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Rank</div>
                    <div class="font-bold text-sm">{{ $listing->rank ?? '—' }}</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Level</div>
                    <div class="font-bold text-sm">{{ $listing->level ?? '—' }}</div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Platform</div>
                    <div class="font-bold text-sm">{{ $listing->platform }}</div>
                </div>
            </div>
               {{-- Contact Information --}}
                @php
                    $hasTelegram = !empty($listing->contact_telegram);
                    $hasWhatsapp = !empty($listing->contact_whatsapp);
                    $hasDiscord  = !empty($listing->contact_discord);
                    $hasPhone    = !empty($listing->seller_phone);
                    $hasContact  = $hasTelegram || $hasWhatsapp || $hasDiscord || $hasPhone;
                @endphp

                @if($hasContact)
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-4">
                    <h3 class="font-bold mb-3">📬 Contact Seller</h3>

                    <div class="flex flex-col gap-2">

                        @if($hasTelegram)
                        <a href="https://t.me/{{ $listing->contact_telegram }}" target="_blank"
                        class="flex items-center gap-3 bg-gray-800 hover:bg-gray-700
                                border border-gray-700 rounded-xl p-3 transition group">
                            <div class="w-9 h-9 bg-sky-500/15 border border-sky-500/20 rounded-xl
                                        flex items-center justify-center flex-shrink-0">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="#38bdf8">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L8.48 14.617l-2.95-.924c-.642-.204-.657-.642.136-.953l11.57-4.461c.537-.194 1.006.131.326.969z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 mb-0.5">Telegram</div>
                                <div class="text-sm font-semibold text-sky-400 group-hover:text-sky-300">
                                    {{ $listing->contact_telegram }}
                                </div>
                            </div>
                            <span class="text-gray-600 group-hover:text-gray-400 text-xs">→</span>
                        </a>
                        @endif

                        @if($hasWhatsapp)
                        <a href="https://api.whatsapp.com/send?phone={{ preg_replace('/\D/', '', $listing->contact_whatsapp) }}"
                        target="_blank"
                        class="flex items-center gap-3 bg-gray-800 hover:bg-gray-700
                                border border-gray-700 rounded-xl p-3 transition group">
                            <div class="w-9 h-9 bg-green-500/15 border border-green-500/20 rounded-xl
                                        flex items-center justify-center flex-shrink-0 text-lg">
                                💬
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 mb-0.5">WhatsApp</div>
                                <div class="text-sm font-semibold text-green-400 group-hover:text-green-300">
                                    {{ $listing->contact_whatsapp }}
                                </div>
                            </div>
                            <span class="text-gray-600 group-hover:text-gray-400 text-xs">→</span>
                        </a>
                        @endif

                        @if($hasDiscord)
                        <div class="flex items-center gap-3 bg-gray-800 border border-gray-700 rounded-xl p-3">
                            <div class="w-9 h-9 bg-indigo-500/15 border border-indigo-500/20 rounded-xl
                                        flex items-center justify-center flex-shrink-0 text-lg">
                                🎮
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 mb-0.5">Discord</div>
                                <div class="text-sm font-semibold text-indigo-400">
                                    {{ $listing->contact_discord }}
                                </div>
                            </div>
                            <button onclick="navigator.clipboard.writeText('{{ $listing->contact_discord }}');
                                            this.textContent='✅ Copied'; setTimeout(()=>this.textContent='📋 Copy',2000)"
                                    class="text-xs text-gray-500 hover:text-white bg-gray-700 hover:bg-gray-600
                                        px-2 py-1 rounded-lg transition">
                                📋 Copy
                            </button>
                        </div>
                        @endif

                        @if($hasPhone)
                        <div class="flex items-center gap-3 bg-gray-800 border border-gray-700 rounded-xl p-3">
                            <div class="w-9 h-9 bg-purple-500/15 border border-purple-500/20 rounded-xl
                                        flex items-center justify-center flex-shrink-0 text-lg">
                                📱
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 mb-0.5">Phone</div>
                                <div class="text-sm font-semibold text-purple-400">
                                    {{ $listing->seller_phone }}
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
                @endif
            {{-- Description --}}

            {{-- <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <h3 class="font-bold mb-3">📝 Description</h3>
                <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </p>
            </div>--}}
            {{-- Seller Reviews --}}
            @php
                $sellerReviews = $listing->seller->reviews()->with('reviewer')->take(3)->get();
                $totalReviews  = $listing->seller->reviews()->count();
            @endphp
            @if($sellerReviews->count() > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold">⭐ Seller Reviews</h3>
                    <a href="{{ route('sellers.show', $listing->seller) }}"
                    class="text-xs text-indigo-400 hover:underline">
                        View all {{ $totalReviews }} →
                    </a>
                </div>

                {{-- Rating summary --}}
                <div class="flex items-center gap-3 bg-gray-800 rounded-xl p-3 mb-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400">
                            {{ number_format($listing->seller->rating_avg, 1) }}
                        </div>
                        <div class="text-xs text-gray-500">out of 5</div>
                    </div>
                    <div class="flex-1">
                        <div class="text-yellow-400 text-lg mb-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($listing->seller->rating_avg) ? '⭐' : '☆' }}
                            @endfor
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $totalReviews }} reviews
                        </div>
                    </div>
                </div>

                {{-- Recent reviews --}}
                @foreach($sellerReviews as $review)
                <div class="border-b border-gray-800 last:border-0 py-3">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center
                                        justify-center text-xs font-bold">
                                {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold">
                                {{ $review->reviewer->name }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex">
                                {!! $review->stars() !!}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @if($review->comment)
                    <p class="text-xs text-gray-300 mt-1 leading-relaxed">
                        {{ $review->comment }}
                    </p>
                    @endif
                </div>
                @endforeach

            </div>
            @endif
        </div>

        {{-- Right — Buy Box --}}
        <div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">

                <div class="text-3xl font-bold text-green-400 mb-1">
                    ${{ number_format($listing->price, 2) }}
                </div>
                <div class="font-bold text-lg mb-4 leading-tight">{{ $listing->title }}</div>

                {{-- Seller --}}
                <div class="flex items-center gap-3 bg-gray-800 rounded-xl p-3 mb-4">
                    <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center
                                justify-center font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($listing->seller->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('sellers.show', $listing->seller) }}"
                        class="font-semibold text-sm hover:text-indigo-400 transition">
                            {{ $listing->seller->name }}
                        </a>
                        <div class="flex items-center gap-2 text-xs text-gray-400 flex-wrap">
                            <span>🛒 {{ $listing->seller->total_sales }} sales</span>
                            @if($listing->seller->rating_avg > 0)
                            <span>·</span>
                            <span class="text-yellow-400">
                                ⭐ {{ number_format($listing->seller->rating_avg, 1) }}
                            </span>
                            @endif
                            @if($listing->seller->is_verified)
                            <span>·</span>
                            <span class="text-sky-400">✓ Verified</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Escrow Notice --}}
                <div class="bg-cyan-500/5 border border-cyan-500/15 rounded-xl p-3 mb-4
                            text-xs text-gray-400 flex gap-2">
                    <span class="text-base flex-shrink-0">🔒</span>
                    <span>Payment held in <strong class="text-cyan-400">escrow</strong> until you confirm the account. 48-hour review window.</span>
                </div>

                {{-- Share Buttons --}}
                <div class="mb-4" x-data="{ copied: false }">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3
                                flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes text-indigo-400"></i>
                        Share This Listing
                    </div>

                    <div class="flex flex-col gap-2">

                        {{-- WhatsApp --}}
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($listing->title . ' — $' . number_format($listing->price, 2) . ' | GameTradeHub: ' . url()->current()) }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-green-500/20 bg-green-500/5
                                hover:bg-green-500/15 hover:border-green-500/40
                                hover:shadow-lg hover:shadow-green-500/10
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center
                                        flex-shrink-0 shadow-lg shadow-green-500/30
                                        group-hover:shadow-green-500/50 transition-all duration-200
                                        group-hover:scale-110">
                                <i class="fa-brands fa-whatsapp text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-green-400">WhatsApp</div>
                                <div class="text-xs text-gray-500">Share with friends</div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-green-500/50
                                    text-xs group-hover:text-green-400 transition-colors"></i>
                        </a>

                        {{-- Telegram --}}
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($listing->title . ' on GameTradeHub') }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-sky-500/20 bg-sky-500/5
                                hover:bg-sky-500/15 hover:border-sky-500/40
                                hover:shadow-lg hover:shadow-sky-500/10
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center
                                        flex-shrink-0 shadow-lg shadow-sky-500/30
                                        group-hover:shadow-sky-500/50 transition-all duration-200
                                        group-hover:scale-110">
                                <i class="fa-brands fa-telegram text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-sky-400">Telegram</div>
                                <div class="text-xs text-gray-500">Share to channel or chat</div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-sky-500/50
                                    text-xs group-hover:text-sky-400 transition-colors"></i>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-blue-500/20 bg-blue-500/5
                                hover:bg-blue-500/15 hover:border-blue-500/40
                                hover:shadow-lg hover:shadow-blue-500/10
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center
                                        flex-shrink-0 shadow-lg shadow-blue-500/30
                                        group-hover:shadow-blue-500/50 transition-all duration-200
                                        group-hover:scale-110">
                                <i class="fa-brands fa-facebook-f text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-blue-400">Facebook</div>
                                <div class="text-xs text-gray-500">Share to your timeline</div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-blue-500/50
                                    text-xs group-hover:text-blue-400 transition-colors"></i>
                        </a>

                        {{-- X / Twitter --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($listing->title . ' — $' . number_format($listing->price, 2)) }}&url={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-gray-600/30 bg-gray-800/50
                                hover:bg-gray-800 hover:border-gray-500/50
                                hover:shadow-lg hover:shadow-gray-500/10
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center
                                        flex-shrink-0 shadow-lg border border-gray-700
                                        group-hover:border-gray-500 transition-all duration-200
                                        group-hover:scale-110">
                                <i class="fa-brands fa-x-twitter text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-gray-300">X (Twitter)</div>
                                <div class="text-xs text-gray-500">Post to your feed</div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-gray-500/50
                                    text-xs group-hover:text-gray-400 transition-colors"></i>
                        </a>

                        {{-- Copy Link --}}
                        <button @click="
                                navigator.clipboard.writeText('{{ url()->current() }}');
                                copied = true;
                                setTimeout(() => copied = false, 2500)"
                                class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                    duration-200 border w-full text-left
                                    border-indigo-500/20 bg-indigo-500/5
                                    hover:bg-indigo-500/15 hover:border-indigo-500/40
                                    hover:shadow-lg hover:shadow-indigo-500/10
                                    hover:-translate-y-0.5">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                        flex-shrink-0 shadow-lg transition-all duration-200
                                        group-hover:scale-110"
                                :class="copied
                                        ? 'bg-green-500 shadow-green-500/30'
                                        : 'bg-indigo-600 shadow-indigo-500/30'">
                                <i class="text-white text-base"
                                :class="copied ? 'fa-solid fa-check' : 'fa-solid fa-link'"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold transition-colors duration-200"
                                    :class="copied ? 'text-green-400' : 'text-indigo-400'"
                                    x-text="copied ? 'Link Copied!' : 'Copy Link'">
                                </div>
                                <div class="text-xs text-gray-500"
                                    x-text="copied ? 'Ready to paste anywhere' : 'Copy direct link'">
                                </div>
                            </div>
                            <i class="fa-solid fa-copy text-indigo-500/50 text-xs
                                    group-hover:text-indigo-400 transition-colors"
                            x-show="!copied"></i>
                        </button>

                    </div>
                </div>

                @auth
                    @if($listing->user_id === auth()->id())
                    {{-- Own listing --}}
                    <div class="flex gap-2">
                        <a href="{{ route('listings.edit', $listing) }}"
                           class="flex-1 bg-gray-700 hover:bg-gray-600 text-white text-center
                                  py-2.5 rounded-xl text-sm font-semibold transition">
                            ✏️ Edit
                        </a>
                        <form method="POST" action="{{ route('listings.destroy', $listing) }}"
                              onsubmit="return confirm('Delete this listing?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-600/20 hover:bg-red-600/40 text-red-400
                                          py-2.5 px-4 rounded-xl text-sm font-semibold transition">
                                🗑️
                            </button>
                        </form>
                    </div>



                    @else
                    {{-- Buy button --}}
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <button type="submit"

                            <a href="{{ route('transactions.payment', $listing) }}"
                            class="block w-full text-center bg-green-600 hover:bg-green-500
                                    text-white py-3 rounded-xl font-bold text-sm transition">
                                🛒 Buy Now — ${{ number_format($listing->price, 2) }}
                            </a>

                        </button>
                    </form>
                    <div class="text-center text-xs text-gray-500 mt-2">
                        🏦 Pay via bank transfer · Escrow protected
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-800 text-center">
                        <a href="{{ route('listings.report', $listing) }}"
                        class="text-xs text-gray-600 hover:text-red-400 transition">
                            🚩 Report this listing
                        </a>
                    </div>
                    @endif
                @else
                <a href="{{ route('login') }}"
                   class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white text-center
                          py-3 rounded-xl font-bold text-sm transition">
                    Login to Buy
                </a>
                @endauth

                <div class="mt-4 pt-4 border-t border-gray-800 flex flex-col gap-2
                            text-xs text-gray-500">
                    <span>✅ Verified listing — proof screenshots checked</span>
                    <span>🔒 Escrow protected — funds safe until confirmed</span>
                    <span>⚖️ Dispute resolution available</span>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
