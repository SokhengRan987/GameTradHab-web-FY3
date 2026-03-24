@extends('layouts.app')
@section('title', 'My Bids')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">🏆 My Bids</h1>

    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Auction</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">My Bid</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Current Bid</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Ends</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bids as $bid)
                @php
                    $isWinning = $bid->listing->highest_bidder_id === auth()->id()
                                 && $bid->listing->isLive();
                    $colors = [
                        'active'    => 'bg-green-500/10 text-green-400 border-green-500/20',
                        'outbid'    => 'bg-red-500/10 text-red-400 border-red-500/20',
                        'won'       => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                        'lost'      => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                        'cancelled' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                    ];
                @endphp
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-sm">
                            {{ Str::limit($bid->listing->title ?? '—', 35) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $bid->listing->game->name ?? '—' }}
                        </div>
                    </td>
                    <td class="px-4 py-3 font-bold text-yellow-400 text-sm">
                        ${{ number_format($bid->amount, 2) }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($bid->listing->current_bid)
                        <span class="{{ $isWinning ? 'text-green-400 font-bold' : 'text-gray-300' }}">
                            ${{ number_format($bid->listing->current_bid, 2) }}
                        </span>
                        @else
                        <span class="text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-400">
                        @if($bid->listing->auction_ends_at)
                            {{ $bid->listing->timeRemaining() }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($isWinning)
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold
                                     bg-green-500/10 text-green-400 border-green-500/20">
                            👑 Winning
                        </span>
                        @else
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold
                                     {{ $colors[$bid->status] ?? '' }}">
                            {{ ucfirst($bid->status) }}
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($bid->listing)
                        <a href="{{ route('auctions.show', $bid->listing) }}"
                           class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5
                                  rounded-lg transition">
                            View
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500 text-sm">
                        No bids placed yet.
                        <a href="{{ route('auctions.index') }}"
                           class="text-indigo-400 hover:underline ml-1">
                            Browse auctions →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-800">
            {{ $bids->links() }}
        </div>
    </div>

</div>
@endsection
