@extends('layouts.app')
@section('title', $listing->title . ' — GameTradeHub')
@section('og_type', 'product')
@section('og_title', $listing->title . ' — GameTradeHub')
@section('og_description', 'Buy: ' . $listing->title . ' | ' . $listing->game->name . ' | $' . number_format($listing->price, 2) . ' | Escrow Protected')
@section('og_image', $listing->firstImage ? $listing->firstImage->url : asset('images/og-default.jpg'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- ✅ DEBUG INFO (temporary) --}}
    <div class="bg-gray-800 text-white p-4 rounded-lg mb-4 text-xs">
        <p>Now (UTC): {{ now()->utc() }}</p>
        <p>Ends (UTC): {{ $listing->auction_ends_at }}</p>
        <p>Has Ended? {{ $listing->hasEnded() ? 'YES' : 'NO' }}</p>
    </div>

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('auctions.index') }}" class="hover:text-white">Auctions</a>
        <span>›</span>
        <span>{{ $listing->game->name }}</span>
        <span>›</span>
        <span class="text-gray-300 truncate">{{ $listing->title }}</span>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Left — Images + Details --}}
        <div class="col-span-2 flex flex-col gap-4">

            {{-- Main Image --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl h-64
                        flex items-center justify-center text-6xl overflow-hidden">
                @if($listing->images->count() > 0)
                <img src="{{ $listing->images->first()->url }}"
                     class="w-full h-full object-cover" id="mainImg">
                @else
                <span>🏆</span>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($listing->images->count() > 1)
            <div class="flex gap-2">
                @foreach($listing->images as $image)
                <img src="{{ $image->url }}"
                     onclick="document.getElementById('mainImg').src='{{ $image->url }}'"
                     class="w-16 h-12 object-cover rounded-lg border-2 border-gray-700
                            hover:border-yellow-500 cursor-pointer transition">
                @endforeach
            </div>
            @endif

            {{-- Specs --}}
            <div class="grid grid-cols-3 gap-3">
                @foreach([
                    'Game'        => $listing->game->name,
                    'Rank'        => $listing->rank ?? '—',
                    'Level'       => $listing->level ?? '—',
                    'Platform'    => $listing->platform,

                ] as $label => $value)
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                        {{ $label }}
                    </div>
                    <div class="font-bold text-sm">{{ $value }}</div>
                </div>
                @endforeach
            </div>

            {{-- Description+
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <h3 class="font-bold mb-3">📝 Description</h3>
                <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </p>
            </div> --}}

            {{-- Bid History --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-800 font-bold text-sm">
                    📋 Bid History ({{ $bidHistory->count() }})
                </div>
                @forelse($bidHistory as $bid)
                <div class="flex items-center gap-3 px-4 py-3
                            border-b border-gray-800/50 last:border-0">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center
                                justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($bid->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold">
                            {{ $bid->user->name }}
                            @if($loop->first && $listing->isLive())
                            <span class="text-xs bg-yellow-500/15 text-yellow-400
                                         border border-yellow-500/25 px-2 py-0.5
                                         rounded-full ml-1">
                                👑 Highest
                            </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $bid->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="font-bold text-yellow-400">
                        ${{ number_format($bid->amount, 2) }}
                    </div>
                </div>
                @empty
                <div class="px-4 py-6 text-center text-gray-500 text-sm">
                    No bids yet — be the first!
                </div>
                @endforelse
            </div>

        </div>

        {{-- Right — Bid Box --}}
        <div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">

                {{-- Timer --}}
                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl
                            p-3 mb-4 text-center"
                     id="timerBox">
                    <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">
                        Time Remaining
                    </div>
                    <div class="text-2xl font-bold text-yellow-400 font-mono"
                         id="countdown"
                         data-ends="{{ $listing->auction_ends_at->toIso8601String() }}"
                    >
                        {{ $listing->timeRemaining() }}
                    </div>
                </div>

                <div class="mb-4">
                    {{-- Current Bid --}}
                    <div  class="text-xs text-gray-400 mb-1">Current Bid</div>
                    <div id="currentBid" class="text-3xl font-bold text-yellow-400 font-mono">
                        ${{ number_format($listing->current_bid ?? $listing->starting_price, 2) }}
                    </div>

                    @if($listing->highestBidder)
                    <div id="highestBidder" class="text-xs text-gray-400 mt-1">
                        by {{ $listing->highestBidder->name }}
                    </div>
                    @else
                    <div id="highestBidder" class="text-xs text-gray-400 mt-1">
                        No bids yet
                    </div>
                    @endif

                    {{-- Starting Price ALWAYS visible --}}
                    <div class="mt-3 text-xs text-gray-500">
                        Starting price:
                        <strong class="text-gray-300">
                            ${{ number_format($listing->starting_price, 2) }}
                        </strong>
                    </div>

                </div>

                {{-- Min next bid info --}}
                <div class="bg-gray-800 rounded-xl px-3 py-2 mb-4 text-sm flex justify-between">
                    <span class="text-gray-400">Minimum next bid</span>
                    <strong class="text-white">
                        ${{ number_format($listing->minimumNextBid(), 2) }}
                    </strong>
                </div>

                {{-- Seller --}}
                <div class="flex items-center gap-3 bg-gray-800 rounded-xl p-3 mb-4">
                    <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center
                                justify-center font-bold text-sm">
                        {{ strtoupper(substr($listing->seller->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm">{{ $listing->seller->name }}</div>
                        <div class="text-xs text-gray-400">
                            🛒 {{ $listing->seller->total_sales }} sales
                        </div>
                    </div>
                </div>

                {{-- Bid Form --}}
                @auth
                    @if($listing->user_id === auth()->id())
                    <div class="bg-gray-800 rounded-xl p-3 text-center text-sm text-gray-400">
                        This is your auction listing
                    </div>
                    @elseif($listing->isLive())
                    <form method="POST" action="{{ route('auctions.bid', $listing) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                Enter your bid
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2
                                             text-gray-400 font-bold">$</span>
                                <input type="number" name="amount"
                                       step="0.01"
                                       min="{{ $listing->minimumNextBid() }}"
                                       value="{{ old('amount', $listing->minimumNextBid()) }}"
                                       class="w-full bg-gray-800 border border-gray-700
                                              rounded-xl pl-7 pr-3 py-2.5 text-sm text-white
                                              focus:outline-none focus:border-yellow-500">
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-yellow-500 hover:bg-yellow-400 text-black
                                       py-3 rounded-xl font-bold text-sm transition">
                            🏆 Place Bid
                        </button>
                        <div class="text-center text-xs text-gray-500 mt-2">
                            Your balance:
                            <strong class="text-yellow-400">
                                ${{ number_format(auth()->user()->wallet_balance, 2) }}
                            </strong>
                        </div>

                    </form>
                    @else
                    <div class="bg-red-500/10 border border-red-500/20 rounded-xl
                                p-3 text-center text-sm text-red-400">
                        This auction has ended
                    </div>
                    @endif
                @else
                <a href="{{ route('login') }}"
                   class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white
                          text-center py-3 rounded-xl font-bold text-sm transition">
                    Login to Bid
                </a>
                @endauth

                {{-- Escrow notice --}}
                <div class="mt-4 pt-4 border-t border-gray-800 text-xs text-gray-500
                            flex flex-col gap-1.5">
                    <span>🔒 Winner pays via escrow — funds safe</span>
                    <span>⏰ Bid increment: ${{ number_format($listing->bid_increment, 2) }}</span>
                    <span>📅 Ends: {{ $listing->auction_ends_at->format('M d, Y · H:i') }}</span>
                </div>

                {{-- Share Buttons --}}
                <div class="mt-4 pt-4 border-t border-gray-800" x-data="{ copied: false }">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3
                                flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes text-yellow-400"></i>
                        Share This Auction
                    </div>

                    <div class="flex flex-col gap-2">

                        <a href="https://api.whatsapp.com/send?text={{ urlencode('🏆 AUCTION: ' . $listing->title . ' | Bid now on GameTradeHub: ' . url()->current()) }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-green-500/20 bg-green-500/5
                                hover:bg-green-500/15 hover:border-green-500/40
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center
                                        flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-brands fa-whatsapp text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-green-400">WhatsApp</div>
                                <div class="text-xs text-gray-500">Share this auction</div>
                            </div>
                        </a>

                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode('🏆 AUCTION: ' . $listing->title . ' | Bid now!') }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-sky-500/20 bg-sky-500/5
                                hover:bg-sky-500/15 hover:border-sky-500/40
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center
                                        flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-brands fa-telegram text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-sky-400">Telegram</div>
                                <div class="text-xs text-gray-500">Share to channel</div>
                            </div>
                        </a>

                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-blue-500/20 bg-blue-500/5
                                hover:bg-blue-500/15 hover:border-blue-500/40
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center
                                        flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-brands fa-facebook-f text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-blue-400">Facebook</div>
                                <div class="text-xs text-gray-500">Share to timeline</div>
                            </div>
                        </a>

                        <a href="https://twitter.com/intent/tweet?text={{ urlencode('🏆 AUCTION: ' . $listing->title) }}&url={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                duration-200 border border-gray-600/30 bg-gray-800/50
                                hover:bg-gray-800 hover:border-gray-500/50
                                hover:-translate-y-0.5">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center
                                        flex-shrink-0 border border-gray-700
                                        group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-brands fa-x-twitter text-white text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-gray-300">X (Twitter)</div>
                                <div class="text-xs text-gray-500">Tweet this auction</div>
                            </div>
                        </a>

                        <button @click="
                                navigator.clipboard.writeText('{{ url()->current() }}');
                                copied = true;
                                setTimeout(() => copied = false, 2500)"
                                class="group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all
                                    duration-200 border w-full text-left
                                    border-yellow-500/20 bg-yellow-500/5
                                    hover:bg-yellow-500/15 hover:border-yellow-500/40
                                    hover:-translate-y-0.5">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                        flex-shrink-0 transition-all duration-200 group-hover:scale-110"
                                :class="copied ? 'bg-green-500' : 'bg-yellow-500'">
                                <i class="text-black text-base"
                                :class="copied ? 'fa-solid fa-check' : 'fa-solid fa-link'"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold"
                                    :class="copied ? 'text-green-400' : 'text-yellow-400'"
                                    x-text="copied ? 'Link Copied!' : 'Copy Link'">
                                </div>
                                <div class="text-xs text-gray-500">
                                    Share the auction link
                                </div>
                            </div>
                        </button>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>

document.addEventListener("DOMContentLoaded", function () {

    const listingId = {{ $listing->id }};

    console.log('Listening to auction channel:', listingId);

    Echo.channel('auction.' + listingId)
        .listen('.BidPlaced', (e) => {


            console.log('Live bid received:', e);

            // Update current bid
            document.getElementById('currentBid').innerText =
                '$' + parseFloat(e.bid.amount).toFixed(2);

            // Update bidder name
            const nameEl = document.getElementById('highestBidder');
                if (nameEl) {
                    nameEl.innerText = 'by ' + e.bid.user.name;
                }
         });
});

// Live countdown timer
function updateCountdown() {
    const el = document.getElementById('countdown');
    if (!el) return;

    const endsAt = new Date(el.dataset.ends);
    const now    = new Date();
    const diff   = endsAt - now;

    if (diff <= 0) {
        el.textContent = 'Ended';
        document.getElementById('timerBox').classList.add('opacity-50');
        return;
    }

    const days    = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    if (days > 0) {
        el.textContent = `${days}d ${hours}h ${minutes}m`;
    } else if (hours > 0) {
        el.textContent = `${hours}h ${minutes}m ${seconds}s`;
    } else {
        el.textContent = `${minutes}m ${seconds}s`;
        el.classList.add('text-red-400');
        el.classList.remove('text-yellow-400');
    }
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endpush
