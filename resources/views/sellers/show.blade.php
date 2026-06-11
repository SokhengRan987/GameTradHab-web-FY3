@extends('layouts.app')
@section('title', $user->name . "'s Profile")

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Seller Header --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6 flex items-center gap-6">
        <div class="w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center
                    text-3xl font-bold flex-shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-xl font-bold">{{ $user->name }}</h1>
                @if($user->is_verified)
                <span class="text-xs bg-sky-500/15 text-sky-400 border border-sky-500/25
                             px-2 py-0.5 rounded-full">✓ Verified</span>
                @endif
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-400">
                <span>Cart {{ $user->total_sales }} sales</span>
                @if($user->rating_avg > 0)
                <span>Rating {{ number_format($user->rating_avg, 1) }} / 5.0</span>
                @endif
                <span>Joined {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Left — Reviews --}}
        <div class="col-span-2 flex flex-col gap-4">

            {{-- Rating Summary --}}
            @if($totalReviews > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <h2 class="font-bold mb-4">Ratings & Reviews</h2>
                <div class="flex items-center gap-6 mb-5">
                    <div class="text-center">
                        <div class="text-5xl font-bold text-yellow-400">
                            {{ number_format($user->rating_avg, 1) }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">out of 5.0</div>
                        <div class="text-xs text-gray-500">{{ $totalReviews }} reviews</div>
                    </div>
                    <div class="flex-1">
                        @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs text-gray-400 w-4">{{ $i }}</span>
                            <span class="text-yellow-400 text-xs">★</span>
                            <div class="flex-1 bg-gray-800 rounded-full h-2">
                                @php
                                    $pct = $totalReviews > 0
                                        ? ($ratingBreakdown[$i] / $totalReviews) * 100
                                        : 0;
                                @endphp
                                <div class="bg-yellow-400 h-2 rounded-full"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-4">{{ $ratingBreakdown[$i] }}</span>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endif

            {{-- Review List --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-800 font-bold text-sm">
                    Comments ({{ $totalReviews }})
                </div>

                @forelse($reviews as $review)
                <div class="px-5 py-4 border-b border-gray-800 last:border-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center
                                        justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold">{{ $review->reviewer->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $review->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            {!! $review->stars() !!}
                        </div>
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-gray-300 leading-relaxed ml-10">
                        {{ $review->comment }}
                    </p>
                    @endif
                    @if($review->listing)
                    <div class="ml-10 mt-2">
                        <span class="text-xs text-gray-600">
                            Purchase: {{ $review->listing->title }}
                        </span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="px-5 py-10 text-center text-gray-500 text-sm">
                    No reviews yet for this seller.
                </div>
                @endforelse

                {{-- Pagination --}}
                @if($reviews->hasPages())
                <div class="px-5 py-3 border-t border-gray-800">
                    {{ $reviews->links() }}
                </div>
                @endif
            </div>

        </div>

        {{-- Right — Active Listings --}}
        <div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-800 font-bold text-sm">
                    Active Listings
                </div>

                @forelse($listings as $listing)
                <a href="{{ route('listings.show', $listing) }}"
                   class="flex items-center gap-3 px-4 py-3 border-b border-gray-800
                          last:border-0 hover:bg-gray-800 transition">
                    <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center
                                justify-center text-lg flex-shrink-0">
                        🎮
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold truncate">{{ $listing->title }}</div>
                        <div class="text-xs text-gray-500">{{ $listing->game->name }}</div>
                    </div>
                    <div class="text-sm font-bold text-green-400 flex-shrink-0">
                        ${{ number_format($listing->price, 2) }}
                    </div>
                </a>
                @empty
                <div class="px-4 py-6 text-center text-gray-500 text-sm">
                    No active listings.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
