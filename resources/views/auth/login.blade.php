<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — GameTradeHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Rajdhani', sans-serif; }
        .font-game { font-family: 'Orbitron', monospace; }

        .bg-grid {
            background-image:
                linear-gradient(rgba(99,102,241,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .glow-box {
            box-shadow: 0 0 0 1px rgba(99,102,241,0.3),
                        0 0 30px rgba(99,102,241,0.1),
                        inset 0 0 30px rgba(0,0,0,0.3);
        }

        .glow-btn {
            box-shadow: 0 0 20px rgba(99,102,241,0.4);
            transition: all 0.3s ease;
        }
        .glow-btn:hover {
            box-shadow: 0 0 40px rgba(99,102,241,0.7);
            transform: translateY(-1px);
        }

        .scan-line {
            background: linear-gradient(
                transparent 0%,
                rgba(99,102,241,0.03) 50%,
                transparent 100%
            );
            animation: scan 4s linear infinite;
        }
        @keyframes scan {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }

        .float-particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(99,102,241,0.6);
            border-radius: 50%;
            animation: float linear infinite;
        }
        @keyframes float {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10px) translateX(20px); opacity: 0; }
        }

        .input-game {
            background: rgba(15, 15, 30, 0.8);
            border: 1px solid rgba(99,102,241,0.3);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        .input-game:focus {
            outline: none;
            border-color: rgba(99,102,241,0.8);
            box-shadow: 0 0 15px rgba(99,102,241,0.2);
            background: rgba(15, 15, 30, 0.95);
        }
        .input-game::placeholder { color: rgba(148,163,184,0.4); }

        .corner-tl::before, .corner-tr::after,
        .corner-bl::before, .corner-br::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-color: rgba(99,102,241,0.6);
            border-style: solid;
        }
        .corner-tl { position: relative; }
        .corner-tl::before {
            top: 0; left: 0;
            border-width: 2px 0 0 2px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.6s ease forwards; }
        .fade-in-delay-1 { animation: fadeInUp 0.6s ease 0.1s forwards; opacity: 0; }
        .fade-in-delay-2 { animation: fadeInUp 0.6s ease 0.2s forwards; opacity: 0; }
        .fade-in-delay-3 { animation: fadeInUp 0.6s ease 0.3s forwards; opacity: 0; }
        .fade-in-delay-4 { animation: fadeInUp 0.6s ease 0.4s forwards; opacity: 0; }
    </style>
</head>
<body class="h-screen min-h-screen bg-gray-950 bg-grid overflow-hidden relative">

    {{-- Scan line effect --}}
    <div class="scan-line fixed inset-0 pointer-events-none z-0 h-40 w-full"></div>

    {{-- Floating particles --}}
    @for($i = 0; $i < 12; $i++)
    <div class="float-particle" style="
        left: {{ rand(0, 100) }}%;
        animation-duration: {{ rand(8, 20) }}s;
        animation-delay: {{ rand(0, 10) }}s;
        width: {{ rand(1, 3) }}px;
        height: {{ rand(1, 3) }}px;
        opacity: {{ rand(3, 7) / 10 }};
    "></div>
    @endfor

    {{-- Side decorations --}}
    <div class="fixed left-0 top-0 bottom-0 w-px bg-gradient-to-b
                from-transparent via-indigo-500/30 to-transparent"></div>
    <div class="fixed right-0 top-0 bottom-0 w-px bg-gradient-to-b
                from-transparent via-indigo-500/30 to-transparent"></div>

    {{-- Corner markers --}}
    <div class="fixed top-6 left-6 w-6 h-6 border-t-2 border-l-2
                border-indigo-500/40"></div>
    <div class="fixed top-6 right-6 w-6 h-6 border-t-2 border-r-2
                border-indigo-500/40"></div>
    <div class="fixed bottom-6 left-6 w-6 h-6 border-b-2 border-l-2
                border-indigo-500/40"></div>
    <div class="fixed bottom-6 right-6 w-6 h-6 border-b-2 border-r-2
                border-indigo-500/40"></div>

    <div class="absolute inset-0 flex items-center justify-center px-4">
        <div class="relative z-10 w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center
                            justify-center text-white font-game font-bold text-sm
                            shadow-lg shadow-indigo-500/30">G</div>
                <span class="font-game text-xl font-bold tracking-wider">
                    <span class="text-indigo-400">GAME</span>
                    <span class="text-white">TRADE</span>
                    <span class="text-indigo-400">HUB</span>
                </span>
            </div>
            <div class="text-xs text-indigo-400/60 tracking-[0.3em] uppercase">
                ⚡ The Gaming Marketplace
            </div>
        </div>

        {{-- Card --}}
        <div class="glow-box rounded-2xl p-6 relative"
             style="background: rgba(10,10,20,0.9); backdrop-filter: blur(20px);">

            {{-- Card corner accents --}}
            <div class="absolute top-0 left-0 w-4 h-4 border-t border-l
                        border-indigo-500/50 rounded-tl-2xl"></div>
            <div class="absolute top-0 right-0 w-4 h-4 border-t border-r
                        border-indigo-500/50 rounded-tr-2xl"></div>
            <div class="absolute bottom-0 left-0 w-4 h-4 border-b border-l
                        border-indigo-500/50 rounded-bl-2xl"></div>
            <div class="absolute bottom-0 right-0 w-4 h-4 border-b border-r
                        border-indigo-500/50 rounded-br-2xl"></div>

            <div class="fade-in-delay-1">
                <h2 class="font-game text-lg font-bold text-white mb-1 tracking-wider">
                    SIGN IN
                </h2>
                <p class="text-xs text-gray-500 mb-6 tracking-wide">
                    Access your account to buy & sell game accounts
                </p>
            </div>

            {{-- Session Status --}}
            @if(session('status'))
            <div class="mb-4 text-xs text-green-400 bg-green-500/10
                        border border-green-500/20 rounded-xl px-3 py-2">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div class="fade-in-delay-2">
                    <label class="block text-xs font-semibold text-gray-400
                                  tracking-widest uppercase mb-1.5">
                        Email
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus
                           placeholder="your@email.com"
                           class="input-game w-full rounded-xl px-4 py-2.5 text-sm">
                    @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="fade-in-delay-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-gray-400
                                      tracking-widest uppercase">
                            Password
                        </label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-indigo-400 hover:text-indigo-300 transition">
                            Forgot?
                        </a>
                        @endif
                    </div>
                    <input type="password" name="password"
                           required
                           placeholder="••••••••"
                           class="input-game w-full rounded-xl px-4 py-2.5 text-sm">
                    @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2 fade-in-delay-3">
                    <input type="checkbox" name="remember" id="remember"
                           checked
                           class="w-3.5 h-3.5 rounded border-gray-600
                                  bg-gray-800 text-indigo-600 cursor-pointer">
                    <label for="remember"
                           class="text-xs text-gray-400 cursor-pointer">
                        Keep me signed in
                    </label>
                </div>

                {{-- Submit --}}
                <div class="fade-in-delay-4 pt-1">
                    <button type="submit"
                            class="glow-btn w-full bg-indigo-600 hover:bg-indigo-500
                                   text-white font-game font-bold tracking-widest
                                   text-xs py-3 rounded-xl uppercase">
                        ⚡ Enter Hub
                    </button>
                </div>

            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gradient-to-r
                            from-transparent to-indigo-500/20"></div>
                <span class="text-xs text-gray-600 tracking-widest">OR</span>
                <div class="flex-1 h-px bg-gradient-to-l
                            from-transparent to-indigo-500/20"></div>
            </div>

            {{-- Register link --}}
            <div class="text-center fade-in-delay-4">
                <span class="text-xs text-gray-500">New to GameTradeHub?</span>
                <a href="{{ route('register') }}"
                   class="text-xs text-indigo-400 hover:text-indigo-300
                          font-semibold ml-1 transition">
                    Create Account →
                </a>
            </div>

        </div>

        {{-- Bottom text --}}
        <div class="text-center mt-5 fade-in-delay-4">
            <span class="text-xs text-gray-700 tracking-wider">
                🔒 All transactions escrow-protected
            </span>
        </div>

    </div>
</div>

</body>
</html>
