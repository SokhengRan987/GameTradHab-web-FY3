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
            $cards = [
                ['icon'=>'👥', 'value'=>$stats['total_users'],        'sub'=>$stats['new_users_today'].' new today',                          'label'=>'Total Users',     'color'=>'indigo'],
                ['icon'=>'🛒', 'value'=>$stats['active_listings'],    'sub'=>'listings live now',                                             'label'=>'Active Listings', 'color'=>'green'],
                ['icon'=>'🚩', 'value'=>$stats['flagged_listings'],   'sub'=>'need review',                                                   'label'=>'Flagged',         'color'=>'yellow'],
                ['icon'=>'📋', 'value'=>$stats['open_reports'],       'sub'=>'pending review',                                                'label'=>'Open Reports',    'color'=>'red'],
                ['icon'=>'⚖️', 'value'=>$stats['open_disputes'],      'sub'=>'awaiting resolution',                                           'label'=>'Disputes',        'color'=>'orange'],
                ['icon'=>'💰', 'value'=>'$'.number_format($stats['total_revenue'],2), 'sub'=>'$'.number_format($stats['revenue_today'],2).' today', 'label'=>'Revenue', 'color'=>'cyan'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center
                        text-xl flex-shrink-0 bg-{{ $card['color'] }}-500/10">
                {{ $card['icon'] }}
            </div>
            <div>
                <div class="text-2xl font-bold text-{{ $card['color'] }}-400">
                    {{ $card['value'] }}
                </div>
                <div class="text-xs text-gray-500">{{ $card['label'] }}</div>
                <div class="text-xs text-gray-600">{{ $card['sub'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-5 mb-5">

        {{-- Open Reports --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                <h2 class="font-bold text-sm flex items-center gap-2">
                    📋 Recent Reports
                    @if($stats['open_reports'] > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $stats['open_reports'] }}
                    </span>
                    @endif
                </h2>
                <a href="{{ route('admin.reports.index') }}"
                   class="text-xs text-sky-400 hover:underline">View all →</a>
            </div>
            @forelse($recent_reports as $report)
            <div class="flex items-center gap-3 px-4 py-3
                        border-b border-gray-800/50 last:border-0">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">
                        {{ $report->listing->title ?? '—' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        By {{ $report->reporter->name }}
                        · {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                    </div>
                </div>
                <a href="{{ route('admin.reports.index') }}"
                   class="text-xs bg-gray-800 hover:bg-gray-700
                          px-2 py-1 rounded-lg transition flex-shrink-0">
                    Review
                </a>
            </div>
            @empty
            <div class="px-4 py-6 text-center text-gray-500 text-sm">
                No pending reports 🎉
            </div>
            @endforelse
        </div>

        {{-- Open Disputes --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                <h2 class="font-bold text-sm flex items-center gap-2">
                    ⚖️ Open Disputes
                    @if($stats['open_disputes'] > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $stats['open_disputes'] }}
                    </span>
                    @endif
                </h2>
                <a href="{{ route('admin.transactions.index', ['status' => 'disputed']) }}"
                   class="text-xs text-sky-400 hover:underline">View all →</a>
            </div>
            @forelse($recent_disputes as $txn)
            <div class="flex items-center gap-3 px-4 py-3
                        border-b border-gray-800/50 last:border-0">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">
                        {{ $txn->listing->title ?? '—' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $txn->buyer->name }} vs {{ $txn->seller->name }}
                    </div>
                </div>
                <span class="text-green-400 font-bold text-sm flex-shrink-0">
                    ${{ number_format($txn->amount, 2) }}
                </span>
            </div>
            @empty
            <div class="px-4 py-6 text-center text-gray-500 text-sm">
                No open disputes 🎉
            </div>
            @endforelse
        </div>

    </div>

    {{-- Flagged Listings --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
            <h2 class="font-bold text-sm flex items-center gap-2">
                🚩 Auto-Flagged Listings
                @if($stats['flagged_listings'] > 0)
                <span class="bg-yellow-500 text-black text-xs
                             px-2 py-0.5 rounded-full font-bold">
                    {{ $stats['flagged_listings'] }}
                </span>
                @endif
            </h2>
            <a href="{{ route('admin.listings.index', ['filter' => 'flagged']) }}"
               class="text-xs text-sky-400 hover:underline">View all →</a>
        </div>
        @forelse($flagged_listings as $listing)
        <div class="flex items-center gap-3 px-4 py-3
                    border-b border-gray-800/50 last:border-0">
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate">
                    {{ $listing->title }}
                </div>
                <div class="text-xs text-red-400">
                    ⚠️ {{ $listing->flag_reason }}
                </div>
            </div>
            <span class="text-green-400 font-bold text-sm flex-shrink-0">
                ${{ number_format($listing->price, 2) }}
            </span>
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('admin.listings.show', $listing) }}"
                   class="text-xs bg-gray-800 hover:bg-gray-700
                          px-2 py-1 rounded-lg transition">
                    Review
                </a>
                <form method="POST"
                      action="{{ route('admin.listings.unflag', $listing) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs bg-green-600/20 hover:bg-green-600/40
                                   text-green-400 px-2 py-1 rounded-lg transition">
                        ✓ Clear
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-4 py-6 text-center text-gray-500 text-sm">
            No flagged listings 🎉
        </div>
        @endforelse
    </div>

@endsection
