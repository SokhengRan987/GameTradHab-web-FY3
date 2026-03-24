<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;

class SellerProfileController extends Controller
{
    public function show(User $user)
    {
        // Load seller reviews
        $reviews = $user->reviews()
            ->with(['reviewer', 'listing'])
            ->paginate(10);

        // Load active listings by this seller
        $listings = Listing::with(['game', 'firstImage'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        // Rating breakdown — count per star
        $ratingBreakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingBreakdown[$i] = $user->reviews()
                ->where('rating', $i)
                ->count();
        }

        $totalReviews = array_sum($ratingBreakdown);

        return view('sellers.show', compact(
            'user',
            'reviews',
            'listings',
            'ratingBreakdown',
            'totalReviews'
        ));
    }
}
