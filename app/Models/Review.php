<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'transaction_id',
        'reviewer_id',
        'seller_id',
        'listing_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
    ];

    // The transaction this review belongs to
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    // The buyer who wrote the review
    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // The selller being reviewed
    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }
    // The listings that was purchased
    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    // Star display helper
    public function stars(): string
    {
        $fullStar = '<span class="text-yellow-400">★</span>';   // or use SVG below
        $emptyStar = '<span class="text-gray-600">☆</span>';

        // Better: Use inline SVG for real icons
        $full = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21 16.54 13.47 22 9.24 14.81 8.62 12 2 9.19 8.62 2 9.24 7.46 13.47 5.82 21z"/></svg>';

        $empty = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.975 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L3.464 9.11c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>';

        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $html .= $i <= $this->rating ? $full : $empty;
        }

        return $html;
    }

}
