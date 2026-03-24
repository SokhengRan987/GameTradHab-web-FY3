<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with(['listing.game', 'listing.seller', 'reporter'])
            ->when(
                $request->status,
                fn($q) => $q->where('status', $request->status),
                fn($q) => $q->where('status', 'pending')
            )
            ->latest()
            ->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    public function removeListing(Request $request, Report $report)
    {
        $request->validate([
            'admin_note' => 'required|string',
        ]);

        // Take down the listing
        $report->listing->update(['status' => 'inactive']);

        // Resolve this report
        $report->update([
            'status'      => 'resolved',
            'reviewed_by' => auth()->id(),
            'admin_note'  => $request->admin_note,
            'reviewed_at' => now(),
        ]);

        // Resolve all other pending reports for same listing
        Report::where('listing_id', $report->listing_id)
            ->where('status', 'pending')
            ->update([
                'status'      => 'resolved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

        return back()->with('success', 'Listing removed and report resolved.');
    }

    public function dismiss(Request $request, Report $report)
    {
        $report->update([
            'status'      => 'dismissed',
            'reviewed_by' => auth()->id(),
            'admin_note'  => $request->admin_note ?? 'No issue found.',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report dismissed. Listing remains live.');
    }
}
