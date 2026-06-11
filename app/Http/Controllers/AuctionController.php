<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Listing;
use App\Models\Transaction;
use App\Services\AuctionService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuctionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly AuctionService $auctionService
    ) {
        $this->middleware('auth')->only(['create', 'store', 'bid', 'myBids']);
    }

    public function index(Request $request): View
    {
        $games = Game::where('is_active', true)->get();

        $listings = Listing::with(['game', 'seller', 'firstImage', 'highestBidder'])
            ->withCount('bids')
            ->where('type', 'auction')
            ->when($request->status === 'ended', fn($q) => $q->where('status', 'inactive'))
            ->when(!$request->status || $request->status === 'active', fn($q) => $q->where('status', 'active'))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->game_id, fn($q) => $q->where('game_id', $request->game_id))
            ->when($request->platform, fn($q) => $q->where('platform', $request->platform))
            ->when($request->sort === 'ending_soon', fn($q) => $q->orderBy('auction_ends_at', 'asc'))
            ->when($request->sort === 'highest_bid', fn($q) => $q->orderBy('current_bid', 'desc'))
            ->when($request->sort === 'lowest_bid', fn($q) => $q->orderBy('current_bid', 'asc'))
            ->when(!$request->sort, fn($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        return view('auctions.index', compact('listings', 'games'));
    }

    public function show(Listing $listing): View
    {
        if (!$listing->isAuction()) {
            abort(404);
        }

        if ($listing->status === 'active') {
            $listing->incrementViews();
        }

        $listing->load([
            'game',
            'seller',
            'images',
            'highestBidder',
            'bids.user',
        ]);

        $bidHistory = $listing->bids()
            ->with('user')
            ->orderBy('amount', 'desc')
            ->take(10)
            ->get();

        return view('auctions.show', compact('listing', 'bidHistory'));
    }

    public function create(): View
    {
        $games = Game::where('is_active', true)->get();
        return view('auctions.create', compact('games'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'game_id'          => 'required|exists:games,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'starting_price'   => 'required|numeric|min:1',
            'bid_increment'    => 'required|numeric|min:0.5',
            'rank'             => 'nullable|string|max:100',
            'level'            => 'nullable|integer|min:1',
            'server'           => 'nullable|string|max:100',
            'platform'         => 'required|in:Mobile,PC,Console',
            'account_age'      => 'nullable|string|max:100',
            'contact_telegram' => 'nullable|string|max:255',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_discord'  => 'nullable|string|max:100',

            'images'           => 'required|array|min:1',
            'images.*'         => 'image|mimes:jpg,jpeg,png,webp|max:10240',

            'auction_ends_at'  => 'required|date',
        ], [
            'images.required' => 'Please upload at least one image.',
            'images.*.image'  => 'The :attribute must be an image.',
            'images.*.mimes'  => 'The :attribute must be a file of type: jpg, jpeg, png, webp.',
            'images.*.max'    => 'Each image must be less than 10MB.',
        ]);

        // Custom validation for auction end time (outside main validate for better control)
        $endsAt = Carbon::parse($request->auction_ends_at, 'Asia/Phnom_Penh')->utc();
        if ($endsAt->lt(now()->utc()->addHour())) {
            return back()
                ->withErrors(['auction_ends_at' => 'Auction end time must be at least 1 hour from now.'])
                ->withInput();
        }

        $validated['auction_ends_at'] = $endsAt->utc();

        // Auto-flagging
        $isFlagged = false;
        $flagReason = null;

        if (Auth::user()->created_at->diffInHours(now()) < 24) {
            $isFlagged = true;
            $flagReason = 'New account — registered less than 24 hours ago';
        } elseif ($validated['starting_price'] < 3) {
            $isFlagged = true;
            $flagReason = 'Suspicious starting price — under $3';
        } else {
            foreach (['hack', 'cheat', 'stolen', 'illegal', 'fake'] as $word) {
                if (stripos($validated['title'], $word) !== false) {
                    $isFlagged = true;
                    $flagReason = 'Suspicious keyword in title: ' . $word;
                    break;
                }
            }
        }

        $listing = null;

        try {
            DB::transaction(function () use ($validated, $request, &$listing, $isFlagged, $flagReason) {
                $listing = Listing::create([
                    ...$validated,
                    'user_id'     => Auth::id(),
                    'price'       => $validated['starting_price'],
                    'status'      => 'active',
                    'type'        => 'auction',
                    'is_flagged'  => $isFlagged,
                    'flag_reason' => $flagReason,
                    'flagged_at'  => $isFlagged ? now() : null,
                ]);

                foreach ($request->file('images', []) as $index => $image) {
                    $result = cloudinary()->uploadApi()->upload(
                        $image->getPathname(),
                        [
                            'folder' => "gametradehub/auctions/{$listing->id}",
                            'transformation' => [
                                'width'        => 1200,
                                'height'       => 1200,
                                'crop'         => 'limit',
                                'quality'      => 'auto:good',
                                'fetch_format' => 'auto',
                            ],
                        ]
                    );

                    $listing->images()->create([
                        'image_path' => $result['secure_url'],
                        'is_proof'   => true,
                        'sort_order' => $index,
                    ]);
                }
            });

            $message = $isFlagged
                ? 'Auction is live! Note: it has been flagged for admin review.'
                : '🎉 Auction is live! Bidding is now open.';

            return redirect()
                ->route('dashboard')
                ->with('success', $message);

        } catch (\Exception $e) {
            report($e);

            return back()
                ->withErrors(['general' => 'Failed to create auction. Please try again.'])
                ->withInput();
        }
    }

    public function bid(Request $request, Listing $listing)
    {
        logger('Bid attempt', [
            'input' => $request->amount,
            'min' => $listing->minimumNextBid(),
        ]);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $listing->minimumNextBid()],
        ]);

        if ($listing->user_id === Auth::id()) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot bid on your own listing.'
                ], 422);
            }

            return back()->with('error', 'You cannot bid on your own listing.');
        }

        try {
            $this->auctionService->placeBid(
                $listing,
                Auth::user(),
                (float) $request->amount
            );

            // ✅ AJAX RESPONSE
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bid placed successfully!'
                ]);
            }

            // ✅ NORMAL FORM FALLBACK
            return redirect()
                ->route('auctions.show', $listing)
                ->with('success', '🎉 Bid placed successfully! You are now the highest bidder.');

        } catch (\Exception $e) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()
                ->route('auctions.show', $listing)
                ->with('error', $e->getMessage());
        }
    }

    public function myBids(): View
    {
        $bids = Auth::user()
            ->bids()
            ->with([
                'listing.game',
                'listing.highestBidder',
                'listing.transactions' // preload
            ])
            ->latest()
            ->paginate(15);


            $wonAuctions = Transaction::with('listing.game')
                    ->where('buyer_id', Auth::id())
                    ->where('status', 'pending')
                    ->whereHas('listing', fn($q) => $q->where('type', 'auction'))
                    ->get();

        return view('auctions.my-bids', compact('bids', '$wonAuctions'));
    }
}
