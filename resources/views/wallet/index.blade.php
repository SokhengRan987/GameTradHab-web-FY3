@extends('layouts.app')
@section('title', 'My Wallet')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">💰 My Wallet</h1>

    {{-- Balance Card --}}
    <div class="bg-gradient-to-br from-indigo-600/20 to-cyan-600/10
                border border-indigo-500/25 rounded-2xl p-6 mb-5 relative overflow-hidden">
        <div class="absolute right-4 top-4 text-7xl opacity-5">💰</div>
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
            Available Balance
        </div>
        <div class="text-4xl font-bold mb-1">
            ${{ number_format(auth()->user()->wallet_balance, 2) }}
        </div>
        <div class="text-xs text-gray-500">Usable for purchases · Escrow-protected</div>
    </div>

    {{-- Top Up Form --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-5">
        <h2 class="font-bold mb-4">➕ Top Up Wallet</h2>
        <form method="POST" action="{{ route('wallet.topup') }}">
            @csrf
            <div class="flex gap-3 mb-3">
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2
                                 text-gray-400 font-bold">$</span>
                    <input type="number" name="amount" value="{{ old('amount', 50) }}"
                           step="0.01" min="1"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  pl-7 pr-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white
                               px-5 py-2.5 rounded-xl text-sm font-bold transition">
                    Top Up
                </button>
            </div>
            {{-- Quick amounts --}}
            <div class="flex gap-2">
                @foreach([10, 25, 50, 100, 200] as $amt)
                <button type="button"
                        onclick="this.closest('form').querySelector('[name=amount]').value={{ $amt }}"
                        class="bg-gray-800 hover:bg-gray-700 border border-gray-700
                               text-gray-300 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                    ${{ $amt }}
                </button>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Transaction History --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
            <h2 class="font-bold">Transaction History</h2>
            <span class="text-xs text-gray-500">{{ $logs->total() }} records</span>
        </div>
        @forelse($logs as $log)
        <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-800/50 last:border-0">
            @php
                $isCredit = in_array($log->type, ['topup', 'payout', 'refund']);
                $icons = [
                    'topup'      => '💳',
                    'purchase'   => '🛒',
                    'payout'     => '💰',
                    'refund'     => '↩️',
                    'withdrawal' => '🏦',
                ];
            @endphp
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0
                        {{ $isCredit ? 'bg-green-500/10' : 'bg-red-500/10' }}">
                {{ $icons[$log->type] ?? '💸' }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold capitalize">{{ $log->description ?? ucfirst($log->type) }}</div>
                <div class="text-xs text-gray-500">{{ $log->created_at->format('M d, Y · H:i') }}</div>
            </div>
            <div class="text-right">
                <div class="font-bold text-sm {{ $isCredit ? 'text-green-400' : 'text-red-400' }}">
                    {{ $isCredit ? '+' : '−' }}${{ number_format($log->amount, 2) }}
                </div>
                <div class="text-xs text-gray-500">Bal: ${{ number_format($log->balance_after, 2) }}</div>
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

</div>
@endsection
