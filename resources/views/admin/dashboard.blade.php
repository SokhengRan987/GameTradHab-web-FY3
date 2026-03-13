@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">📈 Dashboard</h1>
        <span class="text-sm text-gray-400">{{ now()->format('l, M d Y') }}</span>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        @php
            $statCards = [
                ['icon' => '👤', 'value' => $stats['total_users'],        'label' => 'Total Users',        'color' => 'indigo'],
                ['icon' => '🛒', 'value' => $stats['active_listings'],    'label' => 'Active Listings',    'color' => 'green'],
                ['icon' => '⏳', 'value' => $stats['pending_listings'],   'label' => 'Pending Approval',   'color' => 'yellow'],
                ['icon' => '💰', 'value' => '$'.number_format($stats['total_revenue'],2), 'label' => 'Total Revenue', 'color' => 'cyan'],
                ['icon' => '📊', 'value' => $stats['total_transactions'], 'label' => 'Transactions',       'color' => 'sky'],
                ['icon' => '⚖️', 'value' => $stats['open_disputes'],      'label' => 'Open Disputes',      'color' => 'red'],
            ];
        @endphp
        @foreach($statCards as $card)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl
                        bg-{{ $card['color'] }}-500/10 flex-shrink-0">
                {{ $card['icon'] }}
            </div>
            <div>
                <div class="text-2xl font-bold text-{{ $card['color'] }}-400">
                    {{ $card['value'] }}
                </div>
                <div class="text-xs text-gray-500">{{ $card['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-5">

        {{-- Pending Listings --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                <h2 class="font-bold text-sm flex items-center gap-2">
                    ⏳ Pending Listings
                    @if($stats['pending_listings'] > 0)
                    <span class="bg-yellow-500 text-black text-xs px-2 py-0.5 rounded-full font-bold">
                        {{ $stats['pending_listings'] }}
                    </span>
                    @endif
                </h2>
                <a href="{{ route('admin.listings.index') }}"
                   class="text-xs text-sky-400 hover:underline">View all →</a>
            </div>
            @forelse($pending_listings as $listing)
            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-800/50 last:border-0">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">{{ $listing->title }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $listing->game->name }} · {{ $listing->seller->name }}
                    </div>
                </div>
                <span class="text-green-400 font-bold text-sm">
                    ${{ number_format($listing->price, 2) }}
                </span>
                <div class="flex gap-1">
                    <a href="{{ route('admin.listings.show', $listing) }}"
                       class="text-xs bg-gray-800 hover:bg-gray-700 px-2 py-1 rounded-lg transition">
                        Review
                    </a>
                    <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                        @csrf @method('PATCH')
                        <button class="text-xs bg-green-600/20 hover:bg-green-600/40 text-green-400
                                       px-2 py-1 rounded-lg transition">
                            ✓
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-4 py-6 text-center text-gray-500 text-sm">
                No pending listings 🎉
            </div>
            @endforelse
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                <h2 class="font-bold text-sm">📊 Recent Transactions</h2>
                <a href="{{ route('admin.transactions.index') }}"
                   class="text-xs text-sky-400 hover:underline">View all →</a>
            </div>
            @forelse($recent_transactions as $txn)
            @php
                $colors = [
                    'escrow'    => 'text-cyan-400',
                    'completed' => 'text-green-400',
                    'disputed'  => 'text-red-400',
                    'refunded'  => 'text-yellow-400',
                    'pending'   => 'text-gray-400',
                ];
                $tcolor = $colors[$txn->status] ?? 'text-gray-400';
            @endphp
            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-800/50 last:border-0">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-mono text-gray-400">{{ $txn->transaction_code }}</div>
                    <div class="text-xs text-gray-500 truncate">
                        {{ $txn->listing->title ?? '—' }}
                    </div>
                </div>
                <span class="text-green-400 font-bold text-sm">
                    ${{ number_format($txn->amount, 2) }}
                </span>
                <span class="text-xs font-semibold {{ $tcolor }} capitalize">
                    {{ $txn->status }}
                </span>
            </div>
            @empty
            <div class="px-4 py-6 text-center text-gray-500 text-sm">No transactions yet.</div>
            @endforelse
        </div>

    </div>

@endsection
