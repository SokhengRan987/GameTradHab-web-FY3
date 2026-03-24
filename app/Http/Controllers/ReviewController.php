<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Submit a review
    public function store(Request $request, Transaction $transaction)
    {
        // Only the buyer can review
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        // Transaction must be completed
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'You can only review completed transactions.');
        }

        // One review per transaction
        if ($transaction->hasReview()) {
            return back()->with('error', 'You have already reviewed this transaction.');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Create the review
        Review::create([
            'transaction_id' => $transaction->id,
            'reviewer_id'    => auth()->id(),
            'seller_id'      => $transaction->seller_id,
            'listing_id'     => $transaction->listing_id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        // Update seller's average rating
        $transaction->seller->recalculateRating();

        return back()->with('success', '⭐ Review submitted! Thank you for your feedback.');
    }

    // Delete own review
    public function destroy(Review $review)
    {
        if ($review->reviewer_id !== auth()->id()) {
            abort(403);
        }

        $seller = $review->seller;
        $review->delete();

        // Recalculate seller rating after deletion
        $seller->recalculateRating();

        return back()->with('success', 'Review deleted.');
    }
}
