@extends('layouts.app')
@section('title', 'Complete Payment')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment — GameTradeHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Rajdhani', sans-serif; }
        .font-game { font-family: 'Orbitron', monospace; }
        .bg-grid {
            background-image:
                linear-gradient(rgba(99,102,241,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .copy-btn { transition: all 0.2s; }
        .copy-btn:active { transform: scale(0.95); }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in   { animation: fadeInUp 0.5s ease forwards; }
        .fade-in-2 { animation: fadeInUp 0.5s ease 0.1s forwards; opacity: 0; }
        .fade-in-3 { animation: fadeInUp 0.5s ease 0.2s forwards; opacity: 0; }
        .fade-in-4 { animation: fadeInUp 0.5s ease 0.3s forwards; opacity: 0; }
        @keyframes pulse-border {
            0%, 100% { border-color: rgba(234,179,8,0.3); }
            50%       { border-color: rgba(234,179,8,0.7); }
        }
        .pulse-border { animation: pulse-border 2s ease infinite; }
    </style>
</head>
<body class="min-h-screen bg-gray-950 bg-grid text-white">

    {{-- Top nav --}}
    <div class="border-b border-gray-800/50 bg-gray-950/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}"
               class="font-game text-sm font-bold tracking-wider">
                <span class="text-indigo-400">GAME</span>
                <span class="text-white">TRADE</span>
                <span class="text-indigo-400">HUB</span>
            </a>
            <div class="flex items-center gap-2 text-xs text-green-400">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Escrow Protected
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">

        {{-- Page Header --}}
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center gap-2 bg-yellow-500/10 border
                        border-yellow-500/20 rounded-full px-4 py-1.5 mb-3">
                <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full animate-pulse"></span>
                <span class="text-xs text-yellow-400 tracking-wider font-semibold">
                    STEP 2 OF 3 — COMPLETE PAYMENT
                </span>
            </div>
            <h1 class="font-game text-2xl font-bold text-white mb-2 tracking-wider">
                BANK TRANSFER
            </h1>
            <p class="text-gray-400 text-sm">
                Transfer the exact amount below to secure your order
            </p>
        </div>

        <div class="grid grid-cols-3 gap-5">

            {{-- LEFT — Main content --}}
            <div class="col-span-2 flex flex-col gap-4">

                {{-- Step progress --}}
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 fade-in">
                    <div class="flex items-center gap-0">
                        @foreach([
                            ['1', 'Order Created', true],
                            ['2', 'Transfer Funds', true],
                            ['3', 'Admin Verifies', false],
                            ['4', 'Account Delivered', false],
                        ] as [$num, $label, $active])
                        <div class="flex items-center flex-1 {{ !$loop->last ? '' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center
                                            text-xs font-bold flex-shrink-0
                                            {{ $active ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-500 border border-gray-700' }}">
                                    {{ $num }}
                                </div>
                                <div class="text-xs mt-1 {{ $active ? 'text-white font-semibold' : 'text-gray-600' }}
                                            whitespace-nowrap">
                                    {{ $label }}
                                </div>
                            </div>
                            @if(!$loop->last)
                            <div class="flex-1 h-px mx-2 mb-4
                                        {{ $active ? 'bg-indigo-600/50' : 'bg-gray-800' }}"></div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Bank Details --}}
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 fade-in-2"
                     style="background: linear-gradient(135deg, rgba(99,102,241,0.05) 0%, rgba(10,10,20,0.9) 100%)">

                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-indigo-600/20 border border-indigo-500/30
                                    rounded-xl flex items-center justify-center text-base">
                            🏦
                        </div>
                        <div>
                            <div class="font-game text-xs font-bold text-indigo-400 tracking-wider">
                                BANK TRANSFER DETAILS
                            </div>
                            <div class="text-xs text-gray-500">
                                Transfer to this account
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">

                        {{-- Bank fields --}}

                        @foreach([
                            ['🏦', 'Bank Name',       $transaction->bank_name ?? 'ABA Bank',           'bank_name'],
                            ['👤', 'Account Name',    $transaction->bank_account_name ?? 'GameTradeHub',   'account_name'],
                            ['💳', 'Account Number',  $transaction->bank_account_number ?? '123456789',   'account_number'],
                            ['🌐', 'Swift / BIC',     $transaction->bank_swift ?? 'ABAAXXXX',          'swift'],
                        ] as [$icon, $label, $value, $id])

                        <div class="flex items-center justify-between bg-gray-800/60
                                    border border-gray-700/50 rounded-xl px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="text-base">{{ $icon }}</span>
                                <div>
                                    <div class="text-xs text-gray-500">{{ $label }}</div>
                                    <div class="font-semibold text-sm text-white">
                                        {{ $value }}
                                    </div>
                                </div>
                            </div>
                            <button onclick="copyText('{{ $value }}', this)"
                                    class="copy-btn text-xs text-indigo-400 hover:text-white
                                           bg-indigo-500/10 hover:bg-indigo-500/30 border
                                           border-indigo-500/20 px-2.5 py-1 rounded-lg transition">
                                📋 Copy
                            </button>
                        </div>
                        @endforeach

                        {{-- Reference Code — highlighted --}}
                        <div class="pulse-border border-2 rounded-xl px-4 py-3 mt-1"
                             style="background: rgba(234,179,8,0.05)">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="text-xs text-yellow-400 font-bold
                                                     uppercase tracking-wider">
                                            ⚠️ Payment Reference
                                        </span>
                                        <span class="text-xs bg-red-500 text-white
                                                     px-1.5 py-0.5 rounded font-bold">
                                            REQUIRED
                                        </span>
                                    </div>
                                    <div class="font-game font-bold text-yellow-400 text-xl
                                                tracking-widest">
                                        {{ $transaction->transaction_code }}
                                    </div>
                                </div>
                                <button onclick="copyText('{{ $transaction->transaction_code }}', this)"
                                        class="copy-btn text-xs text-yellow-400 hover:text-black
                                               bg-yellow-500/20 hover:bg-yellow-400 border
                                               border-yellow-500/30 px-3 py-2 rounded-xl
                                               font-bold transition">
                                    📋 Copy
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                ⚠️ You MUST include this reference when making the transfer.
                                Without it we cannot match your payment and your order will be delayed.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Instructions --}}
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 fade-in-3">
                    <div class="font-game text-xs font-bold text-gray-400
                                tracking-widest uppercase mb-4">
                        📋 HOW TO COMPLETE
                    </div>
                    <div class="flex flex-col gap-3">
                        @foreach([
                            ['Open your bank app or visit your bank branch'],
                            ['Transfer exactly <span class="text-green-400 font-bold">$' . number_format($transaction->amount, 2) . '</span> to the account above'],
                            ['Use <span class="font-game text-yellow-400 font-bold">' . $transaction->transaction_code . '</span> as payment reference/description'],
                            ['Come back here and click <span class="text-green-400 font-bold">"I Have Paid"</span> below'],
                            ['Admin verifies your payment within <span class="text-white font-bold">1–3 hours</span>'],
                            ['Once verified, contact the seller to receive account details'],
                        ] as $i => [$step])
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-indigo-600/20 border border-indigo-500/30
                                        rounded-full flex items-center justify-center
                                        text-xs text-indigo-400 font-bold flex-shrink-0 mt-0.5">
                                {{ $i + 1 }}
                            </div>
                            <span class="text-sm text-gray-300 leading-relaxed">
                                {!! $step !!}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- I Have Paid Form --}}
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 fade-in-4">
                    <div class="font-game text-xs font-bold text-gray-400
                                tracking-widest uppercase mb-4">
                        ✅ CONFIRM YOUR PAYMENT
                    </div>

                    <form method="POST" action="{{ route('transactions.paid', $transaction->id) }}">
                        @csrf

                        {{-- Optional note --}}
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                                Payment Note
                                <span class="text-gray-600 font-normal ml-1">(optional)</span>
                            </label>
                            <input type="text" name="payment_note"
                                   placeholder="e.g. Paid via Maybank2u at 3:15 PM, 20 March"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                          px-3 py-2.5 text-sm text-white placeholder-gray-600
                                          focus:outline-none focus:border-green-500 transition">
                        </div>

                        {{-- Checkbox confirmation --}}
                        <label class="flex items-start gap-3 cursor-pointer mb-4">
                            <input type="checkbox" id="confirmCheck"
                                   class="mt-0.5 w-4 h-4 rounded border-gray-600
                                          bg-gray-800 text-green-600 cursor-pointer"
                                   onchange="toggleSubmit()">
                            <span class="text-sm text-gray-300">
                                I confirm I have transferred
                                <strong class="text-green-400">
                                    ${{ number_format($transaction->amount, 2) }}
                                </strong>
                                with reference
                                <strong class="text-yellow-400 font-game text-xs">
                                    {{ $transaction->transaction_code }}
                                </strong>
                            </span>
                        </label>

                        <button type="submit"
                                id="submitBtn"
                                disabled
                                class="w-full font-game font-bold text-xs tracking-wider
                                       py-4 rounded-xl transition disabled:opacity-40
                                       disabled:cursor-not-allowed
                                       bg-green-600 hover:bg-green-500 text-white"
                                style="box-shadow: 0 0 20px rgba(34,197,94,0.2)">
                            ✅ I HAVE PAID — NOTIFY ADMIN
                        </button>

                    </form>

                    {{-- Cancel --}}
                    <div class="text-center mt-3">
                        <form method="POST"
                              action="{{ route('transactions.cancel', $transaction->id) }}"
                              onsubmit="return confirm('Cancel this order? The listing will be available again.')">
                            @csrf
                            <button type="submit"
                                    class="text-xs text-gray-600 hover:text-red-400 transition">
                                ✕ Cancel Order
                            </button>
                        </form>
                    </div>

                </div>

            </div>

            {{-- RIGHT — Order Summary --}}
            <div>

                {{-- Order card --}}
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 mb-3 sticky top-20">

                    <div class="font-game text-xs font-bold text-gray-500
                                tracking-widest uppercase mb-3">
                        Order Summary
                    </div>

                    {{-- Item --}}
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-800">
                        <div class="w-12 h-10 bg-gray-800 rounded-xl flex items-center
                                    justify-center text-xl flex-shrink-0">🎮</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm text-white truncate">
                                {{ $transaction->listing->title }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $transaction->listing->game->name }}
                            </div>
                        </div>
                    </div>

                    {{-- Seller --}}
                    <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-800">
                        <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center
                                    justify-center text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($transaction->seller->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-xs font-semibold">
                                {{ $transaction->seller->name }}
                            </div>
                            @if($transaction->seller->rating_avg > 0)
                            <div class="text-xs text-yellow-400">
                                ⭐ {{ number_format($transaction->seller->rating_avg, 1) }}
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Price breakdown --}}
                    <div class="flex flex-col gap-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Account price</span>
                            <span>${{ number_format($transaction->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Service fee</span>
                            <span class="text-gray-500">Included</span>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">You transfer</span>
                            <span class="font-game font-bold text-xl text-green-400">
                                ${{ number_format($transaction->amount, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Timer --}}
                    <div class="mt-4 bg-orange-500/5 border border-orange-500/20
                                rounded-xl px-3 py-2.5 text-center">
                        <div class="text-xs text-gray-500 mb-1">Order expires in</div>
                        <div class="font-game font-bold text-orange-400 text-lg"
                             id="countdown">
                            24:00:00
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            Complete payment before timer ends
                        </div>
                    </div>

                    {{-- Trust --}}
                    <div class="mt-4 flex flex-col gap-2 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <span class="text-green-400">🔒</span>
                            <span>Escrow protected — money held safely</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-indigo-400">⚖️</span>
                            <span>Dispute resolution if issues arise</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400">⚡</span>
                            <span>Verified within 1–3 business hours</span>
                        </div>
                    </div>

                </div>

                {{-- Need help --}}
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 text-center">
                    <div class="text-xs text-gray-500 mb-2">Need help?</div>
                    <div class="text-xs text-gray-400">
                        Contact support with your order code:
                    </div>
                    <div class="font-game text-xs text-indigo-400 mt-1">
                        {{ $transaction->transaction_code }}
                    </div>
                </div>

            </div>

        </div>

    </div>
    @yield('scripts')
</body>
</html>

@push('scripts')
<script>
// Copy to clipboard
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '✅ Copied!';
        btn.classList.add('bg-green-500/20', 'border-green-500/30', 'text-green-400');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.remove('bg-green-500/20', 'border-green-500/30', 'text-green-400');
        }, 2000);
    });
}

// function copyText(text, btn) {
//     navigator.clipboard.writeText(text).then(() => {
//         const orig = btn.innerHTML;
//         btn.innerHTML = '✅ Copied!';
//         btn.classList.add('bg-green-500/20', 'border-green-500/30', 'text-green-400');
//         setTimeout(() => {
//             btn.innerHTML = orig;
//             btn.classList.remove('bg-green-500/20', 'border-green-500/30', 'text-green-400');
//         }, 2000);
//     });
// }


// Enable submit only when checkbox checked
function toggleSubmit() {
    const check  = document.getElementById('confirmCheck');
    const btn    = document.getElementById('submitBtn');
    btn.disabled = !check.checked;
    if (check.checked) {
        btn.style.boxShadow = '0 0 25px rgba(34,197,94,0.4)';
    } else {
        btn.style.boxShadow = '0 0 20px rgba(34,197,94,0.2)';
    }
}

// 24hr countdown
const created = new Date('{{ $transaction->created_at->toISOString() }}');
const expires = new Date(created.getTime() + 24 * 60 * 60 * 1000);

function tick() {
    const diff = expires - new Date();
    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'EXPIRED';
        return;
    }
    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    document.getElementById('countdown').textContent =
        `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;

    // Turn red when under 1 hour
    if (h < 1) {
        document.getElementById('countdown').classList.add('text-red-400');
        document.getElementById('countdown').classList.remove('text-orange-400');
    }
}
tick();
setInterval(tick, 1000);
</script>
@endpush
