@extends('layouts.admin')
@section('title', 'Review Listing #' . $listing->id)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.listings.index') }}"
               class="text-sm text-gray-400 hover:text-white transition flex items-center gap-1">
                ← Back to Listings
            </a>
            <h1 class="text-2xl font-bold mt-1">Review Listing</h1>
            <p class="text-gray-400 text-sm">#{{ $listing->id }}</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Flag / Unflag Button --}}
            @if(!$listing->is_flagged)
                <form action="{{ route('admin.listings.flag', $listing) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            onclick="return confirm('Flag this listing?')"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-sm font-medium transition">
                        🚩 Flag Listing
                    </button>
                </form>
            @else
                <form action="{{ route('admin.listings.unflag', $listing) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            onclick="return confirm('Unflag this listing?')"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl text-sm font-medium transition">
                        ✅ Unflag Listing
                    </button>
                </form>
            @endif

            @if($listing->status === 'pending')
                <form action="{{ route('admin.listings.approve', $listing) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-2xl text-sm font-bold transition">
                        ✓ Approve
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Listing Details --}}
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6">
                <div class="uppercase text-xs tracking-widest font-semibold text-gray-500 mb-4">
                    Listing Details
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        'Title'     => $listing->title,
                        'Game'      => $listing->game->name ?? '—',
                        'Rank'      => $listing->rank ?? '—',
                        'Price'     => '$'.number_format($listing->price, 2),
                        'Platform'  => $listing->platform ?? '—',
                        'Level'     => $listing->level ?? '—',
                    ] as $label => $value)
                    <div class="bg-gray-800 rounded-2xl p-4">
                        <div class="text-xs text-gray-500 uppercase tracking-widest">{{ $label }}</div>
                        <div class="font-semibold mt-1">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>

                @if($listing->description)
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <div class="text-xs text-gray-500 uppercase tracking-widest mb-2">Description</div>
                        <p class="text-gray-300 leading-relaxed">{{ $listing->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Screenshots --}}
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6">
                <div class="uppercase text-xs tracking-widest font-semibold text-gray-500 mb-4">
                    Proof Screenshots ({{ $listing->images->count() }})
                </div>
                @if($listing->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($listing->images as $image)
                            <a href="{{ $image->url }}" target="_blank" class="group">
                                <img src="{{ $image->url }}"
                                     class="w-full aspect-video object-cover rounded-2xl border border-gray-700
                                            group-hover:border-indigo-500 transition">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No screenshots uploaded.</p>
                @endif
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Flag Status --}}
            @if($listing->is_flagged)
                <div class="bg-red-500/10 border border-red-500/30 rounded-3xl p-6">
                    <div class="flex items-center gap-2 text-red-400 mb-3">
                        <span class="text-2xl">🚩</span>
                        <strong class="text-lg">This listing is flagged</strong>
                    </div>
                    @if($listing->flag_reason)
                        <p class="text-red-300 text-sm">{{ $listing->flag_reason ?? 'Flagged by system' }}</p>
                    @endif
                </div>
            @endif

            {{-- Seller Info --}}
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6">
                <div class="uppercase text-xs tracking-widest font-semibold text-gray-500 mb-4">
                    Seller Information
                </div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-11 h-11 bg-indigo-600 rounded-2xl flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($listing->seller->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ $listing->seller-> ?? 'Unknown Seller' }}</div>
                        <div class="text-xs text-gray-400">{{ $listing->seller->email ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Sales</span>
                        <span class="font-medium">{{ $listing->seller->total_sales ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Member Since</span>
                        <span>{{ $listing->seller->created_at->format('M Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6">
                <div class="uppercase text-xs tracking-widest font-semibold text-gray-500 mb-4">
                    Quick Actions
                </div>

                <div class="space-y-3">
                    @if($listing->status === 'pending')
                        <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-3 rounded-2xl font-bold transition">
                                ✓ Approve Listing
                            </button>
                        </form>

                        {{-- Reject with Reason --}}
                        <form action="{{ route('admin.listings.reject', $listing) }}" method="POST"
                              x-data="{ showReject: false }">
                            @csrf
                            @method('PATCH')
                            <button type="button" @click="showReject = !showReject"
                                    class="w-full bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-500/30 py-3 rounded-2xl font-bold transition">
                                ✕ Reject Listing
                            </button>

                            <div x-show="showReject" class="mt-3">
                                <textarea name="admin_notes" rows="3"
                                    class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-4 py-3 text-sm focus:border-red-500"
                                    placeholder="Reason for rejection..."></textarea>
                                <button type="submit"
                                        class="w-full mt-2 bg-red-600 hover:bg-red-500 text-white py-3 rounded-2xl font-bold">
                                    Confirm Rejection
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- <a href="{{ route('admin.listings.remove', $listing) }}"
                       onclick="return confirm('Permanently remove this listing?')"
                       class="block w-full text-center py-3 bg-gray-700 hover:bg-gray-600 rounded-2xl text-sm font-medium transition">
                        🗑️ Remove Listing
                    </a> --}}

<form action="{{ route('admin.listings.remove', $listing) }}" method="POST">
    @csrf
    @method('PATCH')
    <button
        onclick="return confirm('Permanently remove this listing?')"
        class="w-full text-center py-3 bg-gray-700 hover:bg-gray-600 rounded-2xl text-sm font-medium transition">
        🗑️ Remove Listing
    </button>
</form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
