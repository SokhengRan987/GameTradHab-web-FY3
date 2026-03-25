@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">
                👋 Welcome back, {{ auth()->user()->name }}!
            </h1>
            <p class="text-gray-400 text-sm mt-1">
                {{ now()->format('l, F j Y') }}
            </p>
        </div>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex items-center gap-1 px-4 py-2 bg-indigo-600
                           hover:bg-indigo-500 text-white text-sm font-semibold
                           rounded-lg transition">
                + Sell Item <span class="text-xs">▾</span>
            </button>
            <div x-show="open" @click.outside="open = false"
                 class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700
                        rounded-xl shadow-xl py-1 z-50">
                <a href="{{ route('listings.create') }}"
                   class="block px-4 py-2 text-sm text-gray-300
                          hover:bg-gray-700 hover:text-white">
                    🛒 Fixed Price
                </a>
                <a href="{{ route('auctions.create') }}"
                   class="block px-4 py-2 text-sm text-gray-300
                          hover:bg-gray-700 hover:text-white">
                    🏆 Auction
                </a>
            </div>
        </div>
    </div>

    {{-- Profile incomplete warning --}}
    @if(!auth()->user()->profile_completed)
    <div class="bg-yellow-500/10 border border-yellow-500/25 rounded-xl p-4 mb-5
                flex items-center gap-3">
        <span class="text-2xl">⚠️</span>
        <div class="flex-1">
            <div class="font-semibold text-yellow-400 text-sm">
                Complete your profile
            </div>
            <div class="text-xs text-gray-400">
                Add your contact info to build trust with buyers
            </div>
        </div>
        <a href="{{ route('profile.edit') }}"
           class="bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400
                  px-3 py-1.5 rounded-lg text-xs font-semibold transition">
            Complete Now →
        </a>
    </div>
    @endif

    {{-- Stats --}}
    @php
        $totalSales     = auth()->user()->sales()->where('status', 'completed')->count();
        $totalEarned    = auth()->user()->sales()->where('status', 'completed')->sum('seller_payout');
        $totalSpent     = auth()->user()->purchases()->where('status', 'completed')->sum('amount');
        $activeListings = $listings->where('status', 'active')->count();
        $pendingListings= $listings->where('status', 'pending')->count();
        $soldListings   = $listings->where('status', 'sold')->count();
        $openDisputes   = auth()->user()->purchases()->where('status', 'disputed')->count()
                        + auth()->user()->sales()->where('status', 'disputed')->count();
    @endphp

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                Wallet Balance
            </div>
            <div class="text-2xl font-bold text-yellow-400">
                ${{ number_format(auth()->user()->wallet_balance, 2) }}
            </div>
            <a href="{{ route('wallet.index') }}"
               class="text-xs text-gray-500 hover:text-indigo-400 transition">
                Top up →
            </a>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                Total Earned
            </div>
            <div class="text-2xl font-bold text-green-400">
                ${{ number_format($totalEarned, 2) }}
            </div>
            <div class="text-xs text-gray-500">
                from {{ $totalSales }} sales
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                Total Spent
            </div>
            <div class="text-2xl font-bold text-indigo-400">
                ${{ number_format($totalSpent, 2) }}
            </div>
            <div class="text-xs text-gray-500">
                {{ auth()->user()->purchases()->count() }} orders
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                My Rating
            </div>
            @if(auth()->user()->rating_avg > 0)
            <div class="text-2xl font-bold text-yellow-400">
                ⭐ {{ number_format(auth()->user()->rating_avg, 1) }}
            </div>
            <div class="text-xs text-gray-500">
                from {{ auth()->user()->reviews()->count() }} reviews
            </div>
            @else
            <div class="text-2xl font-bold text-gray-600">—</div>
            <div class="text-xs text-gray-500">No reviews yet</div>
            @endif
        </div>
    </div>

    {{-- Listing status summary --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3
                    flex items-center gap-3">
            <div class="w-8 h-8 bg-green-500/15 rounded-lg flex items-center
                        justify-center text-sm">🟢</div>
            <div>
                <div class="text-lg font-bold text-green-400">{{ $activeListings }}</div>
                <div class="text-xs text-gray-500">Active Listings</div>
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3
                    flex items-center gap-3">
            <div class="w-8 h-8 bg-yellow-500/15 rounded-lg flex items-center
                        justify-center text-sm">⏳</div>
            <div>
                <div class="text-lg font-bold text-yellow-400">{{ $pendingListings }}</div>
                <div class="text-xs text-gray-500">Under Review</div>
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3
                    flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-500/15 rounded-lg flex items-center
                        justify-center text-sm">✅</div>
            <div>
                <div class="text-lg font-bold text-indigo-400">{{ $soldListings }}</div>
                <div class="text-xs text-gray-500">Sold</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-6">

        {{-- My Listings --}}
        <div class="col-span-3">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-lg">My Listings</h2>
                <a href="{{ route('listings.create') }}"
                   class="text-xs text-indigo-400 hover:underline">
                    + New listing
                </a>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                @forelse($listings as $listing)
                <div class="flex items-center gap-4 px-4 py-3
                            border-b border-gray-800 last:border-0">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm truncate">
                            {{ $listing->title }}
                        </div>
                        <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                            <span>{{ $listing->game->name }}</span>
                            <span>·</span>
                            <span>{{ ucfirst($listing->type) }}</span>
                            <span>·</span>
                            <span>👁 {{ $listing->views_count }}</span>
                        </div>
                    </div>
                    <div class="text-green-400 font-bold text-sm">
                        ${{ number_format($listing->price, 2) }}
                    </div>
                    @php
                        $colors = [
                            'active'   => 'bg-green-500/10 text-green-400 border-green-500/20',
                            'pending'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                            'sold'     => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                            'rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
                            'inactive' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                        ];
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full border font-semibold
                                 {{ $colors[$listing->status] ?? '' }}">
                        {{ ucfirst($listing->status) }}
                    </span>
                    @if($listing->is_flagged)
                    <span class="text-xs text-yellow-400" title="{{ $listing->flag_reason }}">
                        🚩
                    </span>
                    @endif
                    <a href="{{ route('listings.edit', $listing) }}"
                       class="text-xs text-gray-500 hover:text-white transition">
                        Edit
                    </a>
                </div>
                @empty
                <div class="px-4 py-8 text-center text-gray-500 text-sm">
                    No listings yet.
                    <a href="{{ route('listings.create') }}"
                       class="text-indigo-400 hover:underline ml-1">
                        Create your first listing →
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-lg">Recent Orders</h2>
                <a href="{{ route('transactions.index') }}"
                   class="text-xs text-indigo-400 hover:underline">
                    View all
                </a>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                @forelse($purchases as $purchase)
                @php
                    $tcolors = [
                        'escrow'    => 'text-cyan-400',
                        'completed' => 'text-green-400',
                        'disputed'  => 'text-red-400',
                        'refunded'  => 'text-yellow-400',
                        'pending'   => 'text-gray-400',
                    ];
                @endphp
                <a href="{{ route('transactions.show', $purchase) }}"
                   class="flex items-center gap-3 px-4 py-3
                          border-b border-gray-800 last:border-0
                          hover:bg-gray-800/50 transition block">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm truncate">
                            {{ $purchase->listing->title ?? 'Deleted' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $purchase->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="font-bold text-sm text-green-400">
                            ${{ number_format($purchase->amount, 2) }}
                        </div>
                        <div class="text-xs {{ $tcolors[$purchase->status] ?? 'text-gray-400' }}">
                            {{ ucfirst($purchase->status) }}
                        </div>
                    </div>
                </a>
                @empty
                <div class="px-4 py-8 text-center text-gray-500 text-sm">
                    No orders yet.
                    <a href="{{ route('listings.index') }}"
                       class="text-indigo-400 hover:underline ml-1">
                        Browse listings →
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Open disputes warning --}}
            @if($openDisputes > 0)
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 mt-3">
                <div class="text-sm font-bold text-red-400">
                    ⚠️ {{ $openDisputes }} open dispute{{ $openDisputes > 1 ? 's' : '' }}
                </div>
                <a href="{{ route('transactions.index') }}"
                   class="text-xs text-gray-400 hover:text-white transition">
                    View transactions →
                </a>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
