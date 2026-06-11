{{-- resources/views/partials/listings-grid.blade.php --}}
@forelse($listings as $listing)
@if($loop->first)
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
@endif

    <a href="{{ route('listings.show', $listing) }}"
       class="group bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden
              hover:border-indigo-500/50 transition-all duration-300
              hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/10 block">

        <div class="relative h-36 overflow-hidden"
             style="background: linear-gradient(135deg, #0f0f1a, #1a1a2e)">
            @if($listing->firstImage)
            <img src="{{ $listing->firstImage->url }}"
                 class="w-full h-full object-cover opacity-70
                        group-hover:opacity-90 group-hover:scale-105 transition-all duration-500">
            @else
            <div class="w-full h-full flex items-center justify-center opacity-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                </svg>
            </div>
            @endif

            <div class="absolute top-2 left-2">
                <span class="bg-black/70 backdrop-blur-sm text-indigo-300 text-xs px-2 py-0.5
                             rounded-full font-bold border border-indigo-500/20">
                    {{ $listing->game->name }}
                </span>
            </div>

            @if($listing->is_featured)
            <div class="absolute top-2 right-2">
                <span class="bg-yellow-500 text-black text-xs px-2 py-0.5 rounded-full font-black">TOP</span>
            </div>
            @endif

            <div class="absolute bottom-2 right-2 flex items-center gap-1
                        bg-black/50 text-gray-400 text-xs px-2 py-0.5 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ $listing->views_count }}
            </div>
        </div>

        <div class="p-3">
            <h3 class="font-semibold text-sm text-white leading-tight mb-2 line-clamp-2
                       group-hover:text-indigo-300 transition">
                {{ $listing->title }}
            </h3>

            <div class="flex flex-wrap gap-1 mb-3">
                @if($listing->rank)
                <span class="text-xs bg-gray-800 border border-gray-700 px-2 py-0.5 rounded-full text-gray-400">
                    Rank {{ $listing->rank }}
                </span>
                @endif
                @if($listing->server)
                <span class="text-xs bg-gray-800 border border-gray-700 px-2 py-0.5 rounded-full text-gray-400">
                    {{ $listing->server }}
                </span>
                @endif
                @if($listing->platform)
                <span class="text-xs bg-gray-800 border border-gray-700 px-2 py-0.5 rounded-full text-gray-400">
                    {{ $listing->platform }}
                </span>
                @endif
                @if(Auth::check() && auth()->id() === $listing->user_id && $listing->status !== 'active')
                <span class="text-[10px] font-bold uppercase tracking-[0.2em]
                             bg-yellow-500/10 border border-yellow-500/20
                             text-yellow-300 px-2 py-1 rounded-full">
                    {{ strtoupper($listing->status) }}
                </span>
                @endif
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-gray-800">
                <div>
                    <div class="font-game font-bold text-green-400 text-base">
                        ${{ number_format($listing->price, 2) }}
                    </div>
                    @if($listing->seller->rating_avg > 0)
                    <div class="text-xs text-yellow-400">
                        ★ {{ number_format($listing->seller->rating_avg, 1) }}
                    </div>
                    @endif
                </div>
                <div class="bg-indigo-600/20 border border-indigo-500/30
                            group-hover:bg-indigo-600 text-indigo-400
                            group-hover:text-white text-xs px-3 py-1.5
                            rounded-lg font-bold transition-all duration-300">
                    VIEW
                </div>
            </div>
        </div>
    </a>

@if($loop->last)
</div>
@endif

@empty
<div class="text-center py-20">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
    </svg>
    <h3 class="font-game text-xl font-bold text-gray-500 mb-2">NO ACCOUNTS FOUND</h3>
    <p class="text-gray-600 text-sm mb-5">Try different filters or check back later</p>
    @if(request()->hasAny(['search','game_id','platform','sort','min_price','max_price']))
    <a href="{{ route('home') }}"
       class="inline-flex bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
        Clear Filters
    </a>
    @endif
</div>
@endforelse

{{-- Pagination --}}
@if($listings->hasPages())
<div class="mt-8 flex justify-center">
    {{ $listings->withQueryString()->links() }}
</div>
@endif
