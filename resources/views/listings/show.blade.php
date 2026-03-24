@extends('layouts.app')
@section('title', $listing->title)

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
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Server</div>
                    <div class="font-bold text-sm">{{ $listing->server ?? '—' }}</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Platform</div>
                    <div class="font-bold text-sm">{{ $listing->platform }}</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Account Age</div>
                    <div class="font-bold text-sm">{{ $listing->account_age ?? '—' }}</div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <h3 class="font-bold mb-3">📝 Description</h3>
                <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </p>
            </div>
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
                            <span class="text-yellow-400 text-sm">
                                {{ $review->stars() }}
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
                                class="w-full bg-green-600 hover:bg-green-500 text-white
                                       py-3 rounded-xl font-bold text-sm transition">
                            🛒 Buy Now — ${{ number_format($listing->price, 2) }}
                        </button>
                    </form>
                    <div class="text-center text-xs text-gray-500 mt-2">
                        Your balance:
                        <strong class="text-yellow-400">
                            ${{ number_format(auth()->user()->wallet_balance, 2) }}
                        </strong>
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
