@extends('layouts.admin')
@section('title', 'Review Auction')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.auctions.index') }}"
           class="text-sm text-gray-400 hover:text-white transition">
            ← Back to Auctions
        </a>
    </div>

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold">🔍 Review Auction</h1>
        @if($listing->status === 'pending')
        <form method="POST" action="{{ route('admin.auctions.approve', $listing) }}">
            @csrf @method('PATCH')
            <button class="bg-green-600 hover:bg-green-500 text-white
                           px-4 py-2 rounded-xl text-sm font-bold transition">
                ✓ Approve Auction
            </button>
        </form>
        @endif
        @if($listing->status === 'active')
        <form method="POST" action="{{ route('admin.auctions.end', $listing) }}"
              onsubmit="return confirm('End this auction now?')">
            @csrf @method('PATCH')
            <button class="bg-red-600/20 hover:bg-red-600/40 text-red-400
                           border border-red-500/30 px-4 py-2 rounded-xl text-sm font-bold transition">
                ⏹ End Auction Now
            </button>
        </form>
        @endif
    </div>

    <div class="grid grid-cols-3 gap-5">

        <div class="col-span-2 flex flex-col gap-4">

            {{-- Details --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Auction Details
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        'Title'          => $listing->title,
                        'Game'           => $listing->game->name,
                        'Starting Price' => '$'.number_format($listing->starting_price, 2),
                        'Bid Increment'  => '$'.number_format($listing->bid_increment, 2),
                        'Current Bid'    => $listing->current_bid ? '$'.number_format($listing->current_bid,2) : 'No bids',
                        'Ends At'        => $listing->auction_ends_at?->format('M d, Y H:i') ?? '—',
                        'Platform'       => $listing->platform,
                        'Rank'           => $listing->rank ?? '—',
                    ] as $label => $value)
                    <div class="bg-gray-800 rounded-xl p-3">
                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                            {{ $label }}
                        </div>
                        <div class="font-semibold text-sm">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                        Description
                    </div>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        {{ $listing->description }}
                    </p>
                </div>
            </div>

            {{-- Screenshots --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Proof Screenshots ({{ $listing->images->count() }})
                </div>
                @if($listing->images->count() > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($listing->images as $image)
                    <a href="{{ $image->url }}" target="_blank">
                        <img src="{{ $image->url }}"
                             class="w-full aspect-video object-cover rounded-xl
                                    border border-gray-700 hover:border-indigo-500 transition">
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-sm">No screenshots uploaded.</p>
                @endif
            </div>

            {{-- Bid History --}}
            @if($listing->bids->count() > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-800 font-bold text-sm">
                    Bid History ({{ $listing->bids->count() }})
                </div>
                @foreach($listing->bids->take(10) as $bid)
                <div class="flex items-center gap-3 px-4 py-3
                            border-b border-gray-800/50 last:border-0">
                    <div class="flex-1 text-sm font-semibold">
                        {{ $bid->user->name }}
                    </div>
                    <div class="text-yellow-400 font-bold text-sm">
                        ${{ number_format($bid->amount, 2) }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $bid->created_at->diffForHumans() }}
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                                 {{ $bid->status === 'active' ? 'bg-green-500/10 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                        {{ ucfirst($bid->status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-4">

            {{-- Seller --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Seller Info
                </div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center
                                justify-center font-bold">
                        {{ strtoupper(substr($listing->seller->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ $listing->seller->name }}</div>
                        <div class="text-xs text-gray-400">{{ $listing->seller->email }}</div>
                    </div>
                </div>
                <div class="text-sm flex justify-between">
                    <span class="text-gray-400">Total Sales</span>
                    <span>{{ $listing->seller->total_sales }}</span>
                </div>
            </div>

            {{-- Actions --}}
            @if($listing->status === 'pending')
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Quick Actions
                </div>
                <div class="flex flex-col gap-2">
                    <form method="POST" action="{{ route('admin.auctions.approve', $listing) }}">
                        @csrf @method('PATCH')
                        <button class="w-full bg-green-600 hover:bg-green-500 text-white
                                       py-2.5 rounded-xl text-sm font-bold transition">
                            ✓ Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.auctions.reject', $listing) }}"
                          x-data="{ show: false }">
                        @csrf @method('PATCH')
                        <button type="button" @click="show = !show"
                                class="w-full bg-red-600/20 hover:bg-red-600/40 text-red-400
                                       border border-red-500/30 py-2.5 rounded-xl
                                       text-sm font-bold transition">
                            ✕ Reject
                        </button>
                        <div x-show="show" class="mt-2">
                            <textarea name="admin_notes" rows="3"
                                      placeholder="Reason for rejection..."
                                      class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                             px-3 py-2 text-sm text-white focus:outline-none
                                             focus:border-red-500 resize-none mb-2"></textarea>
                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-500 text-white
                                           py-2 rounded-xl text-sm font-bold transition">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

@endsection
