<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create(Listing $listing)
    {
        if ($listing->user_id === auth()->id()) {
            return back()->with('error', 'You cannot report your own listing.');
        }

        return view('reports.create', compact('listing'));
    }

    public function store(Request $request, Listing $listing)
    {
        if ($listing->user_id === auth()->id()) {
            return back()->with('error', 'You cannot report your own listing.');
        }

        // Check already reported
        $existing = Report::where('listing_id', $listing->id)
            ->where('reporter_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reported this listing.');
        }

        $request->validate([
            'reason'  => 'required|in:scam,fake_screenshots,wrong_info,duplicate,inappropriate,other',
            'details' => 'nullable|string|max:500',
        ]);

        Report::create([
            'listing_id'  => $listing->id,
            'reporter_id' => auth()->id(),
            'reason'      => $request->reason,
            'details'     => $request->details,
        ]);

        return back()->with('success', 'Report submitted. Our team will review within 24 hours.');
    }
}
