@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">👋 Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-400 text-sm mt-1">Manage your listings and orders</p>
        </div>
        <a href="{{ route('listings.create') }}"
           class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2
                  rounded-xl text-sm font-semibold transition">
            + Sell Account
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-500/15 rounded-xl flex items-center justify-center text-lg">📝</div>
            <div>
                <div class="text-2xl font-bold text-indigo-400">{{ $listings->count() }}</div>
                <div class="text-xs text-gray-500">My Listings</div>
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/15 rounded-xl flex items-center justify-center text-lg">💰</div>
            <div>
                <div class="text-2xl font-bold text-green-400">
                    ${{ number_format(auth()->user()->wallet_balance, 2) }}
                </div>
                <div class="text-xs text-gray-500">Wallet Balance</div>
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-cyan-500/15 rounded-xl flex items-center justify-center text-lg">🛒</div>
            <div>
                <div class="text-2xl font-bold text-cyan-400">{{ $purchases->count() }}</div>
                <div class="text-xs text-gray-500">My Orders</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-6">

        {{-- My Listings --}}
        <div class="col-span-3">
            <h2 class="font-bold text-lg mb-3">My Listings</h2>
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                @forelse($listings as $listing)
                <div class="flex items-center gap-4 px-4 py-3 border-b border-gray-800 last:border-0">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm truncate">{{ $listing->title }}</div>
                        <div class="text-xs text-gray-500">{{ $listing->game->name }}</div>
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
                        ];
                        $color = $colors[$listing->status] ?? 'bg-gray-500/10 text-gray-400';
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full border {{ $color }} font-semibold">
                        {{ ucfirst($listing->status) }}
                    </span>
                    <a href="{{ route('listings.edit', $listing) }}"
                       class="text-xs text-gray-500 hover:text-white transition">Edit</a>
                </div>
                @empty
                <div class="px-4 py-8 text-center text-gray-500 text-sm">
                    No listings yet.
                    <a href="{{ route('listings.create') }}" class="text-indigo-400 hover:underline ml-1">
                        Create your first listing →
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-span-2">
            <h2 class="font-bold text-lg mb-3">Recent Orders</h2>
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                @forelse($purchases as $purchase)
                <div class="px-4 py-3 border-b border-gray-800 last:border-0">
                    <div class="font-semibold text-sm truncate">
                        {{ $purchase->listing->title ?? 'Deleted listing' }}
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-gray-500">
                            {{ $purchase->created_at->diffForHumans() }}
                        </span>
                        <span class="text-xs font-bold text-green-400">
                            ${{ number_format($purchase->amount, 2) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-4 py-8 text-center text-gray-500 text-sm">
                    No orders yet.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
