@extends('layouts.app')
@section('title', 'Order #' . $transaction->transaction_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('transactions.index') }}"
           class="w-9 h-9 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center
                  justify-center text-gray-400 transition">←</a>
        <div>
            <h1 class="font-game text-lg font-bold text-white tracking-wider">
                ORDER DETAILS
            </h1>
            <div class="text-xs text-gray-500 font-mono">
                {{ $transaction->transaction_code }}
            </div>
        </div>
        <div class="ml-auto">
            @php
                $statusConfig = [
                    'pending'   => ['bg-orange-500/10 text-orange-400 border-orange-500/20', '⏳ Awaiting Payment'],
                    'paid'      => ['bg-blue-500/10 text-blue-400 border-blue-500/20',       '🕐 Verifying Payment'],
                    'escrow'    => ['bg-cyan-500/10 text-cyan-400 border-cyan-500/20',        '🔒 In Escrow'],
                    'completed' => ['bg-green-500/10 text-green-400 border-green-500/20',    '✅ Completed'],
                    'disputed'  => ['bg-red-500/10 text-red-400 border-red-500/20',          '⚠️ Disputed'],
                    'refunded'  => ['bg-yellow-500/10 text-yellow-400 border-yellow-500/20', '↩️ Refunded'],
                    'cancelled' => ['bg-gray-500/10 text-gray-400 border-gray-500/20',       '✕ Cancelled'],
                ];
                [$statusClass, $statusLabel] = $statusConfig[$transaction->status]
                    ?? ['bg-gray-500/10 text-gray-400 border-gray-500/20', ucfirst($transaction->status)];
            @endphp
            <span class="text-xs px-3 py-1.5 rounded-full border font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-5">

        {{-- LEFT --}}
        <div class="col-span-2 flex flex-col gap-4">

            {{-- Item Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Item
                </div>
                <div class="flex items-center gap-4">
                    {{-- Image --}}
                    <div class="w-16 h-14 bg-gray-800 rounded-xl overflow-hidden flex-shrink-0">
                        @if($transaction->listing->firstImage ?? false)
                        <img src="{{ $transaction->listing->firstImage->url }}"
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">🎮</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-white truncate">
                            {{ $transaction->listing->title }}
                        </div>
                        <div class="text-sm text-gray-400 mt-0.5">
                            {{ $transaction->listing->game->name }}
                            @if($transaction->listing->rank)
                            · {{ $transaction->listing->rank }}
                            @endif
                            @if($transaction->listing->server)
                            · {{ $transaction->listing->server }}
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ $transaction->listing->platform }}
                            · {{ $transaction->listing->type === 'auction' ? '🏆 Won Auction' : '🛒 Fixed Price' }}
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="font-game font-bold text-green-400 text-lg">
                            ${{ number_format($transaction->amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                    Order Progress
                </div>
                @php
                    $steps = [
                        [
                            'icon'  => '🛒',
                            'label' => 'Order Created',
                            'sub'   => $transaction->created_at->format('M d, Y · H:i'),
                            'done'  => true,
                        ],
                        [
                            'icon'  => '🏦',
                            'label' => 'Payment Sent',
                            'sub'   => $transaction->buyer_paid_at
                                        ? $transaction->buyer_paid_at->format('M d, Y · H:i')
                                        : 'Waiting for your transfer',
                            'done'  => in_array($transaction->status, ['paid','escrow','completed','disputed','refunded']),
                        ],
                        [
                            'icon'  => '✅',
                            'label' => 'Payment Verified',
                            'sub'   => $transaction->admin_confirmed_at
                                        ? $transaction->admin_confirmed_at->format('M d, Y · H:i')
                                        : 'Admin checking your transfer',
                            'done'  => in_array($transaction->status, ['escrow','completed','disputed','refunded']),
                        ],
                        [
                            'icon'  => '📩',
                            'label' => 'Account Delivered',
                            'sub'   => 'Seller sends account credentials',
                            'done'  => in_array($transaction->status, ['completed','refunded']),
                        ],
                        [
                            'icon'  => '🎉',
                            'label' => 'Completed',
                            'sub'   => 'You confirmed receipt',
                            'done'  => $transaction->status === 'completed',
                        ],
                    ];
                @endphp
                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-4 top-4 bottom-4 w-px bg-gray-800"></div>

                    <div class="flex flex-col gap-0">
                        @foreach($steps as $step)
                        <div class="flex items-start gap-4 pb-4 last:pb-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        text-sm flex-shrink-0 relative z-10
                                        {{ $step['done']
                                            ? 'bg-green-500 text-white'
                                            : 'bg-gray-800 border border-gray-700 text-gray-500' }}">
                                {{ $step['done'] ? '✓' : $step['icon'] }}
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="text-sm font-semibold
                                            {{ $step['done'] ? 'text-white' : 'text-gray-500' }}">
                                    {{ $step['label'] }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $step['sub'] }}
                                </div>
                            </div>
                            @if($step['done'] && $loop->first)
                            <span class="text-xs text-green-400 font-bold pt-1">Done</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ACTION BOX --}}
            @if($transaction->status === 'pending' && $transaction->buyer_id === auth()->id())
            {{-- Awaiting payment --}}
            <div class="bg-orange-500/5 border-2 border-orange-500/30 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-2xl">🏦</span>
                    <div>
                        <div class="font-bold text-orange-400">
                            Action Required — Complete Payment
                        </div>
                        <div class="text-xs text-gray-400">
                            Transfer ${{ number_format($transaction->amount, 2) }} to secure your order
                        </div>
                    </div>
                </div>
                <a href="{{ route('transactions.payment', $transaction) }}"
                   class="flex items-center justify-center gap-2 w-full
                          bg-orange-600 hover:bg-orange-500 text-white
                          font-bold py-3 rounded-xl text-sm transition"
                   style="box-shadow: 0 0 20px rgba(234,88,12,0.3)">
                    🏦 View Bank Transfer Instructions →
                </a>
            </div>

            @elseif($transaction->status === 'paid' && $transaction->buyer_id === auth()->id())
            {{-- Verifying --}}
            <div class="bg-blue-500/5 border-2 border-blue-500/30 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-2xl">🕐</span>
                    <div>
                        <div class="font-bold text-blue-400">Payment Submitted — Verifying</div>
                        <div class="text-xs text-gray-400">
                            Admin usually verifies within 1–3 hours
                        </div>
                    </div>
                </div>
                @if($transaction->payment_note)
                <div class="bg-gray-800 rounded-xl px-3 py-2 text-xs text-gray-400">
                    📝 Your note: {{ $transaction->payment_note }}
                </div>
                @endif
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                    <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                    Waiting for admin confirmation...
                </div>
            </div>

            @elseif($transaction->status === 'escrow' && $transaction->buyer_id === auth()->id())
            {{-- Escrow — contact seller --}}
            <div class="bg-cyan-500/5 border-2 border-cyan-500/30 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🔒</span>
                        <div>
                            <div class="font-bold text-cyan-400">
                                Payment Confirmed!
                            </div>
                            <div class="text-xs text-gray-400">
                                Contact the seller to receive your account
                            </div>
                        </div>
                    </div>
                    @if($transaction->review_deadline)
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Confirm by</div>
                        <div class="text-cyan-400 font-bold text-sm">
                            {{ $transaction->review_deadline->format('M d · H:i') }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Seller contacts --}}
                @if($transaction->listing->contact_telegram ||
                    $transaction->listing->contact_whatsapp ||
                    $transaction->listing->contact_discord)
                <div class="bg-gray-800/50 rounded-xl p-3 mb-4">
                    <div class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wider">
                        Contact Seller
                    </div>
                    <div class="flex flex-col gap-2">
                        @if($transaction->listing->contact_telegram)
                        <a href="https://t.me/{{ $transaction->listing->contact_telegram }}"
                           target="_blank"
                           class="flex items-center gap-2 bg-sky-500/10 border border-sky-500/20
                                  rounded-xl px-3 py-2 hover:bg-sky-500/20 transition">
                            <span class="text-base">✈️</span>
                            <div>
                                <div class="text-xs text-gray-400">Telegram</div>
                                <div class="text-sm font-semibold text-sky-400">
                                    @{{ $transaction->listing->contact_telegram }}
                                </div>
                            </div>
                            <span class="ml-auto text-sky-400 text-xs">Open →</span>
                        </a>
                        @endif
                        @if($transaction->listing->contact_whatsapp)
                        <a href="https://wa.me/{{ $transaction->listing->contact_whatsapp }}"
                           target="_blank"
                           class="flex items-center gap-2 bg-green-500/10 border border-green-500/20
                                  rounded-xl px-3 py-2 hover:bg-green-500/20 transition">
                            <span class="text-base">📱</span>
                            <div>
                                <div class="text-xs text-gray-400">WhatsApp</div>
                                <div class="text-sm font-semibold text-green-400">
                                    +{{ $transaction->listing->contact_whatsapp }}
                                </div>
                            </div>
                            <span class="ml-auto text-green-400 text-xs">Open →</span>
                        </a>
                        @endif
                        @if($transaction->listing->contact_discord)
                        <div class="flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20
                                    rounded-xl px-3 py-2">
                            <span class="text-base">🎮</span>
                            <div>
                                <div class="text-xs text-gray-400">Discord</div>
                                <div class="text-sm font-semibold text-indigo-400">
                                    {{ $transaction->listing->contact_discord }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Confirm / Dispute --}}
                <div class="flex gap-3">
                    <form method="POST"
                          action="{{ route('transactions.confirm', $transaction) }}"
                          class="flex-1"
                          onsubmit="return confirm('Confirm you received the account?')">
                        @csrf
                        <button class="w-full bg-green-600 hover:bg-green-500 text-white
                                       py-3 rounded-xl text-sm font-bold transition">
                            ✅ Confirm Receipt
                        </button>
                    </form>
                    <form method="POST"
                          action="{{ route('transactions.dispute', $transaction) }}"
                          onsubmit="return confirm('Raise a dispute?')">
                        @csrf
                        <button class="bg-red-600/20 hover:bg-red-600/40 text-red-400
                                       border border-red-500/30 px-5 py-3
                                       rounded-xl text-sm font-bold transition whitespace-nowrap">
                            ⚠️ Dispute
                        </button>
                    </form>
                </div>
            </div>

            @elseif($transaction->status === 'completed')
            <div class="bg-green-500/5 border-2 border-green-500/30 rounded-2xl p-5
                        text-center">
                <div class="text-4xl mb-2">🎉</div>
                <div class="font-bold text-green-400 mb-1">Transaction Completed!</div>
                <div class="text-sm text-gray-400">
                    Enjoy your new account. Don't forget to leave a review below!
                </div>
            </div>

            @elseif($transaction->status === 'disputed')
            <div class="bg-red-500/5 border-2 border-red-500/30 rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⚖️</span>
                    <div>
                        <div class="font-bold text-red-400">Dispute In Progress</div>
                        <div class="text-xs text-gray-400">
                            Admin is reviewing your case. Expected resolution: 24–48 hours.
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- REVIEW --}}
            @if($transaction->status === 'completed' && $transaction->buyer_id === auth()->id())
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                @if($transaction->hasReview())
                @php $review = $transaction->review @endphp
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    ⭐ Your Review
                </div>
                <div class="bg-gray-800 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-yellow-400 text-lg">{!! $review->stars() !!}</div>
                        <span class="text-xs text-gray-500">
                            {{ $review->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-gray-300">{{ $review->comment }}</p>
                    @endif
                    <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                          class="mt-3"
                          onsubmit="return confirm('Delete your review?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-300 transition">
                            🗑️ Delete review
                        </button>
                    </form>
                </div>
                @else
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
                    <div class="mb-4">
                        <div class="flex gap-2 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    @click="rating = {{ $i }}"
                                    @mouseenter="hover = {{ $i }}"
                                    @mouseleave="hover = 0"
                                    class="text-3xl transition-transform hover:scale-110">
                                <span :class="(hover || rating) >= {{ $i }}
                                              ? 'text-yellow-400' : 'text-gray-700'"
                                      x-text="(hover || rating) >= {{ $i }} ? '★' : '☆'">
                                </span>
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                        <p x-show="rating > 0"
                           class="text-xs text-gray-400"
                           x-text="['','Terrible 😞','Bad 😕','Okay 😐','Good 😊','Excellent! 🎉'][rating]">
                        </p>
                    </div>
                    <textarea name="comment" rows="3"
                              placeholder="Share your experience..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                     px-3 py-2.5 text-sm text-white placeholder-gray-600
                                     focus:outline-none focus:border-yellow-500
                                     resize-none mb-3">{{ old('comment') }}</textarea>
                    <button type="submit"
                            :disabled="rating === 0"
                            :class="rating === 0
                                ? 'bg-gray-700 text-gray-500 cursor-not-allowed'
                                : 'bg-yellow-500 hover:bg-yellow-400 text-black'"
                            class="w-full py-2.5 rounded-xl font-bold text-sm transition">
                        Submit Review
                    </button>
                </form>
                @endif
            </div>
            @endif

        </div>

        {{-- RIGHT --}}
        <div class="flex flex-col gap-4">

            {{-- Payment Summary --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Payment Summary
                </div>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Account price</span>
                        <span>${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Platform fee (5%)</span>
                        <span class="text-gray-500">Included</span>
                    </div>
                    <div class="border-t border-gray-800 my-1"></div>
                    <div class="flex justify-between font-bold">
                        <span>Total Transferred</span>
                        <span class="text-green-400">
                            ${{ number_format($transaction->amount, 2) }}
                        </span>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-600">
                    Payment method: Bank Transfer
                </div>
            </div>

            {{-- Parties --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Parties
                </div>

                {{-- Buyer --}}
                <div class="flex items-center gap-3 pb-3 border-b border-gray-800">
                    <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center
                                justify-center text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($transaction->buyer->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold">
                            {{ $transaction->buyer->name }}
                            @if($transaction->buyer_id === auth()->id())
                            <span class="text-xs text-indigo-400">(You)</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">Buyer</div>
                    </div>
                </div>

                {{-- Seller --}}
                <div class="flex items-center gap-3 pt-3">
                    <div class="w-9 h-9 bg-green-600 rounded-full flex items-center
                                justify-center text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($transaction->seller->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold">
                            {{ $transaction->seller->name }}
                            @if($transaction->seller_id === auth()->id())
                            <span class="text-xs text-green-400">(You)</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">Seller</div>
                        @if($transaction->seller->rating_avg > 0)
                        <div class="text-xs text-yellow-400">
                            ⭐ {{ number_format($transaction->seller->rating_avg, 1) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Help --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 text-center">
                <div class="text-xs text-gray-500 mb-1">Need help with this order?</div>
                <div class="font-mono text-xs text-indigo-400">
                    {{ $transaction->transaction_code }}
                </div>
                <div class="text-xs text-gray-600 mt-1">
                    Use this code when contacting support
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
