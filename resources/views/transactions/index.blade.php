@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">My Orders</h1>
            <p class="text-gray-400 mt-2">Manage your purchases and sales in one place</p>
        </div>

        <div class="mt-4 sm:mt-0 flex gap-3">
            <a href="{{ route('listings.create') }}"
               class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 px-5 py-2.5 rounded-2xl text-sm font-medium transition">
                <span>+</span> Create Listing
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'purchases' }" class="w-full">
        <div class="flex border-b border-gray-800 mb-8">
            <button @click="tab = 'purchases'"
                    :class="tab === 'purchases'
                        ? 'border-b-2 border-indigo-500 text-white'
                        : 'text-gray-400 hover:text-white'"
                    class="px-8 py-4 font-semibold text-lg transition relative -mb-px">
                Purchases
                <span class="ml-2 text-sm font-normal text-gray-500">({{ $purchases->total() }})</span>
            </button>
            <button @click="tab = 'sales'"
                    :class="tab === 'sales'
                        ? 'border-b-2 border-indigo-500 text-white'
                        : 'text-gray-400 hover:text-white'"
                    class="px-8 py-4 font-semibold text-lg transition relative -mb-px">
                Sales
                <span class="ml-2 text-sm font-normal text-gray-500">({{ $sales->total() }})</span>
            </button>
        </div>

        {{-- Purchases Tab --}}
        <div x-show="tab === 'purchases'" x-transition>
            <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden">
                @if($purchases->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Order Code</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Item</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Seller</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Amount</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Date</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Status</th>
                            <th class="w-28"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($purchases as $txn)
                        @php
                            $colors = [
                                'pending'   => 'bg-gray-500/10 text-gray-400 border-gray-500/30',
                                'escrow'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                'disputed'  => 'bg-red-500/10 text-red-400 border-red-500/30',
                                'refunded'  => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            ];
                            $color = $colors[$txn->status] ?? 'bg-gray-500/10 text-gray-400';
                        @endphp
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-8 py-6 font-mono text-sm text-gray-400">
                                #{{ $txn->transaction_code }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-semibold text-white">
                                    {{ Str::limit($txn->listing->title ?? 'Deleted Listing', 45) }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-gray-300">
                                {{ $txn->seller->name ?? 'N/A' }}
                            </td>
                            <td class="px-8 py-6 font-semibold text-emerald-400">
                                ${{ number_format($txn->amount, 2) }}
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-400">
                                {{ $txn->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-2xl border {{ $color }}">
                                    {{ ucfirst($txn->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('transactions.show', $txn) }}"
                                   class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-medium text-sm group-hover:translate-x-0.5 transition">
                                    View Details →
                                </a>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>

                <div class="px-8 py-5 border-t border-gray-800">
                    {{ $purchases->links() }}
                </div>
                @else
                {{-- Empty State --}}
                <div class="py-24 text-center">
                    <div class="mx-auto w-20 h-20 bg-gray-800 rounded-3xl flex items-center justify-center text-4xl mb-6">
                        🛍️
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">No purchases yet</h3>
                    <p class="text-gray-400 max-w-sm mx-auto mb-8">
                        When you buy an account, it will appear here.
                    </p>
                    <a href="{{ route('listings.index') }}"
                       class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-500 px-8 py-4 rounded-2xl font-semibold transition">
                        Browse Available Accounts
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Sales Tab --}}
        <div x-show="tab === 'sales'" x-transition>
            <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden">
                @if($sales->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Order Code</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Item</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Buyer</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Payout</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Date</th>
                            <th class="text-left px-8 py-5 text-xs text-gray-500 font-semibold uppercase tracking-widest">Status</th>
                            <th class="w-28"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($sales as $txn)
                        @php
                            $colors = [
                                'pending'   => 'bg-gray-500/10 text-gray-400 border-gray-500/30',
                                'escrow'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                'disputed'  => 'bg-red-500/10 text-red-400 border-red-500/30',
                                'refunded'  => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            ];
                            $color = $colors[$txn->status] ?? 'bg-gray-500/10 text-gray-400';
                        @endphp
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-8 py-6 font-mono text-sm text-gray-400">
                                #{{ $txn->transaction_code }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-semibold text-white">
                                    {{ Str::limit($txn->listing->title ?? 'Deleted Listing', 45) }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-gray-300">
                                {{ $txn->buyer->name ?? 'N/A' }}
                            </td>
                            <td class="px-8 py-6 font-semibold text-emerald-400">
                                ${{ number_format($txn->seller_payout ?? $txn->amount, 2) }}
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-400">
                                {{ $txn->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-2xl border {{ $color }}">
                                    {{ ucfirst($txn->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('transactions.show', $txn) }}"
                                   class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-medium text-sm group-hover:translate-x-0.5 transition">
                                    View Details →
                                </a>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>

                <div class="px-8 py-5 border-t border-gray-800">
                    {{ $sales->links() }}
                </div>
                @else
                {{-- Empty State for Sales --}}
                <div class="py-24 text-center">
                    <div class="mx-auto w-20 h-20 bg-gray-800 rounded-3xl flex items-center justify-center text-4xl mb-6">
                        💰
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">No sales yet</h3>
                    <p class="text-gray-400 max-w-sm mx-auto mb-8">
                        Your sold accounts will appear here once you make your first sale.
                    </p>
                    <a href="{{ route('auctions.create') ?? route('listings.create') }}"
                       class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-500 px-8 py-4 rounded-2xl font-semibold transition">
                        Create New Listing
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
