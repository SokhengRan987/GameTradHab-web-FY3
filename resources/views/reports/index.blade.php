@extends('layouts.admin')
@section('title', 'Reports')

@section('content')

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold">📋 Reports</h1>
        <div class="flex gap-2">
            @foreach(['pending' => '⏳ Pending', 'resolved' => '✅ Resolved', 'dismissed' => '❌ Dismissed'] as $status => $label)
            <a href="{{ route('admin.reports.index', ['status' => $status]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                      {{ request('status', 'pending') === $status
                         ? 'bg-sky-600 text-white'
                         : 'bg-gray-800 text-gray-400 hover:text-white' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col gap-3">
        @forelse($reports as $report)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4"
             x-data="{ open: false }">

            <div class="flex items-start gap-4">

                {{-- Report info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider
                                     px-2 py-0.5 rounded-full
                                     bg-red-500/10 text-red-400 border border-red-500/20">
                            {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $report->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="font-semibold mb-1">
                        {{ $report->listing->title ?? 'Deleted listing' }}
                    </div>
                    <div class="text-xs text-gray-400 mb-2">
                        Reported by
                        <strong class="text-white">{{ $report->reporter->name }}</strong>
                        · Seller:
                        <strong class="text-white">
                            {{ $report->listing->seller->name ?? '—' }}
                        </strong>
                        · Price:
                        <strong class="text-green-400">
                            ${{ number_format($report->listing->price ?? 0, 2) }}
                        </strong>
                    </div>
                    @if($report->details)
                    <div class="bg-gray-800 rounded-xl px-3 py-2 text-xs text-gray-300 italic">
                        "{{ $report->details }}"
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                @if($report->status === 'pending')
                <div class="flex flex-col gap-2 flex-shrink-0">
                    @if($report->listing)
                    <a href="{{ route('admin.listings.show', $report->listing_id) }}"
                       target="_blank"
                       class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5
                              rounded-lg transition text-center">
                        View Listing
                    </a>
                    @endif
                    <button @click="open = !open"
                            class="text-xs bg-red-600/20 hover:bg-red-600/40 text-red-400
                                   border border-red-500/30 px-3 py-1.5 rounded-lg transition">
                        🗑️ Remove Listing
                    </button>
                    <form method="POST"
                          action="{{ route('admin.reports.dismiss', $report) }}">
                        @csrf @method('PATCH')
                        <button class="w-full text-xs bg-gray-700 hover:bg-gray-600
                                       text-gray-300 px-3 py-1.5 rounded-lg transition">
                            ✓ Dismiss
                        </button>
                    </form>
                </div>
                @else
                <span class="text-xs px-2 py-1 rounded-full font-semibold flex-shrink-0
                             {{ $report->status === 'resolved'
                                ? 'bg-green-500/10 text-green-400 border border-green-500/20'
                                : 'bg-gray-500/10 text-gray-400 border border-gray-500/20' }}">
                    {{ ucfirst($report->status) }}
                </span>
                @endif

            </div>

            {{-- Remove confirm form --}}
            <div x-show="open" class="mt-4 pt-4 border-t border-gray-800">
                <form method="POST"
                      action="{{ route('admin.reports.remove', $report) }}">
                    @csrf @method('PATCH')
                    <p class="text-xs text-gray-400 mb-2">
                        This will remove the listing from the site. Add a note:
                    </p>
                    <textarea name="admin_note" rows="2"
                              placeholder="Reason for removing listing..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-xl
                                     px-3 py-2 text-sm text-white mb-2
                                     focus:outline-none focus:border-red-500 resize-none"></textarea>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-500 text-white
                                       px-4 py-2 rounded-xl text-sm font-bold transition">
                            Confirm Remove
                        </button>
                        <button type="button" @click="open = false"
                                class="bg-gray-700 hover:bg-gray-600 text-white
                                       px-4 py-2 rounded-xl text-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @empty
        <div class="bg-gray-900 border border-gray-800 rounded-xl px-4 py-12
                    text-center text-gray-500 text-sm">
            No {{ request('status', 'pending') }} reports 🎉
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $reports->withQueryString()->links() }}
    </div>

@endsection
