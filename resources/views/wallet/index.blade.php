{{-----@extends('layouts.app')
@section('title', 'My Wallet')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">💰 My Earnings</h1>

    {{-- Balance Card --}}
     {{--  <div class="bg-gradient-to-br from-indigo-600/20 to-cyan-600/10
                border border-indigo-500/25 rounded-2xl p-6 mb-5 relative overflow-hidden">
        <div class="absolute right-4 top-4 text-7xl opacity-5">💰</div>
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
            Available Balance
        </div>
        <div class="text-4xl font-bold mb-1">
            ${{ number_format(auth()->user()->wallet_balance, 2) }}
        </div>
        <div class="text-xs text-gray-500">
            Seller earnings from completed sales · Escrow-protected
        </div>
    </div>

    {{-- Info box --}}
     {{--  <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mb-5
                flex items-start gap-3">
        <span class="text-xl">ℹ️</span>
        <div>
            <div class="text-sm font-semibold text-blue-300 mb-1">How payments work</div>
            <div class="text-xs text-gray-400 leading-relaxed">
                When you sell an item, your earnings are held in escrow until the buyer confirms receipt.
                Once confirmed, your payout appears here. Buyers pay directly by card — no top-up needed.
            </div>
        </div>
    </div>

    {{-- Transaction History --}}
     {{-- <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
            <h2 class="font-bold">Transaction History</h2>
            <span class="text-xs text-gray-500">{{ $logs->total() }} records</span>
        </div>
        @forelse($logs as $log)
        <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-800/50 last:border-0">
            @php
                $isCredit = in_array($log->type, ['topup', 'payout', 'refund', 'card_payment']);
                $icons = [
                    'topup'        => '💳',
                    'card_payment' => '💳',
                    'purchase'     => '🛒',
                    'payout'       => '💰',
                    'refund'       => '↩️',
                    'withdrawal'   => '🏦',
                ];
            @endphp
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0
                        {{ $isCredit ? 'bg-green-500/10' : 'bg-red-500/10' }}">
                {{ $icons[$log->type] ?? '💸' }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold capitalize">
                    {{ $log->description ?? ucfirst($log->type) }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $log->created_at->format('M d, Y · H:i') }}
                </div>
            </div>
            <div class="text-right">
                <div class="font-bold text-sm {{ $isCredit ? 'text-green-400' : 'text-red-400' }}">
                    {{ $isCredit ? '+' : '−' }}${{ number_format($log->amount, 2) }}
                </div>
                <div class="text-xs text-gray-500">
                    Bal: ${{ number_format($log->balance_after, 2) }}
                </div>
            </div>
        </div>
        @empty
        <div class="px-4 py-10 text-center text-gray-500 text-sm">
            No transactions yet.
        </div>
        @endforelse
        <div class="px-4 py-3">
            {{ $logs->links() }}
        </div>
    </div>

<<<<<<< Updated upstream
  {{--</div>---}}
  {{--@endsection ----}}
=======
</div>
@endsection --}}
>>>>>>> Stashed changes
