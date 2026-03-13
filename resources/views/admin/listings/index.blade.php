@extends('layouts.admin')
@section('title', 'Manage Listings')

@section('content')

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold">🛒 Manage Listings</h1>
        <div class="flex gap-2">
            @foreach(['pending' => '⏳ Pending', 'active' => '✅ Active', 'rejected' => '❌ Rejected', 'sold' => '🏷️ Sold'] as $status => $label)
            <a href="{{ route('admin.listings.index', ['status' => $status]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                      {{ request('status', 'pending') === $status
                         ? 'bg-sky-600 text-white'
                         : 'bg-gray-800 text-gray-400 hover:text-white' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Listing</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Game</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Seller</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Price</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($listings as $listing)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-sm">{{ Str::limit($listing->title, 40) }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $listing->images->count() }} screenshots ·
                            {{ $listing->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">{{ $listing->game->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-300">{{ $listing->seller->name }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-green-400">
                        ${{ number_format($listing->price, 2) }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $colors = [
                                'active'   => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'pending'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                'sold'     => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                'rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold
                                     {{ $colors[$listing->status] ?? '' }}">
                            {{ ucfirst($listing->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.listings.show', $listing) }}"
                               class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5
                                      rounded-lg transition">
                                Review
                            </a>
                            @if($listing->status === 'pending')
                            <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-green-600/20 hover:bg-green-600/40
                                               text-green-400 px-3 py-1.5 rounded-lg transition">
                                    ✓ Approve
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500 text-sm">
                        No listings found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-800">
            {{ $listings->withQueryString()->links() }}
        </div>
    </div>

@endsection
