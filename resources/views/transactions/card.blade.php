@extends('layouts.app')
@section('title', 'Enter Card Details')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('transactions.payment', $transaction) }}"
           class="w-9 h-9 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center
                  justify-center text-gray-400 transition text-lg">←</a>
        <h1 class="text-xl font-bold">Enter Card Details</h1>
        <span class="ml-auto flex items-center gap-1.5 text-xs text-green-400">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            256-bit SSL Encrypted
        </span>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- LEFT — Card Form --}}
        <div class="col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">

                @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/25 text-red-400
                            rounded-xl p-3 mb-5 text-sm">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('transactions.pay', $transaction) }}"
                      id="card-form">
                    @csrf

                    {{-- Credit card section --}}
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                        Credit Card
                    </div>

                    <div class="mb-4">
                        <input type="text" name="card_number" id="card-number"
                               placeholder="Card number" maxlength="19"
                               value="{{ old('card_number') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <input type="text" name="expiry" id="expiry"
                               placeholder="MM/YY" maxlength="5"
                               value="{{ old('expiry') }}"
                               class="bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                        <input type="text" name="cvv"
                               placeholder="CVV" maxlength="4"
                               class="bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                    </div>

                    {{-- Billing details --}}
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                        Billing Details
                    </div>

                    <div class="mb-3">
                        <input type="text" name="card_name"
                               placeholder="Name on card"
                               value="{{ old('card_name', auth()->user()->name) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="address"
                               placeholder="Address"
                               value="{{ old('address') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <input type="text" name="city"
                               placeholder="City"
                               value="{{ old('city') }}"
                               class="bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                        <input type="text" name="zip"
                               placeholder="Zip Code"
                               value="{{ old('zip') }}"
                               class="bg-gray-800 border border-gray-700 rounded-xl
                                      px-4 py-3 text-sm text-white placeholder-gray-600
                                      focus:outline-none focus:border-indigo-500 transition"
                               required>
                    </div>

                    <div class="mb-6">
                        <select name="country"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                       px-4 py-3 text-sm text-white focus:outline-none
                                       focus:border-indigo-500 transition">
                            <option value="">Country</option>
                            <option value="KH" {{ old('country')=='KH'?'selected':'' }}>Cambodia</option>
                            <option value="TH" {{ old('country')=='TH'?'selected':'' }}>Thailand</option>
                            <option value="VN" {{ old('country')=='VN'?'selected':'' }}>Vietnam</option>
                            <option value="SG" {{ old('country')=='SG'?'selected':'' }}>Singapore</option>
                            <option value="MY" {{ old('country')=='MY'?'selected':'' }}>Malaysia</option>
                            <option value="PH" {{ old('country')=='PH'?'selected':'' }}>Philippines</option>
                            <option value="ID" {{ old('country')=='ID'?'selected':'' }}>Indonesia</option>
                            <option value="US" {{ old('country')=='US'?'selected':'' }}>United States</option>
                            <option value="GB" {{ old('country')=='GB'?'selected':'' }}>United Kingdom</option>
                        </select>
                    </div>

                    {{-- Save card --}}
                    <div class="flex items-center justify-between bg-gray-800 border
                                border-gray-700 rounded-xl p-4 mb-6 cursor-pointer"
                         onclick="toggleSave()">
                        <div class="flex items-center gap-3">
                            <div id="save-box"
                                 class="w-5 h-5 border-2 border-gray-600 rounded
                                        flex items-center justify-center flex-shrink-0">
                            </div>
                            <div>
                                <div class="text-sm font-semibold">Save this card</div>
                                <div class="text-xs text-gray-500">Securely stored · Remove anytime</div>
                            </div>
                        </div>
                        <span class="text-xs text-indigo-400">Pay in one click next time!</span>
                        <input type="checkbox" name="save_card" id="save-check" class="hidden">
                    </div>

                    {{-- Security logos --}}
                    <div class="flex items-center gap-3 flex-wrap pt-4 border-t border-gray-800">
                        <span class="text-xs text-gray-600 bg-gray-800 px-2 py-1 rounded">PCI DSS</span>
                        <span class="text-xs text-gray-600 bg-gray-800 px-2 py-1 rounded">ID Check</span>
                        <span class="text-xs text-gray-600 bg-gray-800 px-2 py-1 rounded">SafeKey</span>
                        <span class="text-xs text-gray-600 bg-gray-800 px-2 py-1 rounded">ProtectBuy</span>
                        <span class="text-xs text-gray-600 bg-gray-800 px-2 py-1 rounded">🔒 VISA Secure</span>
                    </div>

                </form>
            </div>
        </div>

        {{-- RIGHT — Summary --}}
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
                        <span>${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                </div>

                <button onclick="submitPayment()"
                        class="w-full bg-yellow-500 hover:bg-yellow-400 text-gray-900
                               font-bold py-3.5 rounded-xl transition flex items-center
                               justify-center gap-2 text-sm" id="pay-btn">
                    🔒 Pay ${{ number_format($transaction->amount, 2) }} →
                </button>

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
document.getElementById('card-number').addEventListener('input', function(e) {
    let val = e.target.value.replace(/\D/g,'').substring(0,16);
    e.target.value = val.replace(/(.{4})/g,'$1 ').trim();
});

document.getElementById('expiry').addEventListener('input', function(e) {
    let val = e.target.value.replace(/\D/g,'').substring(0,4);
    if (val.length >= 2) val = val.substring(0,2) + '/' + val.substring(2);
    e.target.value = val;
});

function toggleSave() {
    const box   = document.getElementById('save-box');
    const check = document.getElementById('save-check');
    check.checked = !check.checked;
    box.innerHTML = check.checked ? '<span class="text-indigo-400 text-xs font-bold">✓</span>' : '';
    box.classList.toggle('border-indigo-500', check.checked);
    box.classList.toggle('border-gray-600', !check.checked);
}

function submitPayment() {
    const btn = document.getElementById('pay-btn');
    btn.textContent = '⏳ Processing...';
    btn.disabled = true;
    document.getElementById('card-form').submit();
}
</script>
@endsection