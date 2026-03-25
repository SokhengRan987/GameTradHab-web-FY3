@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">⚙️ My Profile</h1>
            <p class="text-gray-400 text-sm mt-1">
                Complete your profile to build trust with buyers
            </p>
        </div>
        @if(auth()->user()->profile_completed)
        <span class="bg-green-500/10 border border-green-500/25 text-green-400
                     px-3 py-1.5 rounded-full text-xs font-bold">
            ✅ Profile Complete
        </span>
        @else
        <span class="bg-yellow-500/10 border border-yellow-500/25 text-yellow-400
                     px-3 py-1.5 rounded-full text-xs font-bold">
            ⚠️ Profile Incomplete
        </span>
        @endif
    </div>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        {{-- BLOCK 1: Personal Information --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                👤 Personal Information
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Display Name *
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                    @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Username *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2
                                     text-gray-500 text-sm">@</span>
                        <input type="text" name="username"
                               value="{{ old('username', $user->username) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      pl-7 pr-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                    @error('username')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Full Name
                        <span class="text-gray-600 font-normal">(as per ID)</span>
                    </label>
                    <input type="text" name="full_name"
                           value="{{ old('full_name', $user->full_name) }}"
                           placeholder="Your legal full name"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Email Address
                    </label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-600 mt-1">Email cannot be changed</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Date of Birth
                    </label>
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                           max="{{ now()->subYears(13)->format('Y-m-d') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                    @error('date_of_birth')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Country
                    </label>
                    <x-country-select
                        name="country"
                        :selected="old('country', $user->country ?? '')" />
                </div>
            </div>
        </div>

        {{-- BLOCK 2: Contact Information --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                📞 Contact Information
            </div>
            <p class="text-xs text-gray-500 mb-4">
                Phone number is only shown to buyers after a completed purchase.
            </p>

            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                    Phone Number
                </label>
                <div class="flex gap-2">
                    <x-country-select
                        name="country"
                        :selected="old('country', $user->country ?? '')" />
                    <input type="text" name="phone_number"
                           value="{{ old('phone_number', $user->phone_number) }}"
                           placeholder="123456789"
                           class="flex-1 bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                </div>
            </div>
        </div>

        {{-- BLOCK 3: Instant Messenger --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                💬 Instant Messenger
            </div>
            <p class="text-xs text-gray-500 mb-4">
                Add at least one so buyers can contact you directly.
            </p>

            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-sky-500/15 rounded-xl flex items-center
                                justify-center text-lg flex-shrink-0">✈️</div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-400 mb-1">
                            Telegram
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2
                                         text-gray-500 text-sm">t.me/</span>
                            <input type="text" name="telegram"
                                   value="{{ old('telegram', $user->telegram) }}"
                                   placeholder="yourusername"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                          pl-14 pr-3 py-2 text-sm text-white
                                          focus:outline-none focus:border-sky-500">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-500/15 rounded-xl flex items-center
                                justify-center text-lg flex-shrink-0">📱</div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-400 mb-1">
                            WhatsApp
                        </label>
                        <input type="text" name="whatsapp"
                               value="{{ old('whatsapp', $user->whatsapp) }}"
                               placeholder="60123456789"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-3 py-2 text-sm text-white
                                      focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-500/15 rounded-xl flex items-center
                                justify-center text-lg flex-shrink-0">🎮</div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-400 mb-1">
                            Discord
                        </label>
                        <input type="text" name="discord"
                               value="{{ old('discord', $user->discord) }}"
                               placeholder="username or username#0000"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-3 py-2 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-600/15 rounded-xl flex items-center
                                justify-center text-lg flex-shrink-0">💚</div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-400 mb-1">
                            Line ID
                        </label>
                        <input type="text" name="line_id"
                               value="{{ old('line_id', $user->line_id) }}"
                               placeholder="your_line_id"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-3 py-2 text-sm text-white
                                      focus:outline-none focus:border-green-600">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end mb-4">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white
                           px-6 py-2.5 rounded-xl font-semibold text-sm transition">
                Save Profile →
            </button>
        </div>

    </form>

    {{-- Change Password --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
            🔒 Change Password
        </div>
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PATCH')
            <div class="flex flex-col gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                        Current Password
                    </label>
                    <input type="password" name="current_password"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                  px-3 py-2.5 text-sm text-white
                                  focus:outline-none focus:border-indigo-500">
                    @error('current_password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                            New Password
                        </label>
                        <input type="password" name="password"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                        @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">
                            Confirm Password
                        </label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                      px-3 py-2.5 text-sm text-white
                                      focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-gray-700 hover:bg-gray-600 text-white
                                   px-5 py-2 rounded-xl text-sm font-semibold transition">
                        Change Password
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-red-500/5 border border-red-500/20 rounded-xl p-5"
         x-data="{ show: false }">
        <div class="text-xs font-bold text-red-400 uppercase tracking-wider mb-3">
            ⚠️ Danger Zone
        </div>
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold">Delete Account</div>
                <div class="text-xs text-gray-500">
                    Permanently delete your account and all data
                </div>
            </div>
            <button type="button" @click="show = !show"
                    class="bg-red-600/20 hover:bg-red-600/40 text-red-400
                           border border-red-500/30 px-4 py-2
                           rounded-xl text-sm font-bold transition">
                Delete Account
            </button>
        </div>
        <div x-show="show" class="mt-4 pt-4 border-t border-red-500/20">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <input type="password" name="password"
                       placeholder="Enter your password to confirm"
                       class="w-full bg-gray-800 border border-red-500/30 rounded-xl
                              px-3 py-2 text-sm text-white mb-3
                              focus:outline-none focus:border-red-500">
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-500 text-white
                               py-2 rounded-xl text-sm font-bold transition">
                    Yes, Delete My Account Permanently
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
