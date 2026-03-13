<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
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
        $games = Game::where('is_active', true)->get();
        return view('listings.create', compact('games'));
    }

    // Save new listing
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id'     => 'required|exists:games,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:1',
            'rank'        => 'nullable|string|max:100',
            'level'       => 'nullable|integer|min:1',
            'server'      => 'nullable|string|max:100',
            'platform'    => 'required|in:Mobile,PC,Console',
            'account_age' => 'nullable|string|max:100',
            'images'      => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        // Create the listing — status is pending until admin approves
        $listing = Listing::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status'  => 'pending',
            'type'    => 'fixed',
        ]);

        // Upload each image to storage
        foreach ($request->file('images', []) as $index => $image) {
            $path = $image->store('listings/' . $listing->id, 'public');
            $listing->images()->create([
                'image_path'  => $path,
                'is_proof'    => true,
                'sort_order'  => $index,
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing submitted! We will review it within 24 hours.');
    }

    // Show edit form
    public function edit(Listing $listing)
    {
        // Only the seller can edit their own listing
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $games = Game::where('is_active', true)->get();
        return view('listings.edit', compact('listing', 'games'));
    }

    // Update listing
    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'game_id'     => 'required|exists:games,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:1',
            'rank'        => 'nullable|string|max:100',
            'level'       => 'nullable|integer|min:1',
            'server'      => 'nullable|string|max:100',
            'platform'    => 'required|in:Mobile,PC,Console',
            'account_age' => 'nullable|string|max:100',
        ]);

        // Re-submit for admin approval after edit
        $listing->update([
            ...$validated,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing updated and re-submitted for review.');
    }

    // Delete listing
    public function destroy(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

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