@extends('layouts.app')
@section('title', 'Report Listing')

@section('content')
<div class="max-w-lg mx-auto px-4 py-8">

    <h1 class="text-xl font-bold mb-1">🚩 Report Listing</h1>
    <p class="text-gray-400 text-sm mb-6">
        Help keep GameTradeHub safe. Reports are reviewed within 24 hours.
    </p>

    {{-- Listing Preview --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-5 flex gap-3">
        <div class="w-12 h-10 bg-gray-800 rounded-lg flex items-center
                    justify-center text-xl flex-shrink-0">🎮</div>
        <div>
            <div class="font-semibold text-sm">{{ $listing->title }}</div>
            <div class="text-xs text-gray-400">
                {{ $listing->game->name }} · ${{ number_format($listing->price, 2) }}
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('listings.report.store', $listing) }}">
        @csrf

        {{-- Reason --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-4">
            <label class="block text-xs font-bold text-gray-500
                          uppercase tracking-wider mb-3">
                Reason for Report *
            </label>
            <div class="flex flex-col gap-2">
                @foreach([
                    'scam'             => ['🚨', 'Scam / Fraud',         'Seller is trying to scam buyers'],
                    'fake_screenshots' => ['🖼️', 'Fake Screenshots',     'Screenshots are edited or fake'],
                    'wrong_info'       => ['❌', 'Wrong Information',     'Account details are incorrect'],
                    'duplicate'        => ['📋', 'Duplicate Listing',     'Same account listed multiple times'],
                    'inappropriate'    => ['⚠️', 'Inappropriate Content','Content violates our rules'],
                    'other'            => ['💬', 'Other',                 'Something else not listed above'],
                ] as $value => [$icon, $label, $desc])
                <label class="cursor-pointer" x-data>
                    <input type="radio" name="reason" value="{{ $value }}"
                           class="sr-only peer"
                           {{ old('reason') === $value ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 bg-gray-800 border-2 border-gray-700
                                hover:border-gray-600 rounded-xl p-3 transition
                                peer-checked:border-red-500 peer-checked:bg-red-500/5">
                        <span class="text-lg">{{ $icon }}</span>
                        <div>
                            <div class="text-sm font-semibold">{{ $label }}</div>
                            <div class="text-xs text-gray-400">{{ $desc }}</div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('reason')
            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Details --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-5">
            <label class="block text-xs font-bold text-gray-500
                          uppercase tracking-wider mb-3">
                Additional Details (Optional)
            </label>
            <textarea name="details" rows="3"
                      placeholder="Describe the issue in more detail..."
                      class="w-full bg-gray-800 border border-gray-700 rounded-xl
                             px-3 py-2.5 text-sm text-white
                             focus:outline-none focus:border-red-500 resize-none">{{ old('details') }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('listings.show', $listing) }}"
               class="text-gray-400 hover:text-white text-sm transition">
                ← Cancel
            </a>
            <button type="submit"
                    class="bg-red-600 hover:bg-red-500 text-white
                           px-6 py-2.5 rounded-xl font-bold text-sm transition">
                Submit Report
            </button>
        </div>

    </form>
</div>
@endsection
