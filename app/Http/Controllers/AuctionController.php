<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Listing;
use App\Services\AuctionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;


class AuctionController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private AuctionService $auctionService,
    ) {}

    // Browse all live auctions
    public function index(Request $request)
    {
        $games = Game::where('is_active', true)->get();  // ← was calling Game::with() instead of Listing::with()

        $listings = Listing::with(['game', 'seller', 'firstImage', 'highestBidder'])
            ->where('type', 'auction')
            ->where('status', 'active')
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', '%' . $request->search . '%')
            )
            ->when($request->game_id, fn($q) =>
                $q->where('game_id', $request->game_id)
            )
            ->when($request->platform, fn($q) =>
                $q->where('platform', $request->platform)
            )
            ->when($request->sort === 'ending_soon',
                fn($q) => $q->orderBy('auction_ends_at', 'asc')
            )
            ->when($request->sort === 'highest_bid',
                fn($q) => $q->orderBy('current_bid', 'desc')
            )
            ->when($request->sort === 'lowest_bid',
                fn($q) => $q->orderBy('current_bid', 'asc')
            )
            ->where('auction_ends_at', '>', now())
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('auctions.index', compact('listings', 'games'));
    }

    // Show single auction detail
    public function show(Listing $listing)
    {
        if (!$listing->isAuction() || $listing->status !== 'active') {
            abort(404);
        }

        $listing->incrementViews();
        $listing->load([
            'game',
            'seller',
            'images',
            'highestBidder',
            'bids.user',
        ]);

        // Top 10 bids for history
        $bidHistory = $listing->bids()
            ->with('user')
            ->orderBy('amount', 'desc')
            ->take(10)
            ->get();

        return view('auctions.show', compact('listing', 'bidHistory')); // ← was missing quotes around bidHistory
    }

    // Show create auction form
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $games = Game::where('is_active', true)->get();

        if (!auth()->user()->hasCompletedOnboarding()) {
            session()->flash('warning', 'Complete seller onboarding to publish, or continue to create a draft.');
        }

        return view('auctions.create', compact('games'));
    }

    // Save new auction listing
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id'         => 'required|exists:games,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'starting_price'  => 'required|numeric|min:1',
            'bid_increment'   => 'required|numeric|min:0.5',
            'auction_ends_at' => 'required|date|after:+1 hour',
            'rank'            => 'nullable|string|max:100',
            'level'           => 'nullable|integer|min:1',
            'server'          => 'nullable|string|max:100',
            'platform'        => 'required|in:Mobile,PC,Console',
            'account_age'     => 'nullable|string|max:100',
            'images'          => 'required|array|min:1',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        // ── Auto-flag check ────────────────────────────────
        $isFlagged  = false;
        $flagReason = null;

        if (auth()->user()->created_at->diffInHours(now()) < 24) {
            $isFlagged  = true;
            $flagReason = 'New account — registered less than 24 hours ago';
        }

        if ($validated['starting_price'] < 3) {
            $isFlagged  = true;
            $flagReason = 'Suspicious starting price — under $3';
        }

        foreach (['hack', 'cheat', 'stolen', 'illegal', 'fake'] as $word) {
            if (stripos($validated['title'], $word) !== false) {
                $isFlagged  = true;
                $flagReason = 'Suspicious keyword in title: ' . $word;
                break;
            }
        }
        // ──────────────────────────────────────────────────

        $listing = Listing::create([
            ...$validated,
            'user_id'     => auth()->id(),
            'price'       => $validated['starting_price'],
            'status'      => 'active',  // instantly live
            'type'        => 'auction',
            'is_flagged'  => $isFlagged,
            'flag_reason' => $flagReason,
            'flagged_at'  => $isFlagged ? now() : null,
        ]);

        foreach ($request->file('images', []) as $index => $image) {
            $path = $image->store('listings/' . $listing->id, 'public');
            $listing->images()->create([
                'image_path' => $path,
                'is_proof'   => true,
                'sort_order' => $index,
            ]);
        }

        $message = $isFlagged
            ? 'Auction is live! Note: it has been flagged for admin review.'
            : '🎉 Auction is live! Bidding is now open.';

        return redirect()
            ->route('dashboard')
            ->with('success', $message);
    }

    // Buyer places a bid
    public function bid(Request $request, Listing $listing)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $this->auctionService->placeBid(
                $listing,
                auth()->user(),
                (float) $request->amount
            );

            return redirect()
                ->route('auctions.show', $listing)
                ->with('success', '🎉 Bid placed successfully! You are now the highest bidder.');

        } catch (\Exception $e) {
            return redirect()
                ->route('auctions.show', $listing)
                ->with('error', $e->getMessage());
        }
    }

    // Show auctions the user is bidding on
    public function myBids()
    {
        if (!auth()->check()){
            return redirect()->route('login');
        }
        $bids = auth()->user()
            ->bids()
            ->with(['listing.game', 'listing.highestBidder'])
            ->latest()
            ->paginate(15);

        return view('auctions.my-bids', compact('bids'));
    }
}
