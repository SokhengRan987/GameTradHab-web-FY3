<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'active_listings'   => Listing::where('status', 'active')->count(),
            'pending_listings'  => Listing::where('status', 'pending')->count(),
            'total_revenue'     => Transaction::where('status', 'completed')->sum('platform_fee'),
            'total_transactions'=> Transaction::count(),
            'open_disputes'     => Transaction::where('status', 'disputed')->count(),
        ];

        $pending_listings = Listing::with(['game', 'seller'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recent_transactions = Transaction::with(['listing', 'buyer', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'pending_listings',
            'recent_transactions'
        ));
    }
}
