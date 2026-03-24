<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Listing;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    use AuthorizesRequests;
    // Browse all active listings with filters
    public function index(Request $request)
    {
        $games = Game::where('is_active', true)->get();

        $listings = Listing::with(['game', 'seller', 'firstImage'])
            ->where('status', 'active')
            ->where('type', 'fixed')
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', '%' . $request->search . '%')
            )
            ->when($request->game_id, fn($q) =>
                $q->where('game_id', $request->game_id)
            )
            ->when($request->platform, fn($q) =>
                $q->where('platform', $request->platform)
            )
            ->when($request->min_price, fn($q) =>
                $q->where('price', '>=', $request->min_price)
            )
            ->when($request->max_price, fn($q) =>
                $q->where('price', '<=', $request->max_price)
            )
            ->when($request->sort === 'price_asc',  fn($q) => $q->orderBy('price', 'asc'))
            ->when($request->sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc'))
            ->when($request->sort === 'popular',    fn($q) => $q->orderBy('views_count', 'desc'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('listings.index', compact('listings', 'games'));
    }

    // Show single listing detail
    public function show(Listing $listing)
    {
        // Only show active listings to public
        if ($listing->status !== 'active') {
            abort(404);
        }

        $listing->incrementViews();
        $listing->load(['game', 'seller', 'images']);

        $related = Listing::active()
            ->where('game_id', $listing->game_id)
            ->where('id', '!=', $listing->id)
            ->take(4)
            ->get();

        return view('listings.show', compact('listing', 'related'));
    }

    // Show create listing form
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $games = Game::where('is_active', true)->get();

        if (!auth()->user()->hasCompletedOnboarding()) {
            session()->flash('warning', 'Complete seller onboarding to publish, or continue to create a draft.');
        }

        return view('listings.create', compact('games'));
    }

    // Save new listing
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id'           => 'required|exists:games,id',
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'price'             => 'required|numeric|min:1',
            'rank'              => 'nullable|string|max:100',
            'level'             => 'nullable|integer|min:1',
            'server'            => 'nullable|string|max:100',
            'platform'          => 'required|in:Mobile,PC,Console',
            'account_age'       => 'nullable|string|max:100',
            'contact_telegram'  => 'nullable|string|max:500',
            'contact_whatsapp'  => 'nullable|string|max:20',
            'contact_discord'   => 'nullable|string|max:100',
            'seller_phone'      => 'nullable|string|max:20',
            'seller_country'    => 'nullable|string|max:10',
            'stock_source'      => 'nullable|in:self_farmed,resell,gifted,other',
            'stock_source_note' => 'nullable|string|max:500',
            'images'            => 'required|array|min:1',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        // Parse Telegram link to extract username
        if (!empty($validated['contact_telegram'])) {
            $telegram = $validated['contact_telegram'];

            // Try to extract username from various formats
            if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:t\.me|telegram\.me)\/([^\s\/?#]+)/i', $telegram, $matches)) {
                $telegram = $matches[1];
            } else if (preg_match('/(?:t\.me|telegram\.me)\/([^\s\/?#]+)/i', $telegram, $matches)) {
                $telegram = $matches[1];
            }

            // Clean username - remove @ and invalid chars
            $telegram = preg_replace('/^[@#]/', '', $telegram);
            $telegram = preg_replace('/[^\w.-]/', '', $telegram);
            $validated['contact_telegram'] = $telegram ?: $validated['contact_telegram'];
        }


        // ── Auto-flag check ────────────────────────────────
        $isFlagged  = false;
        $flagReason = null;

        // Flag new accounts (registered less than 24 hours ago)
        if (auth()->user()->created_at->diffInHours(now()) < 24) {
            $isFlagged  = true;
            $flagReason = 'New account — registered less than 24 hours ago';
        }

        // Flag suspiciously low price
        if ($validated['price'] < 3) {
            $isFlagged  = true;
            $flagReason = 'Suspicious price — under $3';
        }

        // Flag suspicious keywords in title
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
            'status'      => 'active',  // instantly live
            'type'        => 'fixed',
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
            ? 'Listing is live! Note: it has been flagged for admin review.'
            : '🎉 Listing is live! Buyers can now find your account.';

        return redirect()
            ->route('dashboard')
            ->with('success', $message);
    }

    // Show edit form
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $games = Game::where('is_active', true)->get();

        // Auction listings use a different edit view
        if($listing->isAuction()) {
            return view('auctions.edit', compact('listing', 'games'));
        }

        return view('listings.edit', compact('listing', 'games'));
    }

    // Update listing
    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        // Different validation for auction vs fixed
        if ($listing->isAuction()){
            $validated = $request->validate([
                'game_id'     => 'required|exists:games,id',
                'title'       => 'required|string|max:255',
                'description' => 'required|string',
                'starting_price' => 'required|numeric|min:1',
                'bid_increment' => 'required|numeric|min:0.5',
                'auction_ends_at' => 'required|date|after:now',
                //'price'       => 'required|numeric|min:1',
                'rank'        => 'nullable|string|max:100',
                'level'       => 'nullable|integer|min:1',
                'server'      => 'nullable|string|max:100',
                'platform'    => 'required|in:Mobile,PC,Console',
                'account_age' => 'nullable|string|max:100',
            ]);
            // Re-submit for admin approval after edit
            $listing->update([
                ...$validated,
                'price'=> $validated['starting_price'],
                'status' => 'pending',
            ]);
        } else {
            $validated = $request->validate([
                'game_id' =>'required|exists:games,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:1',
                'rank' => 'nullable|string|max:100',
                'level' => 'nullable|integer|min:1',
                'server' => 'nullable|string|max:100',
                'platform' => 'required|in:Mobile,PC,Console',
                'account_age' => 'nullable|string|max:100',
            ]);
            $listing->update([
                ...$validated,
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing updated and re-submitted for review.');
    }

    // Delete listing
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        // Delete all images from storage
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $listing->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing deleted.');
    }
}
