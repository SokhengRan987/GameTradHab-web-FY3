@extends('layouts.admin')
@section('title', 'Transactions')

@section('content')

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold">💳 Transactions</h1>
        <div class="flex gap-2">
            @foreach(['' => 'All', 'escrow' => '🔒 Escrow', 'completed' => '✅ Done', 'disputed' => '⚠️ Disputed'] as $status => $label)
            <a href="{{ route('admin.transactions.index', $status ? ['status' => $status] : []) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                      {{ request('status', '') === $status
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
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Code</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Listing</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Buyer</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Seller</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Amount</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                    <td class="px-4 py-3 font-mono text-xs text-gray-400">
                        {{ $txn->transaction_code }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ Str::limit($txn->listing->title ?? '—', 30) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">{{ $txn->buyer->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-300">{{ $txn->seller->name }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-green-400">
                        ${{ number_format($txn->amount, 2) }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $colors = [
                                'escrow'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'disputed'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                                'refunded'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                'pending'   => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold
                                     {{ $colors[$txn->status] ?? '' }}">
                            {{ ucfirst($txn->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            @if($txn->status === 'disputed')
                            <form method="POST"
                                  action="{{ route('admin.transactions.release', $txn) }}"
                                  onsubmit="return confirm('Release escrow to seller?')">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-green-600/20 hover:bg-green-600/40
                                               text-green-400 px-2 py-1 rounded-lg transition">
                                    Release
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.transactions.refund', $txn) }}"
                                  onsubmit="return confirm('Refund buyer?')">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-yellow-600/20 hover:bg-yellow-600/40
                                               text-yellow-400 px-2 py-1 rounded-lg transition">
                                    Refund
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-500 text-sm">
                        No transactions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-800">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>

@endsection
