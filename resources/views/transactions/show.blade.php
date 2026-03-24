@extends('layouts.app')
@section('title', 'Transaction ' . $transaction->transaction_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold">Transaction Details</h1>
            <code class="text-gray-400 text-sm">{{ $transaction->transaction_code }}</code>
        </div>
        @php
            $colors = [
                'pending'   => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                'escrow'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                'disputed'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                'refunded'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            ];
            $color = $colors[$transaction->status] ?? 'bg-gray-500/10 text-gray-400';
        @endphp
        <span class="text-sm px-3 py-1.5 rounded-full border font-semibold {{ $color }}">
            {{ ucfirst($transaction->status) }}
        </span>
    </div>

    <div class="grid grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="col-span-2 flex flex-col gap-4">

            {{-- Item --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Item Purchased
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-16 h-12 bg-gray-800 rounded-lg flex items-center
                                justify-center text-2xl flex-shrink-0">🎮</div>
                    <div>
                        <div class="font-semibold">{{ $transaction->listing->title }}</div>
                        <div class="text-sm text-gray-400">
                            {{ $transaction->listing->game->name }} ·
                            {{ $transaction->listing->rank }} ·
                            {{ $transaction->listing->server }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                    Transaction Timeline
                </div>
                @php
                    $steps = [
                        ['icon' => '💳', 'label' => 'Payment Initiated',         'done' => true],
                        ['icon' => '🔒', 'label' => 'Funds Held in Escrow',      'done' => in_array($transaction->status, ['escrow','completed','disputed','refunded'])],
                        ['icon' => '📩', 'label' => 'Account Credentials Sent',  'done' => in_array($transaction->status, ['completed','refunded'])],
                        ['icon' => '✅', 'label' => 'Buyer Confirmed Receipt',   'done' => $transaction->status === 'completed'],
                        ['icon' => '💰', 'label' => 'Seller Paid',               'done' => $transaction->status === 'completed'],
                    ];
                @endphp
                <div class="flex flex-col gap-0">
                    @foreach($steps as $step)
                    <div class="flex items-center gap-3 py-3
                                {{ !$loop->last ? 'border-b border-gray-800' : '' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0
                                    {{ $step['done'] ? 'bg-green-500/15 border border-green-500/30' : 'bg-gray-800 border border-gray-700' }}">
                            {{ $step['icon'] }}
                        </div>
                        <div class="flex-1 text-sm font-medium
                                    {{ $step['done'] ? 'text-white' : 'text-gray-500' }}">
                            {{ $step['label'] }}
                        </div>
                        @if($step['done'])
                        <span class="text-xs text-green-400 font-bold">✓ Done</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Action Box --}}
            @if($transaction->status === 'escrow' && $transaction->buyer_id === auth()->id())
            <div class="bg-cyan-500/5 border border-cyan-500/20 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-bold">⏳ Awaiting Your Confirmation</div>
                    @if($transaction->review_deadline)
                    <div class="text-right">
                        <div class="text-xs text-gray-400">Deadline</div>
                        <div class="text-cyan-400 font-bold text-sm">
                            {{ $transaction->review_deadline->format('M d, Y H:i') }}
                        </div>
                    </div>
                    @endif
                </div>
                <p class="text-sm text-gray-400 mb-4 leading-relaxed">
                    Test the account thoroughly. If everything is correct, confirm to release payment.
                    If there is an issue, raise a dispute.
                </p>
                <div class="flex gap-3">
                    <form method="POST"
                          action="{{ route('transactions.confirm', $transaction) }}"
                          onsubmit="return confirm('Confirm receipt and release payment to seller?')">
                        @csrf
                        <button class="bg-green-600 hover:bg-green-500 text-white
                                       px-5 py-2.5 rounded-xl text-sm font-bold transition">
                            ✅ Confirm Receipt
                        </button>
                    </form>
                    <form method="POST"
                          action="{{ route('transactions.dispute', $transaction) }}"
                          onsubmit="return confirm('Raise a dispute? Admin will review within 48 hours.')">
                        @csrf
                        <button class="bg-red-600/20 hover:bg-red-600/40 text-red-400
                                       border border-red-500/30 px-5 py-2.5 rounded-xl text-sm font-bold transition">
                            ⚠️ Raise Dispute
                        </button>
                    </form>
                </div>
            </div>
            @endif
            {{-- Review Section --}}
            @if($transaction->status === 'completed' && $transaction->buyer_id === auth()->id())
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">

                @if($transaction->hasReview())
                {{-- Already reviewed --}}
                @php $review = $transaction->review @endphp
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    ⭐ Your Review
                </div>
                <div class="bg-gray-800 rounded-xl p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-yellow-400 text-lg">
                            {{ $review->stars() }}
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ $review->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-gray-300">{{ $review->comment }}</p>
                    @endif
                    <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                        class="mt-2"
                        onsubmit="return confirm('Delete your review?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-300 transition">
                            🗑️ Delete review
                        </button>
                    </form>
                </div>

                @else
                {{-- Leave a review --}}
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    ⭐ Leave a Review
                </div>
                <p class="text-xs text-gray-400 mb-4">
                    How was your experience with {{ $transaction->seller->name }}?
                </p>

                <form method="POST"
                    action="{{ route('reviews.store', $transaction) }}"
                    x-data="{ rating: 0, hover: 0 }">
                    @csrf

                    {{-- Star Rating --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-400 mb-2">
                            Rating *
                        </label>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    @click="rating = {{ $i }}"
                                    @mouseenter="hover = {{ $i }}"
                                    @mouseleave="hover = 0"
                                    class="text-3xl transition-transform hover:scale-110">
                                <span x-text="(hover || rating) >= {{ $i }} ? '⭐' : '☆'"
                                    :class="(hover || rating) >= {{ $i }}
                                            ? 'text-yellow-400'
                                            : 'text-gray-600'">
                                </span>
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                        <p x-show="rating > 0"
                        class="text-xs text-gray-400 mt-1"
                        x-text="['', 'Terrible 😞', 'Bad 😕', 'Okay 😐', 'Good 😊', 'Excellent! 🎉'][rating]">
                        </p>
                    </div>

                    {{-- Comment --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                            Comment (Optional)
                        </label>
                        <textarea name="comment" rows="3"
                                placeholder="Share your experience with this seller..."
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                        px-3 py-2.5 text-sm text-white
                                        focus:outline-none focus:border-yellow-500 resize-none">{{ old('comment') }}</textarea>
                    </div>

                    <button type="submit"
                            x-bind:disabled="rating === 0"
                            x-bind:class="rating === 0
                                ? 'bg-gray-700 text-gray-500 cursor-not-allowed'
                                : 'bg-yellow-500 hover:bg-yellow-400 text-black cursor-pointer'"
                            class="w-full py-2.5 rounded-xl font-bold text-sm transition">
                        Submit Review
                    </button>

                </form>
                @endif

            </div>
            @endif

        </div>

        {{-- Right --}}
        <div class="flex flex-col gap-4">

            {{-- Summary --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Payment Summary
                </div>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Amount</span>
                        <span>${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Platform Fee</span>
                        <span class="text-red-400">−${{ number_format($transaction->platform_fee, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-800 my-1"></div>
                    <div class="flex justify-between font-bold">
                        <span>You paid</span>
                        <span class="text-green-400">${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Parties --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Parties
                </div>
                <div class="flex items-center gap-2 pb-3 border-b border-gray-800">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center
                                justify-center text-xs font-bold">
                        {{ strtoupper(substr($transaction->buyer->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold">{{ $transaction->buyer->name }}</div>
                        <div class="text-xs text-gray-500">Buyer</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-3">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center
                                justify-center text-xs font-bold">
                        {{ strtoupper(substr($transaction->seller->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold">{{ $transaction->seller->name }}</div>
                        <div class="text-xs text-gray-500">Seller</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
