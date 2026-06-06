@extends('layouts.app')
@section('title', 'Secure Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ url()->previous() }}"
           class="w-9 h-9 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center
                  justify-center text-gray-400 transition text-lg">←</a>
        <h1 class="text-xl font-bold">Secure Checkout</h1>
        <span class="ml-auto flex items-center gap-1.5 text-xs text-green-400">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            256-bit SSL Encrypted
        </span>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="col-span-2 flex flex-col gap-4">

            {{-- Item --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-800 rounded-xl flex items-center
                                justify-center text-3xl flex-shrink-0">🎮</div>
                    <div class="flex-1">
                        <div class="font-bold">{{ $transaction->listing->title }}</div>
                        <div class="text-sm text-gray-400 mt-0.5">
                            {{ $transaction->listing->game->name }}
                            @if($transaction->listing->rank) · {{ $transaction->listing->rank }} @endif
                            @if($transaction->listing->server) · {{ $transaction->listing->server }} @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $transaction->listing->platform }} · Delivery: Instant
                        </div>
                    </div>
                </div>

                {{-- Seller --}}
                <div class="mt-4 pt-4 border-t border-gray-800 flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center
                                justify-center text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($transaction->seller->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <span class="text-sm font-semibold">{{ $transaction->seller->name }}</span>
                        @if($transaction->seller->is_verified)
                        <span class="ml-2 text-xs text-sky-400 bg-sky-500/10 border border-sky-500/20
                                     px-2 py-0.5 rounded-full">✓ Verified since {{ $transaction->seller->created_at->format('Y') }}</span>
                        @endif
                        @if($transaction->seller->rating_avg > 0)
                        <span class="ml-2 text-xs text-yellow-400">
                            ⭐ {{ number_format($transaction->seller->rating_avg, 1) }}
                            ({{ $transaction->seller->total_sales }} reviews)
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <div class="text-sm font-bold mb-4">Payment Method</div>

                <div class="flex flex-col gap-3" id="payment-methods">

                    {{-- Credit Card --}}
                    <label id="method-card"
                           class="flex items-center gap-4 border-2 border-indigo-500
                                  bg-indigo-500/5 rounded-xl p-4 cursor-pointer transition">
                        <div class="w-5 h-5 rounded-full border-2 border-indigo-500
                                    flex items-center justify-center flex-shrink-0" id="dot-card">
                            <div class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-sm">Credit / Debit Card</div>
                            <div class="text-xs text-indigo-400 mt-0.5">Pay in one click next time!</div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded font-bold">VISA</span>
                            <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded font-bold">MC</span>
                            <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded font-bold">AMEX</span>
                            <span class="text-xs text-gray-500">+1</span>
                        </div>
                        <input type="radio" name="payment_method" value="card" class="hidden" checked>
                    </label>

                    {{-- Google Pay --}}
                    <label id="method-google"
                           class="flex items-center gap-4 border-2 border-gray-700
                                  bg-gray-800/50 rounded-xl p-4 cursor-pointer
                                  hover:border-gray-600 transition"
                           onclick="selectMethod('google')">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-600
                                    flex items-center justify-center flex-shrink-0" id="dot-google"></div>
                        <div class="flex-1">
                            <div class="font-semibold text-sm">Google Pay</div>
                        </div>
                        <span class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded font-bold">G Pay</span>
                        <input type="radio" name="payment_method" value="google" class="hidden">
                    </label>

                    {{-- Crypto --}}
                    <label id="method-crypto"
                           class="flex items-center gap-4 border-2 border-gray-700
                                  bg-gray-800/50 rounded-xl p-4 cursor-pointer
                                  hover:border-gray-600 transition"
                           onclick="selectMethod('crypto')">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-600
                                    flex items-center justify-center flex-shrink-0" id="dot-crypto"></div>
                        <div class="flex-1">
                            <div class="font-semibold text-sm">Cryptocurrency</div>
                        </div>
                        <div class="flex gap-1">
                            <span class="text-lg">₿</span>
                            <span class="text-lg">Ξ</span>
                        </div>
                        <input type="radio" name="payment_method" value="crypto" class="hidden">
                    </label>

                </div>
            </div>

            {{-- Trust badges --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
                    <div class="text-xl mb-1">🔒</div>
                    <div class="text-xs font-semibold">PCI DSS</div>
                    <div class="text-xs text-gray-500">Certified</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
                    <div class="text-xl mb-1">🛡️</div>
                    <div class="text-xs font-semibold">SafeKey</div>
                    <div class="text-xs text-gray-500">Protected</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
                    <div class="text-xl mb-1">✅</div>
                    <div class="text-xs font-semibold">ID Check</div>
                    <div class="text-xs text-gray-500">Verified</div>
                </div>
            </div>

        </div>

        {{-- RIGHT — Order Summary --}}
        <div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">
                <div class="text-sm font-bold mb-4">Order Summary</div>

                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-400">Order Price</span>
                    <span>${{ number_format($transaction->amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-400">Payment Fees</span>
                    <span class="text-red-400">-${{ number_format($transaction->platform_fee, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-4">
                    <span class="text-gray-400">Loyalty Points</span>
                    <span class="text-yellow-400">+ {{ round($transaction->amount * 100) }} 🏆</span>
                </div>

                <div class="border-t border-gray-800 pt-4 mb-5">
                    <div class="flex justify-between font-bold text-base">
                        <span>Total:</span>
                        <span class="text-white">${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                </div>

                {{-- CTA --}}
                <a href="{{ route('transactions.card', $transaction) }}"
                   class="block w-full text-center bg-yellow-500 hover:bg-yellow-400
                          text-gray-900 font-bold py-3.5 rounded-xl transition
                          flex items-center justify-center gap-2 text-sm">
                    🔒 Enter card details →
                </a>

                <p class="text-xs text-gray-600 text-center mt-3">
                    By placing order, you confirm you agree to our Terms & Privacy Policy.
                </p>

                <div class="mt-4 pt-4 border-t border-gray-800">
                    <div class="flex items-center gap-2 text-xs text-green-400">
                        <span>✅</span>
                        <span>Safe & Secure Payment — 100% protected by escrow</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function selectMethod(method) {
    ['card','google','crypto'].forEach(m => {
        const label = document.getElementById('method-' + m);
        const dot   = document.getElementById('dot-' + m);
        if (m === method) {
            label.classList.add('border-indigo-500','bg-indigo-500/5');
            label.classList.remove('border-gray-700','bg-gray-800/50');
            dot.innerHTML = '<div class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>';
            dot.classList.replace('border-gray-600','border-indigo-500');
        } else {
            label.classList.remove('border-indigo-500','bg-indigo-500/5');
            label.classList.add('border-gray-700','bg-gray-800/50');
            dot.innerHTML = '';
            dot.classList.replace('border-indigo-500','border-gray-600');
        }
    });
}
</script>
@endsection