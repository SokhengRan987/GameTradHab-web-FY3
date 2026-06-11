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
        // dd($request->all());
        
        $query = Listing::with(['game', 'seller']);

        // ✅ PRIORITY: flagged filter
        if ($request->get('filter') === 'flagged') {
            $query->where('is_flagged', true)
                ->where('status', 'active');
        } else {
            // ✅ default behavior
            if ($request->status) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', 'pending');
            }
        }

        $listings = $query->latest()->paginate(15);

        return view('admin.listings.index', compact('listings'));
    }

    // Show single listing for review
    public function show(Listing $listing)
    {
        $listing->load(['game', 'seller', 'images']);
        return view('admin.listings.show', compact('listing'));
    }

    /**
     * Approve a listing
     */
    public function approve(Listing $listing)
    {
        $listing->update([
            'status'      => 'active',     // Recommended: use 'active' instead of 'approved'
            'admin_notes' => null,
        ]);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing approved and is now live.');
    }

    /**
     * Reject a listing with reason
     */
    public function reject(Request $request, Listing $listing)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $listing->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing has been rejected.');
    }

    // Remove a listing from marketplace
    public function remove(Listing $listing)
    {
        $listing->update(['status' => 'inactive']);

        return back()->with('success', 'Listing removed from marketplace.');
    }

    // Unflag a listing (clear the admin flag)
    public function unflag(Listing $listing)
    {
        $listing->update([
            'is_flagged'  => false,
            'flag_reason' => null,
            'flagged_at'  => null,
        ]);

        return back()->with('success', 'Listing unflagged.');
    }
}
