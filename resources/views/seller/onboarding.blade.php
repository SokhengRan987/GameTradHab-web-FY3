@extends('layouts.app')

@section('title', 'Seller Onboarding')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="text-5xl mb-3">🛒</div>
        <h1 class="text-2xl font-bold mb-2">Become a Seller</h1>
        <p class="text-gray-400 text-sm">
            Complete your seller profile once to start listing accounts.
            This helps build trust with buyers.
        </p>
    </div>

    {{-- Progress Steps --}}
    <div class="flex items-center justify-center gap-2 mb-8">
        @foreach(['Personal Info', 'Contact', 'Selling Info', 'Rules'] as $i => $step)
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center
                        text-xs font-bold bg-indigo-600 text-white">
                {{ $i + 1 }}
            </div>
            <span class="text-xs text-gray-400 hidden sm:block">{{ $step }}</span>
        </div>
        @if(!$loop->last)
        <div class="w-8 h-px bg-gray-700"></div>
        @endif
        @endforeach
    </div>

    <form method="POST" action="{{ route('seller.onboarding.store') }}">
        @csrf

        {{-- BLOCK 1: Personal Information --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500
                        uppercase tracking-wider mb-4">
                <span class="w-5 h-5 bg-indigo-600 rounded-full flex items-center
                             justify-center text-white text-xs">1</span>
                Personal Information
            </div>

            {{-- Full Name --}}
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Full Name *
                    <span class="text-gray-600 font-normal">(as per ID)</span>
                </label>
                <input type="text" name="full_name"
                       value="{{ old('full_name', auth()->user()->full_name) }}"
                       placeholder="Your legal full name"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl
                              px-3 py-2.5 text-sm text-white
                              focus:outline-none focus:border-indigo-500">
                @error('full_name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Country --}}
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Country *
                </label>
                <x-country-select
                    name="country"
                    :selected="old('country', auth()->user()->country ?? '')" />
                @error('country')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Email Address *
                </label>
                <input type="email" name="email"
                       value="{{ old('email', auth()->user()->email) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl
                              px-3 py-2.5 text-sm text-white
                              focus:outline-none focus:border-indigo-500">
                @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Instant Messenger --}}
            <div x-data="{ type: '{{ old('messenger_type') }}' }">
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Instant Messenger
                    <span class="text-gray-600 font-normal">(optional — select one)</span>
                </label>

                {{-- Messenger selector --}}
                <div class="grid grid-cols-4 gap-2 mb-3">
                    @foreach([
                        'telegram'  => ['✈️', 'Telegram',  'sky'],
                        'whatsapp'  => ['📱', 'WhatsApp',  'green'],
                        'discord'   => ['🎮', 'Discord',   'indigo'],
                        'line'      => ['💚', 'Line',      'emerald'],
                    ] as $value => [$icon, $label, $color])
                    <label class="cursor-pointer">
                        <input type="radio" name="messenger_type"
                               value="{{ $value }}"
                               x-model="type"
                               class="sr-only">
                        <div :class="type === '{{ $value }}'
                                     ? 'border-{{ $color }}-500 bg-{{ $color }}-500/10 text-{{ $color }}-400'
                                     : 'border-gray-700 bg-gray-800 text-gray-400'"
                             class="border-2 rounded-xl p-2.5 text-center transition cursor-pointer">
                            <div class="text-lg mb-0.5">{{ $icon }}</div>
                            <div class="text-xs font-semibold">{{ $label }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Link input --}}
                <div x-show="type !== ''" x-cloak>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2
                                     text-gray-500 text-xs"
                              x-text="{
                                  telegram: 't.me/',
                                  whatsapp: 'wa.me/',
                                  discord:  'discord.gg/',
                                  line:     'line.me/ti/p/'
                              }[type] || ''">
                        </span>
                        <input type="text" name="messenger_link"
                               value="{{ old('messenger_link') }}"
                               placeholder="your username or link"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      pl-20 pr-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                    <p class="text-xs text-gray-600 mt-1">
                        Paste your username — works worldwide
                    </p>
                </div>
            </div>
        </div>

        {{-- BLOCK 2: Contact Information --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500
                        uppercase tracking-wider mb-4">
                <span class="w-5 h-5 bg-indigo-600 rounded-full flex items-center
                             justify-center text-white text-xs">2</span>
                Contact Information
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Phone Number *
                </label>
                <div class="flex gap-2">
                    <x-country-select
                        name="country"
                        :selected="old('country', auth()->user()->country ?? '')" />
                    <input type="text" name="phone_number"
                           value="{{ old('phone_number', auth()->user()->phone_number) }}"
                           placeholder="123456789"
                           class="flex-1 bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                </div>
                @error('phone_number')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-600 mt-1">
                    Only shown to buyers after a completed purchase
                </p>
            </div>
        </div>

        {{-- BLOCK 3: Selling Information --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500
                        uppercase tracking-wider mb-4">
                <span class="w-5 h-5 bg-indigo-600 rounded-full flex items-center
                             justify-center text-white text-xs">3</span>
                Selling Information
            </div>

            {{-- What games --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-white mb-1">
                    What games and products / services will you be selling? *
                </label>
                <p class="text-xs text-gray-500 mb-2">
                    List the games or services you plan to sell on GameTradeHub.
                </p>
                <textarea name="seller_games" rows="3"
                          placeholder="e.g. Mobile Legends accounts, Valorant accounts, PUBG Mobile top-up..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                 px-3 py-2.5 text-sm text-white
                                 focus:outline-none focus:border-indigo-500 resize-none">{{ old('seller_games', auth()->user()->seller_games) }}</textarea>
                @error('seller_games')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Stock source --}}
            <div class="mb-5"
                 x-data="{ source: '{{ old('seller_stock_source', auth()->user()->seller_stock_source) }}' }">
                <label class="block text-sm font-semibold text-white mb-1">
                    How did you get your stock? *
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Be honest — this helps buyers trust you more.
                </p>

                <div class="flex flex-col gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="seller_stock_source"
                               value="self_farmed"
                               x-model="source"
                               class="sr-only">
                        <div :class="source === 'self_farmed'
                                     ? 'border-green-500 bg-green-500/5'
                                     : 'border-gray-700 bg-gray-800'"
                             class="border-2 rounded-xl p-4 transition cursor-pointer">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🌾</span>
                                <div>
                                    <div class="font-semibold text-sm">
                                        I farm my own items
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        I personally earned or grinded these accounts/items myself
                                    </div>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center
                                            justify-center flex-shrink-0"
                                     :class="source === 'self_farmed'
                                             ? 'border-green-500 bg-green-500'
                                             : 'border-gray-600'">
                                    <div x-show="source === 'self_farmed'"
                                         class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="seller_stock_source"
                               value="resell"
                               x-model="source"
                               class="sr-only">
                        <div :class="source === 'resell'
                                     ? 'border-blue-500 bg-blue-500/5'
                                     : 'border-gray-700 bg-gray-800'"
                             class="border-2 rounded-xl p-4 transition cursor-pointer">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🔄</span>
                                <div>
                                    <div class="font-semibold text-sm">
                                        I buy from elsewhere and resell here
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        I purchase accounts/items from other sources and resell them
                                    </div>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center
                                            justify-center flex-shrink-0"
                                     :class="source === 'resell'
                                             ? 'border-blue-500 bg-blue-500'
                                             : 'border-gray-600'">
                                    <div x-show="source === 'resell'"
                                         class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('seller_stock_source')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sells elsewhere --}}
            <div x-data="{ elsewhere: '{{ old('seller_sells_elsewhere') }}' }">
                <label class="block text-sm font-semibold text-white mb-1">
                    Are you currently selling elsewhere? *
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Let buyers know if you sell on other platforms too.
                </p>

                <div class="flex gap-3 mb-3">
                    <label class="cursor-pointer flex-1">
                        <input type="radio" name="seller_sells_elsewhere"
                               value="1" x-model="elsewhere" class="sr-only">
                        <div :class="elsewhere === '1'
                                     ? 'border-indigo-500 bg-indigo-500/10 text-indigo-400'
                                     : 'border-gray-700 bg-gray-800 text-gray-400'"
                             class="border-2 rounded-xl p-3 text-center transition">
                            <div class="text-xl mb-1">✅</div>
                            <div class="text-sm font-bold">Yes</div>
                            <div class="text-xs opacity-70">I sell on other platforms</div>
                        </div>
                    </label>
                    <label class="cursor-pointer flex-1">
                        <input type="radio" name="seller_sells_elsewhere"
                               value="0" x-model="elsewhere" class="sr-only">
                        <div :class="elsewhere === '0'
                                     ? 'border-indigo-500 bg-indigo-500/10 text-indigo-400'
                                     : 'border-gray-700 bg-gray-800 text-gray-400'"
                             class="border-2 rounded-xl p-3 text-center transition">
                            <div class="text-xl mb-1">❌</div>
                            <div class="text-sm font-bold">No</div>
                            <div class="text-xs opacity-70">GameTradeHub only</div>
                        </div>
                    </label>
                </div>

                {{-- If yes — which platforms --}}
                <div x-show="elsewhere === '1'" x-cloak>
                    <input type="text" name="seller_other_platforms"
                           value="{{ old('seller_other_platforms') }}"
                           placeholder="e.g. Shopee, Carousell, Facebook, Discord servers..."
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                    <p class="text-xs text-gray-600 mt-1">Optional — list the platforms</p>
                </div>

                @error('seller_sells_elsewhere')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- BLOCK 4: Rules --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500
                        uppercase tracking-wider mb-4">
                <span class="w-5 h-5 bg-indigo-600 rounded-full flex items-center
                             justify-center text-white text-xs">4</span>
                Seller Rules
            </div>

            <div class="bg-gray-800 rounded-xl p-4 mb-4 text-sm text-gray-300
                        flex flex-col gap-2">
                <div class="flex items-start gap-2">
                    <span class="text-green-400 flex-shrink-0">✅</span>
                    <span>Only sell accounts you legitimately own</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-green-400 flex-shrink-0">✅</span>
                    <span>Provide accurate screenshots as proof</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-green-400 flex-shrink-0">✅</span>
                    <span>Deliver account credentials promptly after payment</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-red-400 flex-shrink-0">❌</span>
                    <span>Do not sell hacked, stolen, or fake accounts</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-red-400 flex-shrink-0">❌</span>
                    <span>Do not scam or mislead buyers</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-red-400 flex-shrink-0">❌</span>
                    <span>Do not list the same account multiple times</span>
                </div>
            </div>

            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="agree_rules" value="1"
                       class="mt-0.5 w-4 h-4 rounded border-gray-600
                              bg-gray-800 text-indigo-600">
                <span class="text-sm text-gray-300">
                    I have read and agree to the
                    <span class="text-indigo-400">Seller Rules</span>
                    and
                    <span class="text-indigo-400">Terms of Service</span>.
                    I understand violations may result in account suspension.
                </span>
            </label>
            @error('agree_rules')
            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white
                       py-3 rounded-xl font-bold text-sm transition">
            Complete Onboarding & Start Selling →
        </button>

    </form>
</div>
@endsection
