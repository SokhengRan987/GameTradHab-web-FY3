@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">📦 My Orders</h1>

    {{-- Tabs --}}
    <div x-data="{ tab: 'purchases' }" class="w-full">

        <div class="flex gap-2 mb-5">
            <button @click="tab = 'purchases'"
                    :class="tab === 'purchases'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-800 text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                🛒 Purchases ({{ $purchases->total() }})
            </button>
            <button @click="tab = 'sales'"
                    :class="tab === 'sales'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-800 text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                💰 Sales ({{ $sales->total() }})
            </button>
        </div>

        {{-- Purchases Tab --}}
        <div x-show="tab === 'purchases'">
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Code</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Item</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Seller</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Date</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $txn)
                        @php
                            $colors = [
                                'pending'   => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                                'escrow'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'disputed'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                                'refunded'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                            ];
                            $color = $colors[$txn->status] ?? 'bg-gray-500/10 text-gray-400';
                        @endphp
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                            <td class="px-4 py-3 font-mono text-xs text-gray-400">
                                {{ $txn->transaction_code }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-sm">
                                    {{ Str::limit($txn->listing->title ?? 'Deleted', 35) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-300">
                                {{ $txn->seller->name }}
                            </td>
                            <td class="px-4 py-3 font-bold text-green-400 text-sm">
                                ${{ number_format($txn->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400">
                                {{ $txn->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full border font-semibold {{ $color }}">
                                    {{ ucfirst($txn->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('transactions.show', $txn) }}"
                                   class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-lg transition">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500 text-sm">
                                No purchases yet.
                                <a href="{{ route('listings.index') }}"
                                   class="text-indigo-400 hover:underline ml-1">
                                    Browse listings →
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-800">
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>

        {{-- Sales Tab --}}
        <div x-show="tab === 'sales'">
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Code</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Item</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Buyer</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Payout</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Date</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $txn)
                        @php
                            $colors = [
                                'pending'   => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                                'escrow'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'disputed'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                                'refunded'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                            ];
                            $color = $colors[$txn->status] ?? 'bg-gray-500/10 text-gray-400';
                        @endphp
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                            <td class="px-4 py-3 font-mono text-xs text-gray-400">
                                {{ $txn->transaction_code }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-sm">
                                    {{ Str::limit($txn->listing->title ?? 'Deleted', 35) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-300">
                                {{ $txn->buyer->name }}
                            </td>
                            <td class="px-4 py-3 font-bold text-green-400 text-sm">
                                ${{ number_format($txn->seller_payout, 2) }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400">
                                {{ $txn->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full border font-semibold {{ $color }}">
                                    {{ ucfirst($txn->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('transactions.show', $txn) }}"
                                   class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-lg transition">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500 text-sm">
                                No sales yet.
                                <a href="{{ route('listings.create') }}"
                                   class="text-indigo-400 hover:underline ml-1">
                                    Create a listing →
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-800">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
