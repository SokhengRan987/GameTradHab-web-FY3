<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'new_users_today'  => User::whereDate('created_at', today())->count(),
            'active_listings'  => Listing::where('status', 'active')->count(),
            'flagged_listings' => Listing::where('is_flagged', true)
                                         ->where('status', 'active')->count(),
            'open_reports'     => Report::where('status', 'pending')->count(),
            'open_disputes'    => Transaction::where('status', 'disputed')->count(),
            'total_revenue'    => Transaction::where('status', 'completed')
                                             ->sum('platform_fee'),
            'revenue_today'    => Transaction::where('status', 'completed')
                                             ->whereDate('created_at', today())
                                             ->sum('platform_fee'),
            'total_transactions' => Transaction::count(),
        ];

        $recent_reports = Report::with(['listing.game', 'reporter'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recent_disputes = Transaction::with(['listing', 'buyer', 'seller'])
            ->where('status', 'disputed')
            ->latest()
            ->take(5)
            ->get();

        $flagged_listings = Listing::with(['game', 'seller'])
            ->where('is_flagged', true)
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_reports',
            'recent_disputes',
            'flagged_listings'
        ));
    }
}
