@extends('layouts.admin')
@section('title', 'Manage Auctions')

@section('content')

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold">🏆 Manage Auctions</h1>
        <div class="flex gap-2">
            @foreach(['pending' => '⏳ Pending', 'active' => '✅ Live', 'inactive' => '🏁 Ended', 'rejected' => '❌ Rejected'] as $status => $label)
            <a href="{{ route('admin.auctions.index', ['status' => $status]) }}"
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
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Auction</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Game</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Seller</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Start</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Current Bid</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Ends</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($listings as $listing)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-sm">
                            {{ Str::limit($listing->title, 35) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $listing->bids->count() }} bids
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $listing->game->name }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $listing->seller->name }}
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-green-400">
                        ${{ number_format($listing->starting_price, 2) }}
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-yellow-400">
                        {{ $listing->current_bid
                           ? '$'.number_format($listing->current_bid, 2)
                           : '—' }}
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-400">
                        {{ $listing->auction_ends_at
                           ? $listing->timeRemaining()
                           : '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.auctions.show', $listing) }}"
                               class="text-xs bg-gray-800 hover:bg-gray-700 px-2 py-1.5
                                      rounded-lg transition">
                                Review
                            </a>
                            @if($listing->status === 'pending')
                            <form method="POST"
                                  action="{{ route('admin.auctions.approve', $listing) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-green-600/20 hover:bg-green-600/40
                                               text-green-400 px-2 py-1.5 rounded-lg transition">
                                    ✓
                                </button>
                            </form>
                            @endif
                            @if($listing->status === 'active')
                            <form method="POST"
                                  action="{{ route('admin.auctions.end', $listing) }}"
                                  onsubmit="return confirm('End this auction now?')">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-red-600/20 hover:bg-red-600/40
                                               text-red-400 px-2 py-1.5 rounded-lg transition">
                                    End
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-500 text-sm">
                        No auctions found.
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
