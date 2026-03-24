<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Services\AuctionService;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function __construct(
        private AuctionService $auctionService,
    ) {}

    public function index(Request $request)
    {
        $listings = Listing::with(['game', 'seller'])
            ->where('type', 'auction')
            ->when($request->status,
                fn($q) => $q->where('status', $request->status)
            )
            ->when($request->filter === 'flagged',
                fn($q) => $q->where('is_flagged', true)
            )
            ->latest()
            ->paginate(15);

        return view('admin.auctions.index', compact('listings'));
    }

    public function show(Listing $listing)
    {
        $listing->load(['game', 'seller', 'images', 'bids.user']);
        return view('admin.auctions.show', compact('listing'));
    }

    // Remove auction — takes it down
    public function remove(Listing $listing)
    {
        $listing->update(['status' => 'inactive']);
        return back()->with('success', 'Auction removed from site.');
    }

    // End auction early
    public function end(Listing $listing)
    {
        if (!$listing->isAuction() || $listing->status !== 'active') {
            return back()->with('error', 'Cannot end this auction.');
        }

        $this->auctionService->endAuction($listing);
        return back()->with('success', 'Auction ended. Winner notified and escrow created.');
    }
}
