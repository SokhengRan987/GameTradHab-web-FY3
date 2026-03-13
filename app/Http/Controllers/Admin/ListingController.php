<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // Show all listings with filter by status
    public function index(Request $request)
    {
        $listings = Listing::with(['game', 'seller'])
            ->when(
                $request->status,
                fn($q) => $q->where('status', $request->status),
                fn($q) => $q->where('status', 'pending') // default to pending
            )
            ->latest()
            ->paginate(15);

        return view('admin.listings.index', compact('listings'));
    }

    // Show single listing for review
    public function show(Listing $listing)
    {
        $listing->load(['game', 'seller', 'images']);
        return view('admin.listings.show', compact('listing'));
    }

    // Approve a listing — makes it live
    public function approve(Listing $listing)
    {
        $listing->update([
            'status'      => 'active',
            'admin_notes' => null,
        ]);

        return back()->with('success', 'Listing approved and is now live.');
    }

    // Reject a listing with a reason
    public function reject(Request $request, Listing $listing)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $listing->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Listing rejected.');
    }
}
