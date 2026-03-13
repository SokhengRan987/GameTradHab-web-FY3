@extends('layouts.admin')
@section('title', 'Review Listing')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.listings.index') }}"
           class="text-sm text-gray-400 hover:text-white transition">
            ← Back to Listings
        </a>
    </div>

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold">🔍 Review Listing</h1>
        @if($listing->status === 'pending')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                @csrf @method('PATCH')
                <button class="bg-green-600 hover:bg-green-500 text-white
                               px-4 py-2 rounded-xl text-sm font-bold transition">
                    ✓ Approve Listing
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-3 gap-5">

        <div class="col-span-2 flex flex-col gap-4">

            {{-- Details --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Listing Details
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        'Title'       => $listing->title,
                        'Game'        => $listing->game->name,
                        'Rank'        => $listing->rank ?? '—',
                        'Price'       => '$'.number_format($listing->price,2),
                        'Server'      => $listing->server ?? '—',
                        'Platform'    => $listing->platform,
                        'Level'       => $listing->level ?? '—',
                        'Account Age' => $listing->account_age ?? '—',
                    ] as $label => $value)
                    <div class="bg-gray-800 rounded-xl p-3">
                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ $label }}</div>
                        <div class="font-semibold text-sm">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Description</div>
                    <p class="text-sm text-gray-300 leading-relaxed">{{ $listing->description }}</p>
                </div>
            </div>

            {{-- Screenshots --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Proof Screenshots ({{ $listing->images->count() }})
                </div>
                @if($listing->images->count() > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($listing->images as $image)
                    <a href="{{ $image->url }}" target="_blank">
                        <img src="{{ $image->url }}"
                             class="w-full aspect-video object-cover rounded-xl
                                    border border-gray-700 hover:border-indigo-500 transition">
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-sm">No screenshots uploaded.</p>
                @endif
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-4">

            {{-- Seller Info --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Seller Info
                </div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center
                                justify-center font-bold">
                        {{ strtoupper(substr($listing->seller->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ $listing->seller->name }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $listing->seller->email }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Sales</span>
                        <span>{{ $listing->seller->total_sales }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Joined</span>
                        <span>{{ $listing->seller->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                    Quick Actions
                </div>
                <div class="flex flex-col gap-2">

                    @if($listing->status === 'pending')
                    <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                        @csrf @method('PATCH')
                        <button class="w-full bg-green-600 hover:bg-green-500 text-white
                                       py-2.5 rounded-xl text-sm font-bold transition">
                            ✓ Approve Listing
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <form method="POST" action="{{ route('admin.listings.reject', $listing) }}"
                          x-data="{ show: false }">
                        @csrf @method('PATCH')
                        <button type="button" @click="show = !show"
                                class="w-full bg-red-600/20 hover:bg-red-600/40 text-red-400
                                       border border-red-500/30 py-2.5 rounded-xl text-sm font-bold transition">
                            ✕ Reject with Reason
                        </button>
                        <div x-show="show" class="mt-2">
                            <textarea name="admin_notes" rows="3"
                                      placeholder="Explain why this listing is rejected..."
                                      class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                             px-3 py-2 text-sm text-white focus:outline-none
                                             focus:border-red-500 resize-none mb-2"></textarea>
                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-500 text-white
                                           py-2 rounded-xl text-sm font-bold transition">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection
